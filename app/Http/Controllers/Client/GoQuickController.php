<?php
namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Validation\ValidationException;
use App\Models\GoQuickUse;
use App\Models\GoQuickUsageHistory;
use App\Models\JobTool;
use App\Jobs\ProcessGoQuickJob;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GoQuickController extends Controller
{
    private function getApiUrl()
    {
        return config('services.go_quick.url', env('GO_QUICK_API_URL', 'http://127.0.0.1:5000/api/go-quick'));
    }

    /**
     * Check usage và TRỪ NGAY nếu đủ (reserve limit)
     */
    private function checkUsageAndDeduct($requiredCount)
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.'
            ];
        }

        $use = GoQuickUse::where('user_id', $user->id)->first();
        
        if (!$use) {
            return [
                'success' => false,
                'message' => 'Bạn chưa có gói sử dụng. Vui lòng mua gói để tiếp tục.'
            ];
        }

        if ($use->cccd_limit < $requiredCount) {
            return [
                'success' => false,
                'message' => 'Bạn đã hết lượt sử dụng. Bạn còn ' . $use->cccd_limit . ' lượt nhưng cần ' . $requiredCount . ' lượt. Vui lòng mua thêm gói để tiếp tục.'
            ];
        }

        // TRỪ NGAY khi đủ
        $use->cccd_limit -= $requiredCount;
        $use->save();

        return [
            'success' => true,
            'use' => $use,
            'deducted' => $requiredCount
        ];
    }

    /**
     * Legacy method - giữ lại để backward compatibility
     */
    private function checkUsage($requiredCount = 1)
    {
        return $this->checkUsageAndDeduct($requiredCount);
    }

    private function updateUsage($customerCount)
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        $use = GoQuickUse::where('user_id', $user->id)->first();
        if (!$use) {
            return;
        }

        $use->cccd_limit = max(0, $use->cccd_limit - $customerCount);
        $use->total_used += 1;
        $use->total_cccd_extracted += $customerCount;
        $use->save();
    }

    private function getCustomerCount($apiResponse)
    {
        if (!is_array($apiResponse)) {
            return 0;
        }

        // Kiểm tra customer ở top level (cho batch jobs)
        if (isset($apiResponse['customer']) && is_array($apiResponse['customer'])) {
            return count($apiResponse['customer']);
        }

        // Kiểm tra customer trong data (cho /read-quick API)
        if (isset($apiResponse['data']['customer']) && is_array($apiResponse['data']['customer'])) {
            return count($apiResponse['data']['customer']);
        }

        // Kiểm tra data trực tiếp là customer array (fallback)
        if (isset($apiResponse['data']) && is_array($apiResponse['data']) && isset($apiResponse['data']['customer']) && is_array($apiResponse['data']['customer'])) {
            return count($apiResponse['data']['customer']);
        }

        return 0;
    }

    /**
     * Ước tính số CCCD từ images
     * Mỗi CCCD = 2 ảnh (mt + ms)
     */
    private function estimateCccdFromImages($images)
    {
        if (!is_array($images)) {
            $images = [$images];
        }

        $validImages = array_filter($images, function($image) {
            return $image !== null && $image->isValid();
        });

        $imageCount = count($validImages);
        // Mỗi CCCD = 2 ảnh
        return (int) ceil($imageCount / 2);
    }

    /**
     * Ước tính số CCCD từ ZIP file
     * Mỗi CCCD = 2 file (mt + ms)
     */
    private function estimateCccdFromZip($zipPath)
    {
        if (!file_exists($zipPath)) {
            return 0;
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== TRUE) {
            return 0;
        }

        $fileCount = 0;
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            // Bỏ qua folder và file ẩn
            if ($filename && strpos($filename, '/') === false && strpos($filename, '__MACOSX') === false) {
                $fileCount++;
            }
        }
        $zip->close();

        // Mỗi CCCD = 2 file
        return (int) ceil($fileCount / 2);
    }

    /**
     * Ước tính số CCCD từ PDF file
     * Mỗi CCCD = 2 trang (mt + ms)
     */
    private function estimateCccdFromPdf($pdfPath)
    {
        if (!file_exists($pdfPath)) {
            return 0;
        }

        try {
            // Sử dụng PyMuPDF (fitz) qua command line hoặc thư viện PHP
            // Tạm thời dùng cách đơn giản: đọc file và đếm số lần xuất hiện của "/Type /Page"
            $content = file_get_contents($pdfPath);
            $pageCount = preg_match_all('/\/Type[\s]*\/Page[^s]/', $content);
            
            if ($pageCount === 0) {
                // Fallback: thử cách khác
                $pageCount = preg_match_all('/\/Count[\s]+(\d+)/', $content, $matches);
                if (!empty($matches[1])) {
                    $pageCount = (int) max($matches[1]);
                }
            }

            // Mỗi CCCD = 2 trang
            return (int) ceil($pageCount / 2);
        } catch (\Exception $e) {
            Log::warning("Error estimating CCCD from PDF: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Ước tính số CCCD từ Excel file
     * Mỗi dòng = 1 CCCD
     */
    private function estimateCccdFromExcel($excelPath)
    {
        if (!file_exists($excelPath)) {
            return 0;
        }

        try {
            $spreadsheet = IOFactory::load($excelPath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            $rowCount = 0;
            $highestRow = $worksheet->getHighestRow();
            
            // Đếm số dòng có data (bỏ qua header và dòng trống)
            for ($row = 2; $row <= $highestRow; $row++) {
                $cellValue = $worksheet->getCell('A' . $row)->getValue();
                if (!empty(trim($cellValue))) {
                    $rowCount++;
                }
            }

            return $rowCount;
        } catch (\Exception $e) {
            Log::warning("Error estimating CCCD from Excel: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Tạo usage history record
     */
    private function createUsageHistory($jobId, $uploadType, $estimatedCccd, $batchIndex = null, $totalBatches = null)
    {
        return $this->createUsageHistoryWithDeducted($jobId, $uploadType, $estimatedCccd, $estimatedCccd, $batchIndex, $totalBatches);
    }

    /**
     * Tạo usage history record với cccd_deducted tùy chỉnh
     */
    private function createUsageHistoryWithDeducted($jobId, $uploadType, $estimatedCccd, $cccdDeducted, $batchIndex = null, $totalBatches = null)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $use = GoQuickUse::where('user_id', $user->id)->first();
        if (!$use) {
            return null;
        }

        return GoQuickUsageHistory::create([
            'user_id' => $user->id,
            'go_quick_use_id' => $use->id,
            'job_id' => $jobId,
            'upload_type' => $uploadType,
            'batch_index' => $batchIndex,
            'total_batches' => $totalBatches,
            'estimated_cccd' => $estimatedCccd,
            'actual_cccd' => null,
            'cccd_deducted' => $cccdDeducted,
            'status' => 'pending',
        ]);
    }

    /**
     * Update usage khi job complete (adjust chênh lệch)
     */
    public function updateUsageOnComplete($jobId, $actualCccd)
    {
        $history = GoQuickUsageHistory::where('job_id', $jobId)->first();
        if (!$history) {
            Log::warning("Usage history not found for job_id: {$jobId}");
            return;
        }

        $use = GoQuickUse::where('user_id', $history->user_id)->first();
        if (!$use) {
            Log::warning("GoQuickUse not found for user_id: {$history->user_id}");
            return;
        }

        $estimatedCccd = $history->estimated_cccd;
        $diff = $actualCccd - $estimatedCccd;
        
        $cccdLimitBefore = $use->cccd_limit;

        // Update history
        $history->actual_cccd = $actualCccd;
        $history->cccd_deducted = $actualCccd;
        $history->status = 'completed';
        $history->save();

        // Adjust GoQuickUse
        if ($diff > 0) {
            // Thực tế > ước tính → trừ thêm
            $use->cccd_limit -= $diff;
            Log::info("Job {$jobId}: Actual ({$actualCccd}) > Estimated ({$estimatedCccd}), trừ thêm {$diff}. Limit: {$cccdLimitBefore} → {$use->cccd_limit}");
        } elseif ($diff < 0) {
            // Thực tế < ước tính → refund
            $refundAmount = abs($diff);
            $use->cccd_limit += $refundAmount;
            Log::info("Job {$jobId}: Actual ({$actualCccd}) < Estimated ({$estimatedCccd}), refund {$refundAmount}. Limit: {$cccdLimitBefore} → {$use->cccd_limit}");
        } else {
            // actual = estimated, không cần adjust
            Log::info("Job {$jobId}: Actual ({$actualCccd}) = Estimated ({$estimatedCccd}), không cần adjust. Limit: {$cccdLimitBefore}");
        }

        $use->total_used += 1;
        $use->total_cccd_extracted += $actualCccd;
        $use->save();
        
        Log::info("Job {$jobId}: Usage updated. total_used: {$use->total_used}, total_cccd_extracted: {$use->total_cccd_extracted}");
    }

    /**
     * Refund usage khi job cancelled hoặc failed
     */
    public function refundUsageOnCancel($jobId)
    {
        $history = GoQuickUsageHistory::where('job_id', $jobId)->first();
        if (!$history) {
            Log::warning("Usage history not found for job_id: {$jobId}");
            return;
        }

        if ($history->cccd_deducted <= 0) {
            // Chưa trừ gì, chỉ update status
            $history->status = $history->status === 'processing' ? 'cancelled' : $history->status;
            $history->save();
            return;
        }

        $use = GoQuickUse::where('user_id', $history->user_id)->first();
        if (!$use) {
            Log::warning("GoQuickUse not found for user_id: {$history->user_id}");
            return;
        }

        // Refund toàn bộ
        $refundAmount = $history->cccd_deducted;
        $use->cccd_limit += $refundAmount;
        $use->save();

        // Update history
        $history->cccd_deducted = 0;
        $history->status = $history->status === 'processing' ? 'cancelled' : $history->status;
        $history->save();

        Log::info("Job {$jobId}: Refunded {$refundAmount} CCCD limit");
    }

    public function healthCheck()
    {
        try {
            $response = Http::timeout(5)->get($this->getApiUrl() . '/health');
            
            if ($response->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Go Quick API is running',
                    'data' => $response->json()
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'API không phản hồi'
            ], 500);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể kết nối API: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processCCCD(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:zip|max:102400'
                ], [
                    'file.required' => 'Vui lòng chọn file ZIP',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file ZIP. File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File ZIP không được vượt quá 100MB',
                ]);
                
                // Gửi file trực tiếp qua multipart/form-data (theo README)
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($this->getApiUrl() . '/process-cccd');
            }
            elseif ($request->has('inp_path')) {
                $request->validate([
                    'inp_path' => 'required|string'
                ]);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-cccd', [
                        'inp_path' => $request->inp_path
                    ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file hoặc base64 string'
                ], 400);
            }
            
            if ($response->successful()) {
                $apiResult = $response->json();
                $customerCount = $this->getCustomerCount($apiResult);
                
                if ($customerCount > 0) {
                    $usageCheck = $this->checkUsage($customerCount);
                    if (!$usageCheck['success']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    
                    $this->updateUsage($customerCount);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $apiResult
                ]);
            }
            
            // Log chi tiết lỗi từ API
            $statusCode = $response->status();
            $errorBody = $response->body();
            $errorJson = null;
            
            try {
                $errorJson = $response->json();
            } catch (\Exception $e) {
                Log::warning('Go Quick Process CCCD: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
            }
            
            Log::error('Go Quick Process CCCD API Error', [
                'status' => $statusCode,
                'body' => substr($errorBody, 0, 1000),
                'json' => $errorJson
            ]);
            
            $errorMessage = 'API trả về lỗi';
            if ($errorJson && isset($errorJson['message'])) {
                $apiMessage = $errorJson['message'];
                if (strpos($apiMessage, 'No such file or directory') !== false) {
                    $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                } else {
                    $errorMessage = $apiMessage;
                }
            } elseif ($statusCode === 500) {
                $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
            } elseif ($statusCode === 503) {
                $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
                'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
            ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý upload 2 ảnh (mặt trước và mặt sau)
     * Đổi tên thành 1mt, 1ms và nén thành ZIP rồi gửi qua API process-cccd
     */
    public function processCCCDImages(Request $request)
    {
        try {
            // Trừ cccd_limit trước (estimated = 1 cho 1 CCCD)
            $estimatedCccd = 1;
            $usageCheck = $this->checkUsageAndDeduct($estimatedCccd);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            $request->validate([
                'mt' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
                'ms' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            ], [
                'mt.required' => 'Vui lòng chọn ảnh mặt trước CCCD',
                'mt.image' => 'File mặt trước phải là ảnh (JPG, PNG, GIF, WEBP)',
                'mt.mimes' => 'File mặt trước chỉ chấp nhận định dạng: JPG, PNG, GIF, WEBP',
                'mt.max' => 'File mặt trước không được vượt quá 5MB',
                'ms.required' => 'Vui lòng chọn ảnh mặt sau CCCD',
                'ms.image' => 'File mặt sau phải là ảnh (JPG, PNG, GIF, WEBP)',
                'ms.mimes' => 'File mặt sau chỉ chấp nhận định dạng: JPG, PNG, GIF, WEBP',
                'ms.max' => 'File mặt sau không được vượt quá 5MB',
            ]);

            $frontImage = $request->file('mt');
            $backImage = $request->file('ms');

            // Gọi API read-quick trực tiếp với 2 ảnh (không cần tạo ZIP)
            try {
                $apiUrl = $this->getApiUrl() . '/read-quick';
                
                $response = Http::timeout(600)
                    ->attach('mt', file_get_contents($frontImage->getRealPath()), $frontImage->getClientOriginalName())
                    ->attach('ms', file_get_contents($backImage->getRealPath()), $backImage->getClientOriginalName())
                    ->post($apiUrl);

                    if ($response->successful()) {
                        $apiResult = $response->json();
                        
                        // Debug: Log response để kiểm tra
                        $dataHasCustomer = isset($apiResult['data']) && isset($apiResult['data']['customer']);
                        Log::info("Quick read API response", [
                            'status' => $apiResult['status'] ?? 'unknown',
                            'has_data' => isset($apiResult['data']),
                            'has_customer' => isset($apiResult['customer']),
                            'data_has_customer' => $dataHasCustomer,
                            'customer_count_direct' => isset($apiResult['customer']) ? count($apiResult['customer']) : 0,
                            'customer_count_nested' => $dataHasCustomer ? count($apiResult['data']['customer']) : 0,
                        ]);
                        
                        $actualCccd = $this->getCustomerCount($apiResult);
                        
                        // Adjust cccd_limit dựa trên actual vs estimated
                        $use = $usageCheck['use'];
                        $diff = $actualCccd - $estimatedCccd;
                        
                        if ($diff > 0) {
                            // Thực tế > ước tính → trừ thêm
                            $use->cccd_limit -= $diff;
                            Log::info("Quick read: Actual ({$actualCccd}) > Estimated ({$estimatedCccd}), trừ thêm {$diff}");
                        } elseif ($diff < 0) {
                            // Thực tế < ước tính → refund
                            $refundAmount = abs($diff);
                            $use->cccd_limit += $refundAmount;
                            Log::info("Quick read: Actual ({$actualCccd}) < Estimated ({$estimatedCccd}), refund {$refundAmount}");
                        }
                        
                        $use->total_used += 1;
                        $use->total_cccd_extracted += $actualCccd;
                        $use->save();
                        
                        // Nếu API trả về nested structure, flatten nó
                        $responseData = $apiResult;
                        if (isset($apiResult['data']) && is_array($apiResult['data'])) {
                            // API trả về {"status": "success", "data": {"customer": [...]}}
                            // Flatten thành {"status": "success", "customer": [...]}
                            $responseData = $apiResult['data'];
                            if (isset($apiResult['status'])) {
                                $responseData['status'] = $apiResult['status'];
                            }
                        }
                        
                        return response()->json([
                            'status' => 'success',
                            'data' => $responseData
                        ]);
                    }
                    
                    // API lỗi → refund lại
                    $use = $usageCheck['use'];
                    $use->cccd_limit += $estimatedCccd;
                    $use->save();
                    Log::info("Quick read: API lỗi (status: {$response->status()}), refund {$estimatedCccd}");

                    // Log chi tiết lỗi từ API
                    $statusCode = $response->status();
                    $errorBody = $response->body();
                    $errorJson = null;
                    
                    try {
                        $errorJson = $response->json();
                    } catch (\Exception $e) {
                        Log::warning('Go Quick CCCD Images: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
                    }
                    
                    Log::error('Go Quick CCCD Images API Error', [
                        'status' => $statusCode,
                        'body' => substr($errorBody, 0, 1000),
                        'json' => $errorJson
                    ]);
                    
                    $errorMessage = 'API trả về lỗi';
                    if ($errorJson && isset($errorJson['message'])) {
                        $apiMessage = $errorJson['message'];
                        // Kiểm tra nếu là lỗi file không tồn tại
                        if (strpos($apiMessage, 'No such file or directory') !== false) {
                            $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                        } else {
                            $errorMessage = $apiMessage;
                        }
                    } elseif ($statusCode === 500) {
                        $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
                    } elseif ($statusCode === 503) {
                        $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
                    }

                    return response()->json([
                        'status' => 'error',
                        'message' => $errorMessage,
                        'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
                    ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
                } catch (\Illuminate\Http\Client\ConnectionException $e) {
                    // Connection error → refund
                    $use = $usageCheck['use'];
                    $use->cccd_limit += $estimatedCccd;
                    $use->save();
                    Log::info("Quick read: Connection error, refund {$estimatedCccd}");
                    Log::error('API Connection Error: ' . $e->getMessage());
                    throw new \Exception('Không thể kết nối đến API server. Vui lòng kiểm tra API server có đang chạy không.');
                } catch (\Exception $e) {
                    // Any error → refund
                    $use = $usageCheck['use'];
                    $use->cccd_limit += $estimatedCccd;
                    $use->save();
                    Log::info("Quick read: Exception, refund {$estimatedCccd}");
                    throw $e;
                }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Images Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xử lý upload nhiều ảnh (folder)
     * Nén các ảnh thành ZIP và gửi qua API process-cccd
     * Yêu cầu: Tên file phải đúng format (1mt, 1ms, 2mt, 2ms...)
     */
    public function processCCCDMultipleImages(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            $request->validate([
                'images' => 'required|array',
                'images.*' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            ], [
                'images.required' => 'Vui lòng chọn ít nhất một ảnh',
                'images.array' => 'Dữ liệu ảnh không hợp lệ',
                'images.*.required' => 'Vui lòng chọn ảnh',
                'images.*.image' => 'Tất cả file phải là ảnh (JPG, PNG, GIF, WEBP)',
                'images.*.mimes' => 'Chỉ chấp nhận file ảnh định dạng: JPG, PNG, GIF, WEBP',
                'images.*.max' => 'Mỗi file ảnh không được vượt quá 5MB',
            ]);

            $images = $request->file('images');

            // Tạo thư mục temp để lưu file
            $tempDir = storage_path('app/temp/go_quick_' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            try {
                // Kiểm tra và đổi tên file nếu cần
                $imagePairs = [];
                foreach ($images as $image) {
                    $originalName = $image->getClientOriginalName();
                    $extension = $image->getClientOriginalExtension();
                    
                    // Kiểm tra tên file có đúng format không (1mt, 1ms, 2mt, 2ms...)
                    if (preg_match('/^(\d+)(mt|ms)\./i', $originalName, $matches)) {
                        // Tên file đã đúng format
                        $newName = $matches[1] . $matches[2] . '.' . $extension;
                    } else {
                        // Tự động đặt tên theo thứ tự (không khuyến nghị)
                        // Tạm thời báo lỗi yêu cầu đổi tên
                        throw new \Exception('Tên file không đúng format. Vui lòng đổi tên file theo format: 1mt, 1ms, 2mt, 2ms...');
                    }

                    $newPath = $tempDir . '/' . $newName;
                    copy($image->getRealPath(), $newPath);
                    $imagePairs[] = ['path' => $newPath, 'name' => $newName];
                }

                // Tạo file ZIP
                $zipPath = $tempDir . '/images.zip';
                $zip = new ZipArchive();
                
                if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                    throw new \Exception('Không thể tạo file ZIP');
                }

                foreach ($imagePairs as $pair) {
                    $zip->addFile($pair['path'], $pair['name']);
                }
                $zip->close();

                // Gửi file ZIP qua API process-cccd
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($zipPath), 'images.zip')
                    ->post($this->getApiUrl() . '/process-cccd');

                // Xóa thư mục temp
                $this->deleteDirectory($tempDir);

                if ($response->successful()) {
                    $apiResult = $response->json();
                    $customerCount = $this->getCustomerCount($apiResult);
                    
                    if ($customerCount > 0) {
                        $usageCheck = $this->checkUsage($customerCount);
                        if (!$usageCheck['success']) {
                            return response()->json([
                                'status' => 'error',
                                'message' => $usageCheck['message']
                            ], 403);
                        }
                        
                        $this->updateUsage($customerCount);
                    }
                    
                    return response()->json([
                        'status' => 'success',
                        'data' => $apiResult
                    ]);
                }

                // Log chi tiết lỗi từ API
                $statusCode = $response->status();
                $errorBody = $response->body();
                $errorJson = null;
                
                try {
                    $errorJson = $response->json();
                } catch (\Exception $e) {
                    Log::warning('Go Quick CCCD Multiple Images: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
                }
                
                Log::error('Go Quick CCCD Multiple Images API Error', [
                    'status' => $statusCode,
                    'body' => substr($errorBody, 0, 1000),
                    'json' => $errorJson
                ]);
                
                $errorMessage = 'API trả về lỗi';
                if ($errorJson && isset($errorJson['message'])) {
                    $errorMessage = $errorJson['message'];
                } elseif ($statusCode === 500) {
                    $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
                } elseif ($statusCode === 503) {
                    $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
                }
                
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                    'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
                ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);

            } catch (\Exception $e) {
                // Xóa thư mục temp nếu có lỗi
                if (file_exists($tempDir)) {
                    $this->deleteDirectory($tempDir);
                }
                throw $e;
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Multiple Images Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa thư mục và tất cả file bên trong
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return;
        }

        try {
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $path = $dir . '/' . $file;
                try {
                    if (is_dir($path)) {
                        $this->deleteDirectory($path);
                    } else {
                        if (file_exists($path) && is_writable($path)) {
                            @unlink($path);
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            if (is_dir($dir) && is_writable($dir)) {
                @rmdir($dir);
            }
        } catch (\Exception $e) {
        }
    }

    /**
     * Export Excel từ dữ liệu CCCD
     */
    public function exportExcel(Request $request)
    {
        try {
            $request->validate([
                'customers' => 'required|array',
                'customers.*.id_card' => 'nullable|string',
                'customers.*.name' => 'nullable|string',
                'customers.*.birth_date' => 'nullable|string',
                'customers.*.gender' => 'nullable|string',
                'customers.*.nationality' => 'nullable|string',
                'customers.*.hometown' => 'nullable|string',
                'customers.*.address' => 'nullable|string',
                'customers.*.created_date' => 'nullable|string',
                'customers.*.place_created' => 'nullable|string',
            ]);

            $customers = $request->input('customers');

            // Tạo Export class
            $export = new class($customers) implements FromArray, WithHeadings, WithColumnWidths, WithStyles {
                protected $customers;

                public function __construct($customers)
                {
                    $this->customers = $customers;
                }

                public function array(): array
                {
                    $data = [];
                    foreach ($this->customers as $customer) {
                        $data[] = [
                            $customer['id_card'] ?? '',
                            $customer['name'] ?? '',
                            $customer['birth_date'] ?? '',
                            $customer['gender'] ?? '',
                            $customer['nationality'] ?? 'Việt Nam',
                            $customer['hometown'] ?? '',
                            $customer['address'] ?? '',
                            $customer['created_date'] ?? '',
                            $customer['place_created'] ?? '',
                        ];
                    }
                    return $data;
                }

                public function headings(): array
                {
                    return [
                        'Số',
                        'Họ và tên',
                        'Ngày sinh',
                        'Giới tính',
                        'Quốc tịch',
                        'Quê quán',
                        'Nơi thường trú',
                        'Ngày cấp',
                        'Nơi cấp'
                    ];
                }

                public function columnWidths(): array
                {
                    return [
                        'A' => 15,  // Số
                        'B' => 25,  // Họ và tên
                        'C' => 12,  // Ngày sinh
                        'D' => 10,  // Giới tính
                        'E' => 12,  // Quốc tịch
                        'F' => 30,  // Quê quán
                        'G' => 40,  // Nơi thường trú
                        'H' => 12,  // Ngày cấp
                        'I' => 50,  // Nơi cấp
                    ];
                }

                public function styles(Worksheet $sheet)
                {
                    return [
                        1 => ['font' => ['bold' => true]], // Header row
                    ];
                }
            };

            $fileName = 'cccd_data_' . date('Y-m-d_His') . '.xlsx';

            return Excel::download($export, $fileName);

        } catch (\Exception $e) {
            Log::error('Go Quick Export Excel Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xuất file Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processPDF(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:pdf|max:102400' // PDF file, không phải zip
                ], [
                    'file.required' => 'Vui lòng chọn file PDF',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file PDF. File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File PDF không được vượt quá 100MB',
                ]);
                
                // Gửi file trực tiếp qua multipart/form-data
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($this->getApiUrl() . '/process-pdf');
            }
            elseif ($request->has('inp_path')) {
                $request->validate([
                    'inp_path' => 'required|string'
                ]);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-pdf', [
                        'inp_path' => $request->inp_path
                    ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file PDF hoặc base64 string'
                ], 400);
            }
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['zip_base64'])) {
                    $zipData = base64_decode($result['zip_base64']);
                    $zipName = $result['zip_name'] ?? 'pdf_images_' . uniqid() . '.zip';
                    
                    Storage::disk('public')->makeDirectory('go_quick');
                    Storage::disk('public')->put('go_quick/' . $zipName, $zipData);
                    
                    $result['zip_url'] = Storage::url('go_quick/' . $zipName);
                }
                
                $customerCount = $this->getCustomerCount($result);
                if ($customerCount > 0) {
                    $usageCheck = $this->checkUsage($customerCount);
                    if (!$usageCheck['success']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    
                    $this->updateUsage($customerCount);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            // Log chi tiết lỗi từ API
            $statusCode = $response->status();
            $errorBody = $response->body();
            $errorJson = null;
            
            try {
                $errorJson = $response->json();
            } catch (\Exception $e) {
                Log::warning('Go Quick PDF: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
            }
            
            Log::error('Go Quick PDF API Error', [
                'status' => $statusCode,
                'body' => substr($errorBody, 0, 1000),
                'json' => $errorJson
            ]);
            
            $errorMessage = 'Xử lý PDF thất bại';
            if ($errorJson && isset($errorJson['message'])) {
                $apiMessage = $errorJson['message'];
                // Kiểm tra nếu là lỗi file không tồn tại
                if (strpos($apiMessage, 'No such file or directory') !== false) {
                    $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                } else {
                    $errorMessage = $apiMessage;
                }
            } elseif ($statusCode === 500) {
                $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
            } elseif ($statusCode === 503) {
                $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
                'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
            ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Go Quick PDF Connection Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể kết nối đến API server. Vui lòng kiểm tra API server có đang chạy không.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Go Quick PDF Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processExcel(Request $request)
    {
        try {
            $usageCheck = $this->checkUsage(1);
            if (!$usageCheck['success']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $usageCheck['message']
                ], 403);
            }

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:xlsx,xls|max:10240'
                ], [
                    'file.required' => 'Vui lòng chọn file Excel',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file Excel (XLSX, XLS). File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File Excel không được vượt quá 10MB',
                ]);
                
                // Gửi file trực tiếp qua multipart/form-data
                $response = Http::timeout(600)
                    ->attach('file', file_get_contents($file->getRealPath()), $file->getClientOriginalName())
                    ->post($this->getApiUrl() . '/process-excel');
            }
            elseif ($request->has('inp_path')) {
                $request->validate([
                    'inp_path' => 'required|string'
                ]);
                
                $response = Http::timeout(600)
                    ->post($this->getApiUrl() . '/process-excel', [
                        'inp_path' => $request->inp_path
                    ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file Excel hoặc base64 string'
                ], 400);
            }
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['zip_base64'])) {
                    $zipData = base64_decode($result['zip_base64']);
                    $zipName = $result['zip_name'] ?? 'excel_images_' . uniqid() . '.zip';
                    
                    Storage::disk('public')->makeDirectory('go_quick');
                    Storage::disk('public')->put('go_quick/' . $zipName, $zipData);
                    
                    $result['zip_url'] = Storage::url('go_quick/' . $zipName);
                }
                
                $customerCount = $this->getCustomerCount($result);
                if ($customerCount > 0) {
                    $usageCheck = $this->checkUsage($customerCount);
                    if (!$usageCheck['success']) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    
                    $this->updateUsage($customerCount);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            // Log chi tiết lỗi từ API
            $statusCode = $response->status();
            $errorBody = $response->body();
            $errorJson = null;
            
            try {
                $errorJson = $response->json();
            } catch (\Exception $e) {
                Log::warning('Go Quick Excel: API response không phải JSON. Status: ' . $statusCode . ', Body: ' . substr($errorBody, 0, 500));
            }
            
            Log::error('Go Quick Excel API Error', [
                'status' => $statusCode,
                'body' => substr($errorBody, 0, 1000),
                'json' => $errorJson
            ]);
            
            $errorMessage = 'Xử lý Excel thất bại';
            if ($errorJson && isset($errorJson['message'])) {
                $apiMessage = $errorJson['message'];
                // Kiểm tra nếu là lỗi file không tồn tại
                if (strpos($apiMessage, 'No such file or directory') !== false) {
                    $errorMessage = 'API gặp lỗi khi xử lý file. Có thể do xử lý nhiều request cùng lúc. Vui lòng thử lại sau vài giây.';
                } else {
                    $errorMessage = $apiMessage;
                }
            } elseif ($statusCode === 500) {
                $errorMessage = 'API server đang bận hoặc gặp lỗi. Vui lòng thử lại sau vài giây.';
            } elseif ($statusCode === 503) {
                $errorMessage = 'API server đang quá tải. Vui lòng thử lại sau vài giây.';
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $errorMessage,
                'error' => $errorJson ?: ['raw_body' => substr($errorBody, 0, 500)]
            ], $statusCode >= 400 && $statusCode < 600 ? $statusCode : 500);
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Go Quick Excel Connection Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể kết nối đến API server. Vui lòng kiểm tra API server có đang chạy không.'
            ], 503);
        } catch (\Exception $e) {
            Log::error('Go Quick Excel Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ], 500);
        }
    }

    public function process(Request $request)
    {
        try {
            $func_type = $request->input('func_type');
            
            if (!$func_type) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Missing func_type'
                ], 400);
            }
            
            $inp_data = null;
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileBytes = file_get_contents($file->getRealPath());
                $base64 = base64_encode($fileBytes);
                $inp_data = $base64;
            }
            elseif ($request->has('inp_path')) {
                $inp_data = $request->input('inp_path');
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file hoặc inp_path'
                ], 400);
            }
            
            $response = Http::timeout(600)
                ->post($this->getApiUrl() . '/process', [
                    'func_type' => $func_type,
                    'inp_path' => $inp_data
                ]);
            
            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['zip_base64'])) {
                    $zipData = base64_decode($result['zip_base64']);
                    $zipName = $result['zip_name'] ?? 'result_' . uniqid() . '.zip';
                    
                    Storage::disk('public')->makeDirectory('go_quick');
                    Storage::disk('public')->put('go_quick/' . $zipName, $zipData);
                    
                    $result['zip_url'] = Storage::url('go_quick/' . $zipName);
                }
                
                return response()->json([
                    'status' => 'success',
                    'data' => $result
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Xử lý thất bại',
                'error' => $response->json()
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('Go Quick Process Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * SSE Streaming: Process CCCD với progress updates
     * Tránh connection timeout khi xử lý nhiều CCCD
     */
    public function processCCCDStream(Request $request)
    {
        return $this->streamApiRequest($request, '/process-cccd-stream');
    }

    /**
     * SSE Streaming: Process PDF với progress updates
     */
    public function processPDFStream(Request $request)
    {
        return $this->streamApiRequest($request, '/process-pdf-stream');
    }

    /**
     * SSE Streaming: Process Excel với progress updates
     */
    public function processExcelStream(Request $request)
    {
        return $this->streamApiRequest($request, '/process-excel-stream');
    }

    /**
     * SSE Streaming: Process multiple images với progress updates
     */
    public function processImagesStream(Request $request)
    {
        ini_set('display_errors', '0');
        error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
        
        set_time_limit(300); // 5 minutes
        ini_set('memory_limit', '512M');
        
        $currentMaxFiles = ini_get('max_file_uploads');
        if ($currentMaxFiles < 200) {
            ini_set('max_file_uploads', '200');
        }
        
        try {
            if (!$request->hasFile('images')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng chọn ít nhất một ảnh'
                ], 400);
            }

            $images = $request->file('images');
            
            if (!is_array($images)) {
                $images = [$images];
            }
            
            $images = array_filter($images, function($image) {
                return $image !== null && $image->isValid();
            });
            
            if (empty($images)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không có ảnh hợp lệ nào được chọn'
                ], 400);
            }
            
            try {
                $request->validate([
                    'images.*' => 'required|image|mimes:jpeg,jpg,png|max:10240'
                ], [
                    'images.*.required' => 'Vui lòng chọn ảnh',
                    'images.*.image' => 'File phải là ảnh',
                    'images.*.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png',
                    'images.*.max' => 'Kích thước ảnh không được vượt quá 10MB'
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi validation: ' . $e->getMessage(),
                    'errors' => $e->errors()
                ], 422);
            }

            // Chia thành batches
            $batchIndexFromRequest = $request->input('batch_index', 1);
            $totalBatchesFromRequest = $request->input('total_batches', 1);
            $totalEstimatedCccdFromRequest = $request->input('total_estimated_cccd', null); // Frontend gửi tổng estimated
            
            if ($totalBatchesFromRequest > 1) {
                $batches = [$images];
                $totalBatches = $totalBatchesFromRequest;
            } else {
                $maxImagesPerBatch = 150;
                $maxSizePerBatch = 1024 * 1024 * 1024;
                
                $batches = [];
                $currentBatch = [];
                $currentBatchSize = 0;
                
                foreach ($images as $image) {
                    $imageSize = $image->getSize();
                    
                    if (count($currentBatch) >= $maxImagesPerBatch || 
                        ($currentBatchSize + $imageSize) > $maxSizePerBatch) {
                        if (!empty($currentBatch)) {
                            $batches[] = $currentBatch;
                            $currentBatch = [];
                            $currentBatchSize = 0;
                        }
                    }
                    
                    $currentBatch[] = $image;
                    $currentBatchSize += $imageSize;
                }
                
                if (!empty($currentBatch)) {
                    $batches[] = $currentBatch;
                }
                
                $totalBatches = count($batches);
            }

            // Estimate total_cccd cho batch hiện tại
            $batchEstimates = [];
            $currentBatchEstimatedCccd = 0;
            foreach ($batches as $batchIndex => $batch) {
                $estimatedCccd = $this->estimateCccdFromImages($batch);
                $batchEstimates[$batchIndex] = $estimatedCccd;
                $currentBatchEstimatedCccd += $estimatedCccd;
            }

            // Nếu có nhiều batch, cần check tổng estimated của TẤT CẢ batch
            $user = Auth::user();
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.'
                ], 403);
            }
            
            if ($totalBatchesFromRequest > 1) {
                // Frontend đã gửi total_estimated_cccd, dùng nó để check
                if ($totalEstimatedCccdFromRequest !== null) {
                    $totalEstimatedCccd = (int) $totalEstimatedCccdFromRequest;
                } else {
                    // Fallback: ước tính dựa trên batch hiện tại và total_batches
                    // Giả sử mỗi batch có số CCCD tương tự
                    $avgCccdPerBatch = $currentBatchEstimatedCccd;
                    $totalEstimatedCccd = $avgCccdPerBatch * $totalBatchesFromRequest;
                }
                
                // Check limit cho TẤT CẢ batch (chỉ check ở batch đầu tiên)
                if ($batchIndexFromRequest == 1) {
                    $usageCheck = $this->checkUsageAndDeduct($totalEstimatedCccd);
                    if (!$usageCheck['success']) {
                        Log::info("processImagesStream: Batch 1 - Usage check failed for total batches", [
                            'user_id' => $user->id,
                            'total_batches' => $totalBatchesFromRequest,
                            'total_estimated_cccd' => $totalEstimatedCccd,
                            'message' => $usageCheck['message']
                        ]);
                        return response()->json([
                            'status' => 'error',
                            'message' => $usageCheck['message']
                        ], 403);
                    }
                    Log::info("processImagesStream: Batch 1 - Reserved limit for all batches", [
                        'user_id' => $user->id,
                        'total_batches' => $totalBatchesFromRequest,
                        'total_estimated_cccd' => $totalEstimatedCccd,
                        'deducted' => $usageCheck['deducted'] ?? $totalEstimatedCccd
                    ]);
                } else {
                    // Batch 2 trở đi: verify rằng batch 1 đã được tạo và đã trừ limit
                    // Tìm history của batch 1 cùng user và total_batches
                    $batch1History = GoQuickUsageHistory::where('user_id', $user->id)
                        ->where('total_batches', $totalBatchesFromRequest)
                        ->where('batch_index', 1)
                        ->where('status', '!=', 'cancelled')
                        ->first();
                    
                    if (!$batch1History || $batch1History->cccd_deducted <= 0) {
                        // Batch 1 chưa được tạo hoặc chưa trừ limit → return error
                        Log::warning("processImagesStream: Batch {$batchIndexFromRequest} - Batch 1 not found or not deducted", [
                            'user_id' => $user->id,
                            'total_batches' => $totalBatchesFromRequest,
                            'batch1_history' => $batch1History ? $batch1History->toArray() : null
                        ]);
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Batch đầu tiên chưa được xử lý. Vui lòng thử lại từ đầu.'
                        ], 403);
                    }
                    
                    Log::info("processImagesStream: Batch {$batchIndexFromRequest} - Verified batch 1 exists and limit deducted", [
                        'user_id' => $user->id,
                        'total_batches' => $totalBatchesFromRequest,
                        'batch1_cccd_deducted' => $batch1History->cccd_deducted
                    ]);
                }
            } else {
                // Single batch: check như bình thường
                $usageCheck = $this->checkUsageAndDeduct($currentBatchEstimatedCccd);
                if (!$usageCheck['success']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $usageCheck['message']
                    ], 403);
                }
            }

            $jobIds = [];
            $errors = [];
            
            foreach ($batches as $batchIndex => $batch) {
                try {
                    $zipPath = storage_path('app/temp/go_quick_' . uniqid() . '_batch_' . ($batchIndex + 1) . '_images.zip');
                    $zip = new \ZipArchive();
                    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                        $errors[] = "Không thể tạo file ZIP cho batch " . ($batchIndex + 1);
                        Log::error("Failed to create ZIP for batch " . ($batchIndex + 1));
                        continue;
                    }

                    foreach ($batch as $image) {
                        try {
                            $imageName = $image->getClientOriginalName();
                            $realPath = $image->getRealPath();
                            if ($realPath && file_exists($realPath)) {
                                $zip->addFile($realPath, $imageName);
                            } else {
                                Log::warning("Image file not found: " . $imageName);
                            }
                        } catch (\Exception $e) {
                            Log::warning("Error adding image to ZIP: " . $e->getMessage());
                        }
                    }
                    $zip->close();

                    $job = JobTool::create([
                        'user_id' => auth()->id(),
                        'tool' => 'go-quick',
                        'action' => 'process-cccd',
                        'status' => 'pending',
                        'progress' => 0,
                        'params' => [
                            'file_path' => $zipPath,
                            'file_type' => 'zip',
                            'batch_index' => $batchIndex + 1,
                            'total_batches' => $totalBatches,
                        ],
                    ]);

                    ProcessGoQuickJob::dispatch($job->id, $job->params);
                    
                    // Tạo usage history với estimated_cccd
                    $estimatedCccd = $batchEstimates[$batchIndex];
                    $uploadType = $totalBatches > 1 ? 'images_batch' : 'images_single';
                    $jobId = $job->id ?? $job->getKey();
                    
                    try {
                        // Nếu có nhiều batch:
                        // - Batch 1: cccd_deducted = total_estimated_cccd (đã trừ tổng ở batch 1)
                        // - Batch 2 trở đi: cccd_deducted = 0 (chưa trừ, vì đã trừ ở batch 1)
                        $cccdDeducted = 0;
                        if ($totalBatches > 1) {
                            if ($batchIndexFromRequest == 1) {
                                // Batch 1: đã trừ tổng estimated của TẤT CẢ batch
                                $cccdDeducted = $totalEstimatedCccd ?? $totalEstimatedCccdFromRequest ?? $estimatedCccd;
                            } else {
                                // Batch 2 trở đi: chưa trừ (đã trừ ở batch 1)
                                $cccdDeducted = 0;
                            }
                        } else {
                            // Single batch: trừ như bình thường
                            $cccdDeducted = $estimatedCccd;
                        }
                        
                        $history = $this->createUsageHistoryWithDeducted(
                            $jobId,
                            $uploadType,
                            $estimatedCccd,
                            $cccdDeducted,
                            $totalBatches > 1 ? $batchIndexFromRequest : null,
                            $totalBatches > 1 ? $totalBatches : null
                        );
                        if (!$history) {
                            Log::warning("Failed to create usage history for batch " . ($batchIndex + 1) . ", job_id: {$jobId}");
                        }
                    } catch (\Exception $historyError) {
                        Log::error("Error creating usage history for batch " . ($batchIndex + 1) . ": " . $historyError->getMessage());
                        // Không throw exception, chỉ log vì job đã được tạo
                    }
                    
                    $jobIds[] = $job->id;
                    Log::info("Created job for batch " . ($batchIndex + 1) . "/{$totalBatches}: {$job->id}, estimated_cccd: {$estimatedCccd}");
                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                    // Log chi tiết để debug
                    Log::error("Error creating batch " . ($batchIndex + 1) . ": " . $errorMsg, [
                        'user_id' => auth()->id(),
                        'batch_index' => $batchIndex + 1,
                        'total_batches' => $totalBatches,
                        'exception_class' => get_class($e),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    // Nếu lỗi là về authentication, có thể là session timeout
                    if (strpos($errorMsg, 'đăng nhập') !== false || strpos($errorMsg, 'authentication') !== false) {
                        $errorMsg = "Lỗi xác thực khi tạo batch " . ($batchIndex + 1) . ". Vui lòng thử lại.";
                    }
                    $errors[] = "Batch " . ($batchIndex + 1) . ": " . $errorMsg;
                }
            }
            
            if (empty($jobIds)) {
                Log::error("processImagesStream: No jobs created", ['errors' => $errors]);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không thể tạo job nào. ' . implode('; ', $errors)
                ], 500, [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'X-Content-Type-Options' => 'nosniff'
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            
            $response = [
                'status' => 'success',
                'job_ids' => $jobIds,
                'job_id' => $jobIds[0],
                'total_batches' => $totalBatches,
                'created_batches' => count($jobIds),
                'message' => "Đã chia thành {$totalBatches} batch và tạo " . count($jobIds) . " job để xử lý"
            ];
            
            if (!empty($errors)) {
                $response['warnings'] = $errors;
            }
            
            Log::info("processImagesStream completed: " . count($jobIds) . " jobs created from {$totalBatches} batches", [
                'job_ids' => $jobIds,
                'total_batches' => $totalBatches,
                'response' => $response
            ]);
            
            try {
                $jsonString = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($jsonString === false) {
                    $jsonError = json_last_error_msg();
                    Log::error("JSON encode error: " . $jsonError, ['response' => $response]);
                    throw new \Exception("Không thể encode JSON: " . $jsonError);
                }
                
                Log::debug("processImagesStream returning JSON response", [
                    'json_length' => strlen($jsonString),
                    'job_count' => count($jobIds),
                    'json_preview' => substr($jsonString, 0, 500),
                    'json_full' => $jsonString
                ]);
                
                return response()->json($response, 200, [
                    'Content-Type' => 'application/json; charset=utf-8',
                    'X-Content-Type-Options' => 'nosniff',
                    'Content-Length' => strlen($jsonString),
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0'
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } catch (\Exception $jsonError) {
                Log::error('Error creating JSON response: ' . $jsonError->getMessage(), [
                    'response' => $response
                ]);
                $fallbackResponse = [
                    'status' => 'success',
                    'job_ids' => $jobIds,
                    'job_id' => $jobIds[0] ?? null,
                    'total_batches' => $totalBatches,
                    'message' => "Đã tạo " . count($jobIds) . " job"
                ];
                return response()->json($fallbackResponse, 200, [
                    'Content-Type' => 'application/json; charset=utf-8'
                ]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in processImagesStream: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422, [
                'Content-Type' => 'application/json; charset=utf-8',
                'X-Content-Type-Options' => 'nosniff'
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
            Log::error('Error in processImagesStream: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi xử lý: ' . $e->getMessage()
            ], 500, [
                'Content-Type' => 'application/json; charset=utf-8',
                'X-Content-Type-Options' => 'nosniff'
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
    }

    // ==================== ASYNC JOB METHODS ====================
    
    /**
     * Start async job để xử lý CCCD với Queue System
     * Tạo job và trả về job_id, frontend sẽ dùng SSE để stream progress
     */
    public function processCCCDAsync(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:zip|max:102400'
                ], [
                    'file.required' => 'Vui lòng chọn file ZIP',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file ZIP. File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File ZIP không được vượt quá 100MB',
                ]);
                
                $tempPath = storage_path('app/temp/go_quick_' . uniqid() . '_' . $file->getClientOriginalName());
                $file->move(storage_path('app/temp'), basename($tempPath));
                
                // Estimate total_cccd
                $estimatedCccd = $this->estimateCccdFromZip($tempPath);
                
                // Check và TRỪ trước khi tạo job
                $usageCheck = $this->checkUsageAndDeduct($estimatedCccd);
                if (!$usageCheck['success']) {
                    // Xóa temp file nếu không đủ limit
                    @unlink($tempPath);
                    return response()->json([
                        'status' => 'error',
                        'message' => $usageCheck['message']
                    ], 403);
                }
                
                $job = \App\Models\JobTool::create([
                    'user_id' => auth()->id() ?? 0,
                    'tool' => 'go-quick',
                    'action' => 'process-cccd',
                    'status' => 'pending',
                    'progress' => 0,
                    'params' => [
                        'file_path' => $tempPath,
                        'file_type' => 'zip',
                    ],
                ]);

                $jobId = $job->id ?? $job->getKey();
                
                // Tạo usage history
                $this->createUsageHistory($jobId, 'zip', $estimatedCccd);
                
                Log::info("Dispatching ProcessGoQuickJob for job_id: {$jobId}, estimated_cccd: {$estimatedCccd}");
                \App\Jobs\ProcessGoQuickJob::dispatch($jobId, $job->params);
                Log::info("ProcessGoQuickJob dispatched successfully for job_id: {$jobId}");

                return response()->json([
                    'status' => 'success',
                    'job_id' => $job->id,
                    'message' => 'Job đã được tạo và đang xử lý',
                ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file ZIP'
                ], 400);
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process CCCD Async Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start async job để xử lý PDF với Queue System
     * Tạo job và trả về job_id, frontend sẽ dùng SSE để stream progress
     */
    public function processPDFAsync(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:pdf|max:102400'
                ], [
                    'file.required' => 'Vui lòng chọn file PDF',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file PDF. File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File PDF không được vượt quá 100MB',
                ]);
                
                $tempPath = storage_path('app/temp/go_quick_' . uniqid() . '_' . $file->getClientOriginalName());
                $file->move(storage_path('app/temp'), basename($tempPath));
                
                // Estimate total_cccd
                $estimatedCccd = $this->estimateCccdFromPdf($tempPath);
                
                // Check và TRỪ trước khi tạo job
                $usageCheck = $this->checkUsageAndDeduct($estimatedCccd);
                if (!$usageCheck['success']) {
                    // Xóa temp file nếu không đủ limit
                    @unlink($tempPath);
                    return response()->json([
                        'status' => 'error',
                        'message' => $usageCheck['message']
                    ], 403);
                }
                
                $job = \App\Models\JobTool::create([
                    'user_id' => auth()->id() ?? 0,
                    'tool' => 'go-quick',
                    'action' => 'process-pdf',
                    'status' => 'pending',
                    'progress' => 0,
                    'params' => [
                        'file_path' => $tempPath,
                        'file_type' => 'pdf',
                    ],
                ]);

                $jobId = $job->id ?? $job->getKey();
                
                // Tạo usage history
                $this->createUsageHistory($jobId, 'pdf', $estimatedCccd);
                
                Log::info("Dispatching ProcessGoQuickJob for job_id: {$jobId}, estimated_cccd: {$estimatedCccd}");
                \App\Jobs\ProcessGoQuickJob::dispatch($jobId, $job->params);
                Log::info("ProcessGoQuickJob dispatched successfully for job_id: {$jobId}");

                return response()->json([
                    'status' => 'success',
                    'job_id' => $job->id,
                    'message' => 'Job đã được tạo và đang xử lý',
                ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file PDF'
                ], 400);
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process PDF Async Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start async job để xử lý Excel với Queue System
     * Tạo job và trả về job_id, frontend sẽ dùng SSE để stream progress
     */
    public function processExcelAsync(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                
                $request->validate([
                    'file' => 'required|file|mimes:xlsx,xls|max:10240'
                ], [
                    'file.required' => 'Vui lòng chọn file Excel',
                    'file.file' => 'File không hợp lệ',
                    'file.mimes' => 'Chỉ chấp nhận file Excel (XLSX, XLS). File của bạn có định dạng: ' . ($file->getClientOriginalExtension() ?? 'unknown'),
                    'file.max' => 'File Excel không được vượt quá 10MB',
                ]);
                
                $tempPath = storage_path('app/temp/go_quick_' . uniqid() . '_' . $file->getClientOriginalName());
                $file->move(storage_path('app/temp'), basename($tempPath));
                
                // Estimate total_cccd
                $estimatedCccd = $this->estimateCccdFromExcel($tempPath);
                
                // Check và TRỪ trước khi tạo job
                $usageCheck = $this->checkUsageAndDeduct($estimatedCccd);
                if (!$usageCheck['success']) {
                    // Xóa temp file nếu không đủ limit
                    @unlink($tempPath);
                    return response()->json([
                        'status' => 'error',
                        'message' => $usageCheck['message']
                    ], 403);
                }
                
                $job = \App\Models\JobTool::create([
                    'user_id' => auth()->id() ?? 0,
                    'tool' => 'go-quick',
                    'action' => 'process-excel',
                    'status' => 'pending',
                    'progress' => 0,
                    'params' => [
                        'file_path' => $tempPath,
                        'file_type' => 'excel',
                    ],
                ]);

                $jobId = $job->id ?? $job->getKey();
                
                // Tạo usage history
                $this->createUsageHistory($jobId, 'excel', $estimatedCccd);
                
                Log::info("Dispatching ProcessGoQuickJob for job_id: {$jobId}, estimated_cccd: {$estimatedCccd}");
                \App\Jobs\ProcessGoQuickJob::dispatch($jobId, $job->params);
                Log::info("ProcessGoQuickJob dispatched successfully for job_id: {$jobId}");

                return response()->json([
                    'status' => 'success',
                    'job_id' => $job->id,
                    'message' => 'Job đã được tạo và đang xử lý',
                ]);
            }
            else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Vui lòng cung cấp file Excel'
                ], 400);
            }
            
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Go Quick Process Excel Async Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi khi tạo job: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy status của job từ Redis (không cần gọi API server nữa)
     */
    public function getJobStatus($jobId)
    {
        try {
            $job = \App\Models\JobTool::find($jobId);
            if (!$job) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Job không tồn tại'
                ], 404);
            }
            
            try {
                $redis = \Illuminate\Support\Facades\Redis::connection()->client();
                $status = $redis->get("job:{$jobId}:status");
                $result = $redis->get("job:{$jobId}:result");
                
                if ($status) {
                    $status = $status->decode('utf-8');
                }
                
                $responseData = [
                    'status' => 'success',
                    'data' => [
                        'job_id' => $jobId,
                        'status' => $status ?: $job->status,
                        'progress' => $job->progress,
                        'message' => $job->message ?? 'Đang xử lý...',
                    ]
                ];
                
                if ($result) {
                    $resultData = json_decode($result->decode('utf-8'), true);
                    $responseData['data']['result'] = $resultData;
                }
                
                return response()->json($responseData);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'job_id' => $jobId,
                        'status' => $job->status,
                        'progress' => $job->progress,
                        'message' => $job->message ?? 'Đang xử lý...',
                    ]
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Go Quick Get Job Status Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy kết quả của job
     */
    public function getJobResult($jobId)
    {
        try {
            $response = Http::timeout(10)->get($this->getApiUrl() . '/job-result/' . $jobId);
            
            if ($response->successful()) {
                $result = $response->json();
                
                // Nếu job completed, check và update usage
                if (isset($result['data']) && isset($result['data']['customer'])) {
                    $customerCount = count($result['data']['customer']);
                    if ($customerCount > 0) {
                        $user = Auth::user();
                        if ($user) {
                            $usageCheck = $this->checkUsage($customerCount);
                            if ($usageCheck['success']) {
                                $this->updateUsage($customerCount);
                            } else {
                                // Job đã xong nhưng không đủ lượt - vẫn trả về kết quả nhưng báo lỗi
                                return response()->json([
                                    'status' => 'error',
                                    'message' => $usageCheck['message'],
                                    'data' => $result['data']
                                ], 403);
                            }
                        }
                    }
                }
                
                return response()->json($result);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Lỗi lấy kết quả'
            ], $response->status());
            
        } catch (\Exception $e) {
            Log::error('Go Quick Get Job Result Error: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Forward streaming request to API
     */
    private function streamApiRequest(Request $request, $endpoint)
    {
        // Check user authentication
        $user = Auth::user();
        if (!$user) {
            return response()->stream(function () {
                echo "data: " . json_encode(['type' => 'error', 'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.']) . "\n\n";
                ob_flush();
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no',
            ]);
        }

        // Check usage
        $usageCheck = $this->checkUsage(1);
        if (!$usageCheck['success']) {
            return response()->stream(function () use ($usageCheck) {
                echo "data: " . json_encode(['type' => 'error', 'message' => $usageCheck['message']]) . "\n\n";
                ob_flush();
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no',
            ]);
        }

        $apiUrl = $this->getApiUrl() . $endpoint;
        
        // Copy file sang thư mục tạm để tránh bị xóa trong quá trình streaming
        $tempFilePath = null;
        $originalFileName = null;
        $shouldDeleteTemp = false;
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName();
            
            // Copy file sang storage/app/temp
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $tempFilePath = $tempDir . '/' . uniqid('upload_') . '_' . $originalFileName;
            copy($file->getRealPath(), $tempFilePath);
            $shouldDeleteTemp = true;
        }

        return response()->stream(function () use ($apiUrl, $tempFilePath, $originalFileName, $shouldDeleteTemp) {
            // Disable PHP timeout
            set_time_limit(0);
            ignore_user_abort(true);
            
            // Disable output buffering
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            try {
                if (!$tempFilePath || !file_exists($tempFilePath)) {
                    echo "data: " . json_encode(['type' => 'error', 'message' => 'Không có file được cung cấp']) . "\n\n";
                    flush();
                    return;
                }

                // Sử dụng cURL để forward request với streaming
                $ch = curl_init();
                
                $cfile = new \CURLFile($tempFilePath, mime_content_type($tempFilePath), $originalFileName);
                
                curl_setopt_array($ch, [
                    CURLOPT_URL => $apiUrl,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => ['file' => $cfile],
                    CURLOPT_RETURNTRANSFER => false,
                    CURLOPT_TIMEOUT => 0, // No timeout
                    CURLOPT_CONNECTTIMEOUT => 60,
                    CURLOPT_LOW_SPEED_LIMIT => 1, // 1 byte/s minimum
                    CURLOPT_LOW_SPEED_TIME => 1800, // Allow 30 minutes of low speed
                    CURLOPT_WRITEFUNCTION => function($ch, $data) {
                        // Write immediately without buffering
                        echo $data;
                        // Flush all output buffers
                        while (ob_get_level() > 0) {
                            ob_end_flush();
                        }
                        flush();
                        // Force PHP to send data immediately
                        if (function_exists('fastcgi_finish_request')) {
                            fastcgi_finish_request();
                        }
                        return strlen($data);
                    },
                    CURLOPT_HTTPHEADER => [
                        'Accept: text/event-stream',
                        'Connection: keep-alive',
                        'Cache-Control: no-cache',
                        'X-Accel-Buffering: no',
                    ],
                    CURLOPT_TCP_KEEPALIVE => 1,
                    CURLOPT_TCP_KEEPIDLE => 60,
                    CURLOPT_TCP_KEEPINTVL => 30,
                    CURLOPT_BUFFERSIZE => 16384, // 16KB buffer
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Use HTTP/1.1
                ]);
                
                $result = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    $error = curl_error($ch);
                    Log::error('Go Quick Stream cURL Error: ' . $error);
                    echo "data: " . json_encode(['type' => 'error', 'message' => 'Lỗi kết nối: ' . $error]) . "\n\n";
                    flush();
                }
                
                curl_close($ch);

            } catch (\Exception $e) {
                Log::error('Go Quick Stream Error: ' . $e->getMessage());
                echo "data: " . json_encode(['type' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]) . "\n\n";
                flush();
            } finally {
                // Cleanup temp file
                if ($shouldDeleteTemp && $tempFilePath && file_exists($tempFilePath)) {
                    @unlink($tempFilePath);
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * Helper: Forward streaming request for multiple images to API
     */
    private function streamImagesApiRequest(Request $request, $endpoint)
    {
        // Check user authentication
        $user = Auth::user();
        if (!$user) {
            return response()->stream(function () {
                echo "data: " . json_encode(['type' => 'error', 'message' => 'Bạn cần đăng nhập để sử dụng tính năng này.']) . "\n\n";
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no',
            ]);
        }

        // Check usage
        $usageCheck = $this->checkUsage(1);
        if (!$usageCheck['success']) {
            return response()->stream(function () use ($usageCheck) {
                echo "data: " . json_encode(['type' => 'error', 'message' => $usageCheck['message']]) . "\n\n";
                flush();
            }, 200, [
                'Content-Type' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
                'Connection' => 'keep-alive',
                'X-Accel-Buffering' => 'no',
            ]);
        }

        $apiUrl = $this->getApiUrl() . $endpoint;
        
        // Copy files sang thư mục tạm để tránh bị xóa
        $tempFiles = [];
        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $tempPath = $tempDir . '/' . uniqid('img_') . '_' . $image->getClientOriginalName();
                copy($image->getRealPath(), $tempPath);
                $tempFiles[] = [
                    'path' => $tempPath,
                    'name' => $image->getClientOriginalName(),
                    'mime' => $image->getMimeType()
                ];
            }
        }

        return response()->stream(function () use ($apiUrl, $tempFiles) {
            // Disable PHP timeout
            set_time_limit(0);
            ignore_user_abort(true);
            
            // Disable output buffering
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            try {
                if (empty($tempFiles)) {
                    echo "data: " . json_encode(['type' => 'error', 'message' => 'Không có ảnh được cung cấp']) . "\n\n";
                    flush();
                    return;
                }

                // Sử dụng cURL để forward request với streaming
                $ch = curl_init();
                
                $postFields = [];
                foreach ($tempFiles as $index => $imageInfo) {
                    if (file_exists($imageInfo['path'])) {
                        $cfile = new \CURLFile($imageInfo['path'], $imageInfo['mime'], $imageInfo['name']);
                        $postFields["images[$index]"] = $cfile;
                    }
                }
                
                curl_setopt_array($ch, [
                    CURLOPT_URL => $apiUrl,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $postFields,
                    CURLOPT_RETURNTRANSFER => false,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_CONNECTTIMEOUT => 60,
                    CURLOPT_LOW_SPEED_LIMIT => 1,
                    CURLOPT_LOW_SPEED_TIME => 1800, // Allow 30 minutes of low speed
                    CURLOPT_WRITEFUNCTION => function($ch, $data) {
                        // Write immediately without buffering
                        echo $data;
                        // Flush all output buffers
                        while (ob_get_level() > 0) {
                            ob_end_flush();
                        }
                        flush();
                        // Force PHP to send data immediately
                        if (function_exists('fastcgi_finish_request')) {
                            fastcgi_finish_request();
                        }
                        return strlen($data);
                    },
                    CURLOPT_HTTPHEADER => [
                        'Accept: text/event-stream',
                        'Connection: keep-alive',
                        'Cache-Control: no-cache',
                        'X-Accel-Buffering: no',
                    ],
                    CURLOPT_TCP_KEEPALIVE => 1,
                    CURLOPT_TCP_KEEPIDLE => 60,
                    CURLOPT_TCP_KEEPINTVL => 30,
                    CURLOPT_BUFFERSIZE => 16384, // 16KB buffer
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // Use HTTP/1.1
                ]);
                
                $result = curl_exec($ch);
                
                if (curl_errno($ch)) {
                    $error = curl_error($ch);
                    Log::error('Go Quick Images Stream cURL Error: ' . $error);
                    echo "data: " . json_encode(['type' => 'error', 'message' => 'Lỗi kết nối: ' . $error]) . "\n\n";
                    flush();
                }
                
                curl_close($ch);

            } catch (\Exception $e) {
                Log::error('Go Quick Images Stream Error: ' . $e->getMessage());
                echo "data: " . json_encode(['type' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()]) . "\n\n";
                flush();
            } finally {
                // Cleanup temp files
                foreach ($tempFiles as $tempFile) {
                    if (isset($tempFile['path']) && file_exists($tempFile['path'])) {
                        @unlink($tempFile['path']);
                    }
                }
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}

