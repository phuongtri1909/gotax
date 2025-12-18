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

class GoQuickProcessJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jobId;
    public $params;

    public function __construct($jobId, $params)
    {
        $this->jobId = $jobId;
        $this->params = $params;
        $this->onQueue('go-quick:jobs');
    }

    public function handle(): void
    {
        $jobData = [
            'job_id' => $this->jobId,
            'params' => $this->params,
            'created_at' => now()->toIso8601String(),
        ];
        
        // Use raw Redis client to avoid prefix
        $redis = Redis::connection()->client();
        $redis->rawCommand('LPUSH', 'go-quick:jobs', json_encode($jobData));
        
        $job = JobTool::find($this->jobId);
        if ($job) {
            $job->update(['status' => 'pending']);
        }
    }
}
