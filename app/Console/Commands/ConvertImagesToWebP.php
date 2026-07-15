<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageService;
use Illuminate\Support\Facades\File;
use App\Models\Events;
use App\Models\Blog;
use App\Models\Store;
use App\Models\Category;
use App\Models\Page;
use App\Models\Slider;
use App\Models\Coupon;
use App\Models\Setting;

class ConvertImagesToWebP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-webp {--dry-run : Show what would be converted without actually converting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert all existing images in uploads directory to WebP format and update database records';

    protected $imageService;
    protected $converted = 0;
    protected $skipped = 0;
    protected $errors = 0;
    protected $updated = 0;

    /**
     * Create a new command instance.
     */
    public function __construct(ImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No files will be converted');
        } else {
            $this->info('🚀 Starting image conversion to WebP...');
        }

        $uploadsPath = public_path('uploads');
        
        if (!File::exists($uploadsPath)) {
            $this->error('Uploads directory not found: ' . $uploadsPath);
            return 1;
        }

        // Get all image files
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];
        $files = File::allFiles($uploadsPath);
        
        $this->info("Found " . count($files) . " files in uploads directory");
        $this->newLine();

        $bar = $this->output->createProgressBar(count($files));
        $bar->start();

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            
            // Skip if already WebP or not an image
            if ($extension === 'webp' || !in_array($extension, $imageExtensions)) {
                $this->skipped++;
                $bar->advance();
                continue;
            }

            // Skip SVG files (they can't be converted to WebP)
            if ($extension === 'svg') {
                $this->skipped++;
                $bar->advance();
                continue;
            }

            $relativePath = 'uploads/' . $file->getFilename();
            $fullPath = $file->getPathname();

            try {
                if (!$dryRun) {
                    // Convert to WebP - preserve original size and use maximum quality
                    $newPath = $this->imageService->convertToWebP($relativePath, [
                        'quality' => 100,
                        'preserve_original' => true
                    ]);

                    if ($newPath) {
                        $this->converted++;
                        
                        // Update database records
                        $this->updateDatabaseRecords($relativePath, $newPath);
                    } else {
                        $this->errors++;
                        $this->warn("Failed to convert: {$relativePath}");
                    }
                } else {
                    $this->converted++;
                    $this->info("Would convert: {$relativePath}");
                }
            } catch (\Exception $e) {
                $this->errors++;
                $this->error("Error converting {$relativePath}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('📊 Conversion Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Converted', $this->converted],
                ['Skipped (already WebP or non-image)', $this->skipped],
                ['Errors', $this->errors],
                ['Database Records Updated', $this->updated],
            ]
        );

        if ($dryRun) {
            $this->warn('This was a dry run. Run without --dry-run to actually convert images.');
        } else {
            $this->info('✅ Conversion completed!');
        }

        return 0;
    }

    /**
     * Update database records with new WebP paths
     */
    protected function updateDatabaseRecords($oldPath, $newPath)
    {
        // Normalize paths (remove leading slash, handle different formats)
        $oldPath = ltrim($oldPath, '/');
        $newPath = ltrim($newPath, '/');
        
        // Also check for paths without 'uploads/' prefix
        $oldPathAlt = str_replace('uploads/', '', $oldPath);
        $oldPathStorage = 'storage/' . $oldPath;

        // Events table
        $this->updateModelRecords(Events::class, [
            'front_image' => $oldPath,
            'button_icon' => $oldPath,
            'cover_image' => $oldPath,
            'no_coupon_cover' => $oldPath,
        ], $newPath);

        // Blogs table
        $this->updateModelRecords(Blog::class, [
            'featured_image' => $oldPath,
        ], $newPath);

        // Stores table
        $this->updateModelRecords(Store::class, [
            'store_logo' => $oldPath,
            'cover_image' => $oldPath,
        ], $newPath);

        // Categories table
        $this->updateModelRecords(Category::class, [
            'media' => $oldPath,
        ], $newPath);

        // Pages table
        $this->updateModelRecords(Page::class, [
            'media' => $oldPath,
            'banner_image' => $oldPath,
        ], $newPath);

        // Sliders table
        $this->updateModelRecords(Slider::class, [
            'background_image' => $oldPath,
        ], $newPath);

        // Coupons table
        $this->updateModelRecords(Coupon::class, [
            'cover_logo' => $oldPath,
        ], $newPath);

        // Settings table (key-value pairs)
        $settingKeys = ['site_logo', 'site_favicon', 'home_banner'];
        foreach ($settingKeys as $key) {
            $setting = Setting::where('key', $key)->first();
            if ($setting && $setting->value) {
                $settingValue = ltrim($setting->value, '/');
                
                // Check if setting value matches old path
                if ($settingValue === $oldPath || 
                    $settingValue === $oldPathAlt || 
                    $settingValue === $oldPathStorage ||
                    basename($settingValue) === basename($oldPath)) {
                    $setting->value = $newPath;
                    $setting->save();
                    $this->updated++;
                }
            }
        }
    }

    /**
     * Update model records for given fields
     */
    protected function updateModelRecords($modelClass, $fields, $newPath)
    {
        foreach ($fields as $field => $oldPath) {
            // Try exact match
            $records = $modelClass::where($field, $oldPath)->get();
            
            // Also try with different path formats
            if ($records->isEmpty()) {
                $oldPathAlt = str_replace('uploads/', '', $oldPath);
                $records = $modelClass::where($field, $oldPathAlt)->get();
            }
            
            if ($records->isEmpty()) {
                $oldPathStorage = 'storage/' . $oldPath;
                $records = $modelClass::where($field, $oldPathStorage)->get();
            }

            // Try matching by filename only
            if ($records->isEmpty()) {
                $filename = basename($oldPath);
                $records = $modelClass::where($field, 'like', '%' . $filename)->get();
            }

            foreach ($records as $record) {
                $record->$field = $newPath;
                $record->save();
                $this->updated++;
            }
        }
    }
}

