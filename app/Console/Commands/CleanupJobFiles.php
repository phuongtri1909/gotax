<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupJobFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobs:cleanup 
                            {--days=1 : Số ngày để giữ lại files (mặc định: 1 ngày)}
                            {--dry-run : Chỉ hiển thị files sẽ bị xóa, không xóa thực sự}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Xóa các job files cũ trong storage/app/jobs/';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        
        $this->info("Bắt đầu cleanup job files (giữ lại files < {$days} ngày)...");
        
        if ($dryRun) {
            $this->warn("DRY RUN MODE - Không xóa files thực sự");
        }
        
        $jobsDir = storage_path('app/jobs');
        
        if (!File::exists($jobsDir)) {
            $this->warn("Thư mục {$jobsDir} không tồn tại.");
            return 0;
        }
        
        $cutoffTime = Carbon::now()->subDays($days);
        $deletedCount = 0;
        $deletedSize = 0;
        $keptCount = 0;
        
        // Get all job directories
        $jobDirs = File::directories($jobsDir);
        
        $this->info("Tìm thấy " . count($jobDirs) . " job directories.");
        
        $progressBar = $this->output->createProgressBar(count($jobDirs));
        $progressBar->start();
        
        foreach ($jobDirs as $jobDir) {
            $progressBar->advance();
            
            $jobId = basename($jobDir);
            $dirModifiedTime = Carbon::createFromTimestamp(File::lastModified($jobDir));
            
            // Check if directory is older than cutoff
            if ($dirModifiedTime->lt($cutoffTime)) {
                // Get total size before deletion
                $dirSize = $this->getDirectorySize($jobDir);
                
                if (!$dryRun) {
                    // Delete entire directory
                    File::deleteDirectory($jobDir);
                }
                
                $deletedCount++;
                $deletedSize += $dirSize;
                
                if ($this->getOutput()->isVerbose()) {
                    $this->line("\n  Xóa: {$jobId} (modified: {$dirModifiedTime->format('Y-m-d H:i:s')}, size: " . $this->formatBytes($dirSize) . ")");
                }
            } else {
                $keptCount++;
            }
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info("Kết quả:");
        $this->line("  - Đã xóa: {$deletedCount} job directories");
        $this->line("  - Giữ lại: {$keptCount} job directories");
        $this->line("  - Dung lượng giải phóng: " . $this->formatBytes($deletedSize));
        
        if ($dryRun) {
            $this->warn("\nDRY RUN - Không có files nào bị xóa thực sự.");
            $this->info("Chạy lại không có --dry-run để xóa files.");
        } else {
            $this->info("\nCleanup hoàn thành!");
        }
        
        return 0;
    }
    
    /**
     * Get directory size in bytes
     */
    private function getDirectorySize($directory)
    {
        $size = 0;
        foreach (File::allFiles($directory) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

# Xóa files cũ hơn 1 ngày
// php artisan jobs:cleanup

# Xóa files cũ hơn 3 ngày
// php artisan jobs:cleanup --days=3

# Xem trước (không xóa thực sự)
// php artisan jobs:cleanup --dry-run

# Verbose mode (hiển thị chi tiết)
// php artisan jobs:cleanup -v

# Thêm vào crontab
// * * * * * cd /path/to/gotax && php artisan schedule:run >> /dev/null 2>&1