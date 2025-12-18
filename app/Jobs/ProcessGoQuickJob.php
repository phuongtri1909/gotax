<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Models\JobTool;

class ProcessGoQuickJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jobId;
    public $params;

    /**
     * Create a new job instance.
     */
    public function __construct($jobId, $params)
    {
        $this->jobId = $jobId;
        $this->params = $params;
        
        // Set queue name
        $this->onQueue('go-quick:jobs');
    }

    /**
     * Execute the job.
     * 
     * Job này chỉ push vào Redis queue để Python worker consume
     * Python worker sẽ xử lý thực tế
     */
    public function handle(): void
    {
        Log::info("ProcessGoQuickJob::handle() called for job_id: {$this->jobId}");
        
        $job = JobTool::find($this->jobId);
        $action = $job ? $job->action : 'process-pdf';
        
        $jobData = [
            'job_id' => $this->jobId,
            'action' => $action,
            'params' => $this->params,
            'created_at' => now()->toIso8601String(),
        ];
        
        $jobDataJson = json_encode($jobData);
        Log::info("Pushing job to Redis queue: go-quick:jobs", ['job_data' => $jobData]);
        
        try {
            $queueName = 'go-quick:jobs';
            
            $redis = Redis::connection()->client();
            
            $redis->rawCommand('LPUSH', $queueName, $jobDataJson);
            
            $queueLength = $redis->rawCommand('LLEN', $queueName);
            Log::info("Job pushed to Redis queue successfully for job_id: {$this->jobId}", [
                'queue' => $queueName,
                'queue_length' => $queueLength
            ]);
        } catch (\Exception $e) {
            Log::error("Error pushing job to Redis queue: " . $e->getMessage());
            throw $e;
        }
        
        $job = JobTool::find($this->jobId);
        if ($job) {
            $job->update([
                'status' => 'pending',
            ]);
            Log::info("Job status updated to pending in MySQL for job_id: {$this->jobId}");
        } else {
            Log::warning("Job not found in MySQL for job_id: {$this->jobId}");
        }
    }
}



