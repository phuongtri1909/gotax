<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobTool;
use App\Http\Controllers\Client\GoQuickController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CleanupStuckJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:cleanup-stuck 
                            {--hours=2 : Số giờ để coi job là stuck (mặc định: 2 giờ)}
                            {--dry-run : Chỉ hiển thị jobs sẽ được cleanup, không xử lý thực sự}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup stuck jobs (processing/pending quá lâu) và refund cccd_limit cho go-quick jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = (int) $this->option('hours');
        $dryRun = $this->option('dry-run');
        
        $this->info("Bắt đầu cleanup stuck jobs (jobs stuck > {$hours} giờ)...");
        
        if ($dryRun) {
            $this->warn("DRY RUN MODE - Không xử lý thực sự");
        }
        
        $cutoffTime = Carbon::now()->subHours($hours);
        
        // Tìm các jobs bị stuck (pending hoặc processing quá lâu)
        $stuckJobs = JobTool::whereIn('status', ['pending', 'processing'])
            ->where('updated_at', '<', $cutoffTime)
            ->get();
        
        $this->info("Tìm thấy {$stuckJobs->count()} stuck job(s)");
        
        if ($stuckJobs->isEmpty()) {
            $this->info("Không có stuck jobs nào cần cleanup.");
            return 0;
        }
        
        $refundedCount = 0;
        $failedCount = 0;
        
        foreach ($stuckJobs as $job) {
            $this->line("Job {$job->id}: status={$job->status}, updated_at={$job->updated_at}, tool={$job->tool}");
            
            if ($dryRun) {
                $this->warn("  [DRY RUN] Sẽ mark as failed và refund (nếu là go-quick)");
                continue;
            }
            
            try {
                // Mark job as failed
                $job->update([
                    'status' => 'failed',
                    'error' => "Job bị stuck quá {$hours} giờ, tự động cleanup"
                ]);
                
                // Refund usage nếu là go-quick job
                if ($job->tool === 'go-quick') {
                    try {
                        $goQuickController = new GoQuickController();
                        $goQuickController->refundUsageOnCancel($job->id);
                        $refundedCount++;
                        $this->info("  ✅ Đã refund cccd_limit cho job {$job->id}");
                        Log::info("CleanupStuckJobs: Refunded job {$job->id} (stuck {$hours} hours)");
                    } catch (\Exception $e) {
                        $failedCount++;
                        $this->error("  ❌ Lỗi refund job {$job->id}: " . $e->getMessage());
                        Log::error("CleanupStuckJobs: Error refunding job {$job->id}: " . $e->getMessage());
                    }
                } else {
                    $this->line("  ⏭️  Skip refund (không phải go-quick job)");
                }
            } catch (\Exception $e) {
                $failedCount++;
                $this->error("  ❌ Lỗi update job {$job->id}: " . $e->getMessage());
                Log::error("CleanupStuckJobs: Error updating job {$job->id}: " . $e->getMessage());
            }
        }
        
        $this->info("\n✅ Cleanup hoàn thành!");
        $this->info("  - Tổng stuck jobs: {$stuckJobs->count()}");
        $this->info("  - Đã refund: {$refundedCount}");
        if ($failedCount > 0) {
            $this->warn("  - Lỗi: {$failedCount}");
        }
        
        return 0;
    }
}

