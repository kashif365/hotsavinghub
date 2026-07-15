<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UnusedImageDetector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CleanupUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:cleanup 
                            {--dry-run : Show what would be deleted without actually deleting}
                            {--force : Skip confirmation prompt}
                            {--directories= : Comma-separated directories to scan (default: public,storage/app/public)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Detect and delete unused images from public and storage directories';

    protected $detector;
    protected $deletedCount = 0;
    protected $errorCount = 0;
    protected $deletedFiles = [];

    /**
     * Create a new command instance.
     */
    public function __construct(UnusedImageDetector $detector)
    {
        parent::__construct();
        $this->detector = $detector;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        
        $this->info('🗑️  Unused Image Cleanup Tool');
        $this->info('================================');
        $this->newLine();
        
        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No files will be deleted');
            $this->newLine();
        }
        
        // Set output callback for detector
        $this->detector->setOutputCallback(function($message) {
            $this->line($message);
        });
        
        // Get directories to scan
        $directories = $this->getDirectories();
        
        // Step 1: Scan for unused images
        $this->info('Step 1: Scanning for unused images...');
        $this->newLine();
        
        $results = $this->detector->scan($directories);
        
        $unusedImages = $results['unused_images'];
        $stats = $results['stats'];
        
        // Display statistics
        $this->displayStatistics($results);
        
        if (empty($unusedImages)) {
            $this->info('✅ No unused images found! All images are in use.');
            return 0;
        }
        
        // Step 2: Save report
        $this->newLine();
        $this->info('Step 2: Generating report...');
        $this->saveReport($unusedImages, $results);
        
        // Step 3: Display unused images
        $this->newLine();
        $this->displayUnusedImages($unusedImages);
        
        // Step 4: Confirm deletion
        if ($dryRun) {
            $this->warn('This was a dry run. Run without --dry-run to actually delete files.');
            return 0;
        }
        
        if (!$force) {
            $this->newLine();
            $totalSize = $this->calculateTotalSize($unusedImages);
            $this->warn("⚠️  WARNING: You are about to delete " . count($unusedImages) . " unused images (" . $this->formatBytes($totalSize) . ")");
            $this->warn("This action cannot be undone!");
            $this->newLine();
            
            if (!$this->confirm('Do you want to proceed with deletion?', false)) {
                $this->info('❌ Deletion cancelled.');
                return 0;
            }
        }
        
        // Step 5: Delete unused images
        $this->newLine();
        $this->info('Step 3: Deleting unused images...');
        $this->deleteUnusedImages($unusedImages);
        
        // Step 6: Final summary
        $this->newLine();
        $this->displayFinalSummary();
        
        return 0;
    }

    /**
     * Get directories to scan
     */
    protected function getDirectories()
    {
        $directories = $this->option('directories');
        
        if ($directories) {
            return explode(',', $directories);
        }
        
        return ['public', 'storage/app/public'];
    }

    /**
     * Display scan statistics
     */
    protected function displayStatistics($results)
    {
        $stats = $results['stats'];
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Images Found', count($results['all_images'])],
                ['Used Images', count($results['used_images'])],
                ['Unused Images', count($results['unused_images'])],
                ['Blade Files Scanned', $stats['blade_files']],
                ['PHP Files Scanned', $stats['php_files']],
                ['CSS Files Scanned', $stats['css_files']],
                ['JS Files Scanned', $stats['js_files']],
                ['Database Records Checked', $stats['database_records']],
            ]
        );
    }

    /**
     * Display unused images list
     */
    protected function displayUnusedImages($unusedImages)
    {
        $this->info('📋 Unused Images List:');
        $this->newLine();
        
        $tableData = [];
        foreach (array_slice($unusedImages, 0, 50) as $image) {
            $tableData[] = [
                $image['filename'],
                $this->formatBytes($image['size']),
                $image['path'],
            ];
        }
        
        $this->table(['Filename', 'Size', 'Path'], $tableData);
        
        if (count($unusedImages) > 50) {
            $this->info("... and " . (count($unusedImages) - 50) . " more images");
        }
    }

    /**
     * Save report to JSON file
     */
    protected function saveReport($unusedImages, $results)
    {
        $reportPath = storage_path('logs/unused_images.json');
        
        $report = [
            'scan_date' => now()->toDateTimeString(),
            'total_images' => count($results['all_images']),
            'used_images' => count($results['used_images']),
            'unused_images_count' => count($unusedImages),
            'unused_images' => array_map(function($img) {
                return [
                    'path' => $img['path'],
                    'filename' => $img['filename'],
                    'size' => $img['size'],
                    'size_formatted' => $this->formatBytes($img['size']),
                ];
            }, $unusedImages),
            'statistics' => $results['stats'],
        ];
        
        File::put($reportPath, json_encode($report, JSON_PRETTY_PRINT));
        
        $this->info("✅ Report saved to: {$reportPath}");
    }

    /**
     * Delete unused images
     */
    protected function deleteUnusedImages($unusedImages)
    {
        $bar = $this->output->createProgressBar(count($unusedImages));
        $bar->start();
        
        foreach ($unusedImages as $image) {
            try {
                $fullPath = $image['full_path'];
                
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    $this->deletedFiles[] = $image['path'];
                    $this->deletedCount++;
                } else {
                    $this->errorCount++;
                }
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->error("Failed to delete: {$image['path']} - " . $e->getMessage());
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
    }

    /**
     * Display final summary
     */
    protected function displayFinalSummary()
    {
        $this->info('📊 Cleanup Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Images Deleted', $this->deletedCount],
                ['Errors', $this->errorCount],
            ]
        );
        
        if ($this->deletedCount > 0) {
            $this->info("✅ Successfully deleted {$this->deletedCount} unused images!");
            $this->info("📄 Deleted files list saved in report: storage/logs/unused_images.json");
        }
    }

    /**
     * Calculate total size of unused images
     */
    protected function calculateTotalSize($unusedImages)
    {
        $total = 0;
        foreach ($unusedImages as $image) {
            $total += $image['size'];
        }
        return $total;
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

