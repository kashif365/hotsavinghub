<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Models\Blog;
use App\Models\Store;
use App\Models\Slider;
use App\Models\Page;
use App\Models\Category;
use App\Models\Events;
use App\Models\Coupon;
use App\Models\Setting;

class MigrateStorageToUploads extends Command
{
    protected $signature = 'migrate:storage-to-uploads';
    protected $description = 'Migrate images from public/storage to public/uploads and update database paths';

    public function handle()
    {
        $this->info('Starting migration from public/storage to public/uploads...');

        // Ensure public/uploads directory exists
        $uploadsPath = public_path('uploads');
        if (!File::exists($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
            $this->info('Created public/uploads directory');
        }

        // Check if public/storage/uploads exists
        $storagePath = public_path('storage/uploads');
        if (!File::exists($storagePath)) {
            $this->warn('public/storage/uploads directory does not exist. Nothing to migrate.');
            return;
        }

        // Get all files from storage/uploads
        $files = File::allFiles($storagePath);
        $movedCount = 0;
        $skippedCount = 0;

        foreach ($files as $file) {
            $relativePath = str_replace($storagePath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);
            
            $destinationPath = $uploadsPath . DIRECTORY_SEPARATOR . $relativePath;
            $destinationDir = dirname($destinationPath);
            
            // Create destination directory if needed
            if (!File::exists($destinationDir)) {
                File::makeDirectory($destinationDir, 0755, true);
            }
            
            // Move file if destination doesn't exist
            if (!File::exists($destinationPath)) {
                File::move($file->getPathname(), $destinationPath);
                $movedCount++;
                $this->line("Moved: {$relativePath}");
            } else {
                $skippedCount++;
                $this->warn("Skipped (already exists): {$relativePath}");
            }
        }

        $this->info("Moved {$movedCount} files, skipped {$skippedCount} files");

        // Update database paths
        $this->info('Updating database paths...');
        $this->updateDatabasePaths();

        // Delete public/storage folder if empty
        $this->info('Cleaning up public/storage folder...');
        $this->cleanupStorageFolder();

        $this->info('Migration completed successfully!');
    }

    protected function updateDatabasePaths()
    {
        $updated = 0;

        // Update Blog featured_image
        $blogs = Blog::whereNotNull('featured_image')
            ->where('featured_image', 'like', 'storage/%')
            ->orWhere('featured_image', 'like', 'uploads/%')
            ->get();
        
        foreach ($blogs as $blog) {
            $oldPath = $blog->featured_image;
            $newPath = str_replace('storage/', '', $oldPath);
            
            // If path starts with uploads/, keep it; otherwise prepend uploads/
            if (!str_starts_with($newPath, 'uploads/')) {
                $newPath = 'uploads/' . basename($newPath);
            }
            
            // Check if file exists in new location
            if (file_exists(public_path($newPath))) {
                $blog->featured_image = $newPath;
                $blog->save();
                $updated++;
            }
        }

        // Update Store images
        $stores = Store::where(function($query) {
            $query->where('store_logo', 'like', 'storage/%')
                  ->orWhere('store_logo', 'like', 'uploads/%')
                  ->orWhere('cover_image', 'like', 'storage/%')
                  ->orWhere('cover_image', 'like', 'uploads/%');
        })->get();
        
        foreach ($stores as $store) {
            if ($store->store_logo) {
                $newPath = str_replace('storage/', '', $store->store_logo);
                if (!str_starts_with($newPath, 'uploads/')) {
                    $newPath = 'uploads/' . basename($newPath);
                }
                if (file_exists(public_path($newPath))) {
                    $store->store_logo = $newPath;
                }
            }
            
            if ($store->cover_image) {
                $newPath = str_replace('storage/', '', $store->cover_image);
                if (!str_starts_with($newPath, 'uploads/')) {
                    $newPath = 'uploads/' . basename($newPath);
                }
                if (file_exists(public_path($newPath))) {
                    $store->cover_image = $newPath;
                }
            }
            
            $store->save();
            $updated++;
        }

        // Update Slider background_image
        $sliders = Slider::whereNotNull('background_image')
            ->where('background_image', 'like', 'storage/%')
            ->orWhere('background_image', 'like', 'uploads/%')
            ->get();
        
        foreach ($sliders as $slider) {
            $newPath = str_replace('storage/', '', $slider->background_image);
            if (!str_starts_with($newPath, 'uploads/')) {
                $newPath = 'uploads/' . basename($newPath);
            }
            if (file_exists(public_path($newPath))) {
                $slider->background_image = $newPath;
                $slider->save();
                $updated++;
            }
        }

        // Update Page images
        $pages = Page::where(function($query) {
            $query->where('media', 'like', 'storage/%')
                  ->orWhere('media', 'like', 'uploads/%')
                  ->orWhere('banner_image', 'like', 'storage/%')
                  ->orWhere('banner_image', 'like', 'uploads/%');
        })->get();
        
        foreach ($pages as $page) {
            if ($page->media) {
                $newPath = str_replace('storage/', '', $page->media);
                if (!str_starts_with($newPath, 'uploads/')) {
                    $newPath = 'uploads/' . basename($newPath);
                }
                if (file_exists(public_path($newPath))) {
                    $page->media = $newPath;
                }
            }
            
            if ($page->banner_image) {
                $newPath = str_replace('storage/', '', $page->banner_image);
                if (!str_starts_with($newPath, 'uploads/')) {
                    $newPath = 'uploads/' . basename($newPath);
                }
                if (file_exists(public_path($newPath))) {
                    $page->banner_image = $newPath;
                }
            }
            
            $page->save();
            $updated++;
        }

        // Update Category media
        $categories = Category::whereNotNull('media')
            ->where('media', 'like', 'storage/%')
            ->orWhere('media', 'like', 'uploads/%')
            ->get();
        
        foreach ($categories as $category) {
            $newPath = str_replace('storage/', '', $category->media);
            if (!str_starts_with($newPath, 'uploads/')) {
                $newPath = 'uploads/' . basename($newPath);
            }
            if (file_exists(public_path($newPath))) {
                $category->media = $newPath;
                $category->save();
                $updated++;
            }
        }

        // Update Events images
        $events = Events::where(function($query) {
            $query->where('front_image', 'like', 'storage/%')
                  ->orWhere('front_image', 'like', 'uploads/%')
                  ->orWhere('button_icon', 'like', 'storage/%')
                  ->orWhere('button_icon', 'like', 'uploads/%')
                  ->orWhere('cover_image', 'like', 'storage/%')
                  ->orWhere('cover_image', 'like', 'uploads/%')
                  ->orWhere('no_coupon_cover', 'like', 'storage/%')
                  ->orWhere('no_coupon_cover', 'like', 'uploads/%');
        })->get();
        
        foreach ($events as $event) {
            $fields = ['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'];
            foreach ($fields as $field) {
                if ($event->$field) {
                    $newPath = str_replace('storage/', '', $event->$field);
                    if (!str_starts_with($newPath, 'uploads/')) {
                        $newPath = 'uploads/' . basename($newPath);
                    }
                    if (file_exists(public_path($newPath))) {
                        $event->$field = $newPath;
                    }
                }
            }
            $event->save();
            $updated++;
        }

        // Update Coupon cover_logo
        $coupons = Coupon::whereNotNull('cover_logo')
            ->where('cover_logo', 'like', 'storage/%')
            ->orWhere('cover_logo', 'like', 'uploads/%')
            ->get();
        
        foreach ($coupons as $coupon) {
            $newPath = str_replace('storage/', '', $coupon->cover_logo);
            if (!str_starts_with($newPath, 'uploads/')) {
                $newPath = 'uploads/' . basename($newPath);
            }
            if (file_exists(public_path($newPath))) {
                $coupon->cover_logo = $newPath;
                $coupon->save();
                $updated++;
            }
        }

        // Update Settings
        $settings = ['site_logo', 'site_favicon', 'home_banner'];
        foreach ($settings as $key) {
            $value = Setting::get($key, '');
            if ($value && (str_starts_with($value, 'storage/') || str_starts_with($value, 'uploads/'))) {
                $newPath = str_replace('storage/', '', $value);
                if (!str_starts_with($newPath, 'uploads/')) {
                    $newPath = 'uploads/' . basename($newPath);
                }
                if (file_exists(public_path($newPath))) {
                    Setting::set($key, $newPath);
                    $updated++;
                }
            }
        }

        $this->info("Updated {$updated} database records");
    }

    protected function cleanupStorageFolder()
    {
        $storagePath = public_path('storage');
        
        if (File::exists($storagePath)) {
            // Delete all files and directories in storage
            $files = File::allFiles($storagePath);
            $dirs = File::directories($storagePath);
            
            foreach ($files as $file) {
                File::delete($file);
            }
            
            foreach ($dirs as $dir) {
                File::deleteDirectory($dir);
            }
            
            // Try to delete storage folder itself
            if (File::isEmptyDirectory($storagePath)) {
                File::deleteDirectory($storagePath);
                $this->info('Deleted public/storage folder');
            } else {
                $this->warn('Could not delete public/storage folder (not empty)');
            }
        }
    }
}

