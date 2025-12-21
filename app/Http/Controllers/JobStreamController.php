<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\JobTool;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Client\GoQuickController;

class JobStreamController extends Controller
{
    /**
     * SSE endpoint để stream progress của job
     * GET /api/job/{job_id}/stream
     */
    public function stream($jobId)
    {
        // Verify job exists and belongs to user
        $job = JobTool::find($jobId);
        
        if (!$job) {
            return response()->json([
                'status' => 'error',
                'message' => 'Job not found'
            ], 404);
        }
        
        // Check if user owns this job (optional, nếu cần auth)
        // if (auth()->id() != $job->user_id) {
        //     return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        // }
        
        return response()->stream(function () use ($jobId) {
            // Disable output buffering completely
            @ini_set('zlib.output_compression', 0);
            @ini_set('output_buffering', 'off');
            @ini_set('implicit_flush', 1);
            
            while (ob_get_level() > 0) {
                @ob_end_flush();
            }
            
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('Connection: keep-alive');
            header('X-Accel-Buffering: no');
            
            $lastProgress = 0;
            $lastMessageHash = '';
            $lastActivityTime = time();
            $timeout = 30 * 60;
            $pollInterval = 1;
            
            echo "event: connected\n";
            echo "data: " . json_encode(['message' => 'Connected to job stream']) . "\n\n";
            flush();
            
            try {
                $redis = new \Redis();
                $redis->connect(
                    config('database.redis.default.host', '127.0.0.1'),
                    config('database.redis.default.port', 6379)
                );
                $redis->select((int) config('database.redis.default.database', 0));
                
                if (config('database.redis.default.password')) {
                    $redis->auth(config('database.redis.default.password'));
                }
            } catch (\Exception $e) {
                Log::error("SSE: Lỗi kết nối Redis: " . $e->getMessage());
                echo "event: error\n";
                echo "data: " . json_encode(['error' => 'Lỗi kết nối Redis: ' . $e->getMessage()]) . "\n\n";
                flush();
                return;
            }
            
            $totalCccd = null;
            try {
                $totalCccdStr = $redis->get("job:{$jobId}:total_cccd");
                if ($totalCccdStr) {
                    if (is_string($totalCccdStr)) {
                        $totalCccd = (int) trim($totalCccdStr);
                    } else {
                        $totalCccd = (int) trim((string)$totalCccdStr);
                    }
                }
            } catch (\Exception $e) {
            }
            
            echo "event: progress\n";
            echo "data: " . json_encode([
                'percent' => 0,
                'message' => $totalCccd ? "Bắt đầu xử lý... (0/{$totalCccd} CCCD - 0%)" : 'Đang xử lý...',
                'total_cccd' => $totalCccd,
                'processed_cccd' => 0,
            ]) . "\n\n";
            flush();
            
            try {
                $progressListKey = "job:{$jobId}:progress:list";
                $lastIndex = -1;
                $lastListLength = 0;
                
                $pollCount = 0;
                $completeEventSent = false;
                
                while (true) {
                    if (time() - $lastActivityTime > $timeout) {
                        Log::debug("SSE: Stream timeout sau " . (time() - $lastActivityTime) . " giây không có activity");
                        echo "event: timeout\n";
                        echo "data: " . json_encode(['message' => 'Stream timeout - không có activity trong 30 phút']) . "\n\n";
                        flush();
                        break;
                    }
                    
                    if (connection_aborted()) {
                        $currentJobStatus = $redis->get("job:{$jobId}:status");
                        if (is_string($currentJobStatus)) {
                            $currentJobStatus = trim($currentJobStatus);
                        } elseif ($currentJobStatus !== null) {
                            $currentJobStatus = trim((string)$currentJobStatus);
                        }
                        
                        if ($currentJobStatus === 'completed' || $currentJobStatus === 'failed') {
                            Log::info("SSE: Client disconnected for job {$jobId}, but job is already {$currentJobStatus}, skipping cancellation");
                            break;
                        }
                        
                        Log::info("SSE: Client disconnected for job {$jobId}, cancelling job...");
                        try {
                            $redis->set("job:{$jobId}:cancelled", "1");
                            $redis->set("job:{$jobId}:status", "cancelled");
                            // Update MySQL
                            $job = JobTool::find($jobId);
                            if ($job) {
                                $job->update([
                                    'status' => 'cancelled',
                                    'progress' => $lastProgress
                                ]);
                                
                                // Refund usage khi connection aborted
                                if ($job->tool === 'go-quick') {
                                    try {
                                        $goQuickController = new GoQuickController();
                                        $goQuickController->refundUsageOnCancel($jobId);
                                    } catch (\Exception $e) {
                                        Log::error("Error refunding usage on connection aborted for job {$jobId}: " . $e->getMessage());
                                    }
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error("SSE: Error cancelling job {$jobId}: " . $e->getMessage());
                        }
                        break;
                    }
                    
                    try {
                        $listLength = $redis->llen($progressListKey);
                        
                        if ($lastListLength > 0 && $listLength < $lastListLength) {
                            $lastIndex = -1;
                        } else if ($lastIndex >= $listLength && $listLength > 0) {
                            $lastIndex = -1;
                        } else if ($listLength >= 100 && $lastIndex >= 90) {
                            $lastIndex = -1;
                        }
                        
                        $progressList = $redis->lRange($progressListKey, $lastIndex + 1, -1);
                        
                        $lastListLength = $listLength;
                    } catch (\Exception $e) {
                        Log::error("SSE: Lỗi Redis trong poll loop: " . $e->getMessage());
                        $progressList = [];
                    }
                    
                    // Skip if no progress
                    if (!$progressList || !is_array($progressList)) {
                        $progressList = [];
                    }
                    
                    foreach ($progressList as $progressJson) {
                        if (!is_string($progressJson)) {
                            $progressJson = (string)$progressJson;
                        }
                        
                        $data = json_decode($progressJson, true);
                        
                        if (!$data) {
                            Log::warning("SSE: Failed to parse JSON from Redis", [
                                'raw_json' => substr($progressJson, 0, 200),
                                'json_error' => json_last_error_msg()
                            ]);
                            $lastIndex++;
                            continue;
                        }
                        
                        if ($data) {
                            $percent = $data['percent'] ?? 0;
                            $messageText = $data['message'] ?? 'Đang xử lý...';
                            $eventData = $data['data'] ?? [];
                            
                            $flatData = $eventData;
                            
                            $totalCccd = $data['total_cccd'] ?? null;
                            if ($totalCccd === null) {
                                try {
                                    $totalCccdStr = $redis->get("job:{$jobId}:total_cccd");
                                    if ($totalCccdStr) {
                                        if (is_string($totalCccdStr)) {
                                            $totalCccd = (int) trim($totalCccdStr);
                                        } else {
                                            $totalCccd = (int) trim((string)$totalCccdStr);
                                        }
                                    }
                                } catch (\Exception $e) {
                                }
                            }
                            
                            if ($totalCccd !== null) {
                                $flatData['total_cccd'] = $totalCccd;
                            }
                            if (isset($data['processed_cccd'])) {
                                $flatData['processed_cccd'] = $data['processed_cccd'];
                            }
                            if (isset($data['total_images'])) {
                                $flatData['total_images'] = $data['total_images'];
                            }
                            if (isset($data['processed_images'])) {
                                $flatData['processed_images'] = $data['processed_images'];
                            }
                            
                            $processedCccd = $flatData['processed_cccd'] ?? null;
                            $finalData = [
                                'percent' => $percent,
                                'message' => $messageText,
                                'total_cccd' => $flatData['total_cccd'] ?? null,
                                'processed_cccd' => $flatData['processed_cccd'] ?? null,
                                'total_images' => $flatData['total_images'] ?? null,
                                'processed_images' => $flatData['processed_images'] ?? null,
                                'total_rows' => $flatData['total_rows'] ?? null,
                                'estimated_cccd' => $flatData['estimated_cccd'] ?? null,
                                'processed' => $flatData['processed'] ?? null,
                            ];
                            
                            echo "event: progress\n";
                            echo "data: " . json_encode($finalData) . "\n\n";
                            flush();
                            
                            $lastProgress = $percent;
                        }
                        
                        $lastIndex++;
                    }
                    
                    // Check job status every poll (use raw Redis client to bypass prefix)
                    $jobStatus = $redis->get("job:{$jobId}:status");
                    // Handle bytes response
                    if (is_string($jobStatus)) {
                        $jobStatus = trim($jobStatus);
                    } elseif ($jobStatus !== null) {
                        $jobStatus = trim((string)$jobStatus);
                    }
                    
                    $pollCount++;
                    
                    if ($jobStatus === 'completed' && !$completeEventSent) {
                        $completeEventSent = true;
                        $resultJson = $redis->get("job:{$jobId}:result");
                        $result = null;
                        if ($resultJson) {
                            if (is_string($resultJson)) {
                                $result = json_decode($resultJson, true);
                            } else {
                                $result = json_decode((string)$resultJson, true);
                            }
                        }
                        
                        // Update MySQL
                        $job = JobTool::find($jobId);
                        if ($job) {
                            $resultForDb = $result;
                            if (is_array($resultForDb) && isset($resultForDb['data'])) {
                                $resultForDb = $resultForDb['data'];
                            }
                            if (is_array($resultForDb)) {
                                unset($resultForDb['zip_base64']);
                            }
                            $job->update([
                                'status' => 'completed',
                                'progress' => 100,
                                'result' => $resultForDb,
                            ]);
                        }
                        
                        $actualResult = $result;
                        if (is_array($result) && isset($result['data'])) {
                            $actualResult = $result['data'];
                        }
                        
                        $hasZip = !empty($actualResult['zip_base64']) || !empty($actualResult['download_id']);
                        $zipFilename = $actualResult['zip_filename'] ?? $actualResult['zip_name'] ?? null;
                        $total = $actualResult['total'] ?? 0;
                        $downloadId = $actualResult['download_id'] ?? null;
                        
                        $resultForSSE = [
                            'total' => $total,
                            'zip_filename' => $zipFilename,
                            'has_zip' => $hasZip,
                        ];
                        
                        if ($downloadId) {
                            $resultForSSE['download_id'] = $downloadId;
                        }
                        
                        if ($downloadId) {
                            $apiBaseUrl = env('API_BASE_URL', 'http://127.0.0.1:5000');
                            $downloadUrl = "{$apiBaseUrl}/api/go-soft/download/{$downloadId}";
                            if ($zipFilename) {
                                $downloadUrl .= "?filename=" . urlencode($zipFilename);
                            }
                        } else {
                            // Zip file is in Redis (zip_base64) - use Laravel download endpoint
                            $downloadUrl = "/api/job/{$jobId}/download";
                        }
                        
                        $completeData = [
                            'type' => 'complete',
                            'status' => 'completed',
                            'result' => $resultForSSE,
                            'download_url' => $downloadUrl,
                        ];
                        
                        if (is_array($actualResult) && isset($actualResult['customer'])) {
                            $completeData['data'] = $actualResult;
                        }
                        
                        // Update usage khi job complete (adjust chênh lệch)
                        // LUÔN gọi updateUsageOnComplete dù actualCccd = 0 (để refund nếu không detect được)
                        if ($job && $job->tool === 'go-quick') {
                            try {
                                // Lấy actual_cccd từ result
                                $actualCccd = 0;
                                if (isset($actualResult['customer']) && is_array($actualResult['customer'])) {
                                    $actualCccd = count($actualResult['customer']);
                                } elseif (isset($result['total_cccd'])) {
                                    $actualCccd = (int) $result['total_cccd'];
                                } elseif (isset($result['total_rows'])) {
                                    $actualCccd = (int) $result['total_rows'];
                                } elseif (isset($result['total_images'])) {
                                    $actualCccd = (int) ($result['total_images'] / 2);
                                }
                                
                                // LUÔN gọi updateUsageOnComplete (kể cả khi actualCccd = 0 để refund)
                                $goQuickController = new GoQuickController();
                                $goQuickController->updateUsageOnComplete($jobId, $actualCccd);
                                
                                Log::info("Job {$jobId}: Updated usage on complete, actual_cccd={$actualCccd}");
                            } catch (\Exception $e) {
                                Log::error("Error updating usage on complete for job {$jobId}: " . $e->getMessage());
                            }
                        }
                        
                        echo "event: complete\n";
                        echo "data: " . json_encode($completeData) . "\n\n";
                        flush();
                        break;
                    } elseif ($jobStatus === 'failed') {
                        // Get error from Redis (use raw client)
                        $error = $redis->get("job:{$jobId}:error");
                        if (is_string($error)) {
                            $error = trim($error);
                        } elseif ($error !== null) {
                            $error = trim((string)$error);
                        }
                        
                        // Also update MySQL if job exists
                        $job = JobTool::find($jobId);
                        if ($job) {
                            $job->update([
                                'status' => 'failed',
                                'error' => $error,
                            ]);
                            
                            // Refund usage khi job failed
                            if ($job->tool === 'go-quick') {
                                try {
                                    $goQuickController = new GoQuickController();
                                    $goQuickController->refundUsageOnCancel($jobId);
                                } catch (\Exception $e) {
                                    Log::error("Error refunding usage on failed for job {$jobId}: " . $e->getMessage());
                                }
                            }
                        }
                        
                        echo "event: error\n";
                        echo "data: " . json_encode([
                            'status' => 'failed',
                            'error' => $error,
                        ]) . "\n\n";
                        flush();
                        break;
                    }
                    
                    // Also check MySQL as fallback
                    $job = JobTool::find($jobId);
                    if ($job) {
                        if ($job->status === 'completed') {
                            echo "event: complete\n";
                            echo "data: " . json_encode([
                                'status' => 'completed',
                                'result' => $job->result,
                            ]) . "\n\n";
                            flush();
                            break;
                        } elseif ($job->status === 'failed') {
                            echo "event: error\n";
                            echo "data: " . json_encode([
                                'status' => 'failed',
                                'error' => $job->error,
                            ]) . "\n\n";
                            flush();
                            break;
                        }
                    }
                    
                    // Sleep before next poll
                    usleep($pollInterval * 1000000); // Convert to microseconds
                }
                
            } catch (\Exception $e) {
                Log::error("SSE Stream Error for job {$jobId}: " . $e->getMessage());
                echo "event: error\n";
                echo "data: " . json_encode([
                    'status' => 'error',
                    'message' => 'Stream error: ' . $e->getMessage(),
                ]) . "\n\n";
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
    
    /**
     * Get job status
     * GET /api/job/{job_id}/status
     */
    public function status($jobId)
    {
        $job = JobTool::find($jobId);
        
        if (!$job) {
            return response()->json([
                'status' => 'error',
                'message' => 'Job not found'
            ], 404);
        }
        
        return response()->json([
            'status' => 'success',
            'job' => [
                'id' => $job->id,
                'tool' => $job->tool,
                'action' => $job->action,
                'status' => $job->status,
                'progress' => $job->progress,
                'result' => $job->result,
                'error' => $job->error,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,
            ]
        ]);
    }
    
    /**
     * Get job result
     * GET /api/job/{job_id}/result
     */
    public function result($jobId)
    {
        try {
            $job = JobTool::find($jobId);
            
            if (!$job) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Job not found'
                ], 404);
            }
            
            // Try to get from Redis first (more up-to-date)
            try {
                $redis = new \Redis();
                $redis->connect(
                    config('database.redis.default.host', '127.0.0.1'),
                    config('database.redis.default.port', 6379)
                );
                $redis->select((int) config('database.redis.default.database', 0));
                
                if (config('database.redis.default.password')) {
                    $redis->auth(config('database.redis.default.password'));
                }
                
                $resultJson = $redis->get("job:{$jobId}:result");
                if ($resultJson) {
                    if (is_string($resultJson)) {
                        $result = json_decode($resultJson, true);
                    } else {
                        $result = json_decode((string)$resultJson, true);
                    }
                    
                    if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
                        Log::warning("Failed to decode JSON result for job {$jobId}: " . json_last_error_msg());
                        // Fallback to database
                    } else {
                        // Handle Go Quick format: {status: 'success', data: {...}}
                        if (is_array($result) && isset($result['data'])) {
                            return response()->json([
                                'status' => 'success',
                                'data' => $result['data'],
                            ]);
                        }
                        
                        // Handle direct result
                        return response()->json([
                            'status' => 'success',
                            'data' => $result,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::warning("Could not get result from Redis for job {$jobId}: " . $e->getMessage());
            }
            
            // Fallback to database
            if ($job->status !== 'completed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Job is not completed yet'
                ], 400);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $job->result,
            ]);
        } catch (\Exception $e) {
            Log::error("Error in result() for job {$jobId}: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi lấy kết quả: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Download job result (zip file)
     * GET /api/job/{job_id}/download
     */
    public function download($jobId)
    {
        // Get result from Redis (contains zip_base64)
        try {
            $redis = new \Redis();
            $redis->connect(
                config('database.redis.default.host', '127.0.0.1'),
                config('database.redis.default.port', 6379)
            );
            $redis->select((int) config('database.redis.default.database', 0));
            
            if (config('database.redis.default.password')) {
                $redis->auth(config('database.redis.default.password'));
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Redis connection failed'
            ], 500);
        }
        
        // Check job status
        $jobStatus = $redis->get("job:{$jobId}:status");
        if ($jobStatus) {
            $jobStatus = trim(is_string($jobStatus) ? $jobStatus : (string)$jobStatus);
        }
        
        if ($jobStatus !== 'completed') {
            return response()->json([
                'status' => 'error',
                'message' => 'Job is not completed yet'
            ], 400);
        }
        
        // Get result with zip_base64 or download_id
        $resultJson = $redis->get("job:{$jobId}:result");
        $result = $resultJson ? json_decode($resultJson, true) : null;
        
        if (!$result) {
            Log::warning("Job {$jobId} download: No result found in Redis");
            return response()->json([
                'status' => 'error',
                'message' => 'No download available for this job'
            ], 404);
        }
        
        // Check if we have zip_base64 in Redis
        if (!empty($result['zip_base64'])) {
            // Decode base64 and return as file download
            $zipContent = base64_decode($result['zip_base64']);
            $filename = $result['zip_filename'] ?? "job_{$jobId}.zip";
            
            
            return response($zipContent, 200)
                ->header('Content-Type', 'application/zip')
                ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
                ->header('Content-Length', strlen($zipContent));
        }
        
        // If no zip_base64 but we have download_id, redirect to API server
        if (!empty($result['download_id'])) {
            $downloadId = $result['download_id'];
            $filename = $result['zip_filename'] ?? "job_{$jobId}.zip";
            $apiBaseUrl = env('API_BASE_URL', 'http://127.0.0.1:5000');
            $apiDownloadUrl = "{$apiBaseUrl}/api/go-soft/download/{$downloadId}";
            if ($filename) {
                $apiDownloadUrl .= "?filename=" . urlencode($filename);
            }
            
            
            // Redirect to API server download endpoint
            return redirect($apiDownloadUrl);
        }
        
        // No zip_base64 and no download_id
        Log::warning("Job {$jobId} download: No zip_base64 and no download_id");
        return response()->json([
            'status' => 'error',
            'message' => 'No download available for this job'
        ], 404);
    }
    
    /**
     * Cancel a job
     * POST /api/job/{job_id}/cancel
     */
    public function cancel($jobId)
    {
        try {
            $job = JobTool::find($jobId);
            
            if (!$job) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Job not found'
                ], 404);
            }
            
            // Set cancellation flag in Redis
            try {
                $redis = new \Redis();
                $redis->connect(
                    config('database.redis.default.host', '127.0.0.1'),
                    config('database.redis.default.port', 6379)
                );
                $redis->select((int) config('database.redis.default.database', 0));
                
                if (config('database.redis.default.password')) {
                    $redis->auth(config('database.redis.default.password'));
                }
                
                // Check if job is already completed or failed
                $jobStatus = $redis->get("job:{$jobId}:status");
                if (is_string($jobStatus)) {
                    $jobStatus = trim($jobStatus);
                } elseif ($jobStatus !== null) {
                    $jobStatus = trim((string)$jobStatus);
                }
                
                // Nếu job đã completed hoặc failed thì không cancel
                if ($jobStatus === 'completed' || $jobStatus === 'failed') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Job đã hoàn thành hoặc thất bại, không thể hủy'
                    ], 400);
                }
                
                // Kiểm tra thêm từ MySQL để đảm bảo
                if ($job->status === 'completed' || $job->status === 'failed') {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Job đã hoàn thành hoặc thất bại, không thể hủy'
                    ], 400);
                }
                
                $redis->set("job:{$jobId}:cancelled", "1");
                $redis->set("job:{$jobId}:status", "cancelled");
                
                // Update MySQL
                $job->update([
                    'status' => 'cancelled',
                    'progress' => $job->progress ?? 0
                ]);
                
                // Refund usage khi job cancelled
                if ($job->tool === 'go-quick') {
                    try {
                        $goQuickController = new GoQuickController();
                        $goQuickController->refundUsageOnCancel($jobId);
                    } catch (\Exception $e) {
                        Log::error("Error refunding usage on cancel for job {$jobId}: " . $e->getMessage());
                    }
                }
                
                Log::info("Job {$jobId} cancelled by user");
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Job đã được hủy'
                ]);
            } catch (\Exception $e) {
                Log::error("Error cancelling job {$jobId}: " . $e->getMessage());
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi khi hủy job: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error("Error in cancel() for job {$jobId}: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi hủy job: ' . $e->getMessage()
            ], 500);
        }
    }
}
