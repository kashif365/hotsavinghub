<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UnusedImageDetector
{
    protected $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'];
    protected $scannedFiles = [];
    protected $usedImages = [];
    protected $allImages = [];
    protected $unusedImages = [];
    protected $scanStats = [
        'blade_files' => 0,
        'php_files' => 0,
        'css_files' => 0,
        'js_files' => 0,
        'database_records' => 0,
    ];

    /**
     * Scan all images and detect unused ones
     */
    public function scan($directories = ['public', 'storage/app/public'])
    {
        $this->info('🔍 Starting image scan...');
        
        // Step 1: Collect all images
        $this->collectAllImages($directories);
        
        // Step 2: Scan codebase for image references
        $this->scanCodebase();
        
        // Step 3: Scan database for image references
        $this->scanDatabase();
        
        // Step 4: Find unused images
        $this->findUnusedImages();
        
        return [
            'all_images' => $this->allImages,
            'used_images' => $this->usedImages,
            'unused_images' => $this->unusedImages,
            'stats' => $this->scanStats,
        ];
    }

    /**
     * Collect all image files from specified directories
     */
    protected function collectAllImages($directories)
    {
        $this->info('📁 Collecting all images...');
        
        foreach ($directories as $dir) {
            $fullPath = base_path($dir);
            
            if (!File::exists($fullPath)) {
                continue;
            }
            
            $files = File::allFiles($fullPath);
            
            foreach ($files as $file) {
                $extension = strtolower($file->getExtension());
                
                if (in_array($extension, $this->imageExtensions)) {
                    $relativePath = $this->normalizePath($file->getPathname());
                    $filename = $file->getFilename();
                    $basename = pathinfo($filename, PATHINFO_FILENAME);
                    
                    $this->allImages[] = [
                        'path' => $relativePath,
                        'full_path' => $file->getPathname(),
                        'filename' => $filename,
                        'basename' => $basename,
                        'extension' => $extension,
                        'size' => $file->getSize(),
                    ];
                }
            }
        }
        
        $this->info("Found " . count($this->allImages) . " images total");
    }

    /**
     * Scan codebase files for image references
     */
    protected function scanCodebase()
    {
        $this->info('📝 Scanning codebase files...');
        
        // Scan Blade templates (including frontend views - these are important!)
        $this->scanDirectory(base_path('resources/views'), 'blade');
        
        // Scan Controllers
        $this->scanDirectory(base_path('app/Http/Controllers'), 'php');
        
        // Scan Models
        $this->scanDirectory(base_path('app/Models'), 'php');
        
        // Scan Services
        $this->scanDirectory(base_path('app/Services'), 'php');
        
        // Scan Helpers
        $this->scanDirectory(base_path('app/Helpers'), 'php');
        
        // Scan CSS files
        $this->scanDirectory(public_path('assets/css'), 'css');
        $this->scanDirectory(public_path('frontend_assets/css'), 'css');
        
        // Scan JS files
        $this->scanDirectory(public_path('assets/js'), 'js');
        $this->scanDirectory(public_path('frontend_assets/js'), 'js');
        
        // Scan public directory for CSS/JS
        $this->scanDirectory(public_path('assets'), 'css');
        $this->scanDirectory(public_path('assets'), 'js');
    }

    /**
     * Scan a directory for specific file types
     */
    protected function scanDirectory($path, $type)
    {
        if (!File::exists($path)) {
            return;
        }
        
        $files = File::allFiles($path);
        
        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());
            $filename = strtolower($file->getFilename());
            $filePath = $file->getPathname();
            
            // Check for blade files (filename ends with .blade.php or in views directory)
            $isBlade = $type === 'blade' && (
                str_ends_with($filename, '.blade.php') ||
                str_contains($filePath, 'resources/views')
            );
            
            // Check for PHP files (but not blade files)
            $isPhp = $type === 'php' && $extension === 'php' && !str_contains($filePath, 'resources/views');
            
            // Check for CSS files
            $isCss = $type === 'css' && $extension === 'css';
            
            // Check for JS files
            $isJs = $type === 'js' && $extension === 'js';
            
            if ($isBlade || $isPhp || $isCss || $isJs) {
                $this->scanFile($filePath, $type);
                
                if ($isBlade) $this->scanStats['blade_files']++;
                if ($isPhp) $this->scanStats['php_files']++;
                if ($isCss) $this->scanStats['css_files']++;
                if ($isJs) $this->scanStats['js_files']++;
            }
        }
    }

    /**
     * Scan a single file for image references
     */
    protected function scanFile($filePath, $type)
    {
        try {
            $content = File::get($filePath);
            
            // Extract all image references from content
            $this->extractImageReferences($content, $filePath);
        } catch (\Exception $e) {
            // Skip files that can't be read
        }
    }

    /**
     * Extract image references from content
     */
    protected function extractImageReferences($content, $sourceFile)
    {
        // Pattern 1: asset('path/to/image.jpg')
        preg_match_all("/asset\(['\"]([^'\"]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))['\"]\)/i", $content, $matches);
        foreach ($matches[1] ?? [] as $path) {
            $this->markImageAsUsed($path);
        }
        
        // Pattern 2: url('path/to/image.jpg')
        preg_match_all("/url\(['\"]([^'\"]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))['\"]\)/i", $content, $matches);
        foreach ($matches[1] ?? [] as $path) {
            $this->markImageAsUsed($path);
        }
        
        // Pattern 3: src="path/to/image.jpg" or src='path/to/image.jpg'
        preg_match_all("/src\s*=\s*['\"]([^'\"]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))['\"]/i", $content, $matches);
        foreach ($matches[1] ?? [] as $path) {
            $this->markImageAsUsed($path);
        }
        
        // Pattern 4: background-image: url('path/to/image.jpg')
        preg_match_all("/background-image\s*:\s*url\(['\"]?([^'\")]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))['\"]?\)/i", $content, $matches);
        foreach ($matches[1] ?? [] as $path) {
            $this->markImageAsUsed($path);
        }
        
        // Pattern 5: Direct file paths (uploads/image.jpg, storage/image.jpg, etc.)
        preg_match_all("/(?:uploads|storage|assets|frontend_assets)[\/\\\\][^'\")>\s]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico)/i", $content, $matches);
        foreach ($matches[0] ?? [] as $path) {
            $this->markImageAsUsed($path);
        }
        
        // Pattern 6: Just filename references (image.jpg) - but only in specific contexts
        // Only match filenames that appear in quotes or after specific keywords
        preg_match_all("/(?:['\"]|src\s*=\s*['\"]|href\s*=\s*['\"]|url\(['\"]?)([a-zA-Z0-9_-]+\.(jpg|jpeg|png|gif|webp|svg|bmp|ico))['\"]?/i", $content, $matches);
        foreach ($matches[1] ?? [] as $filename) {
            // Only mark as used if it's a complete filename match (not partial)
            $this->markImageAsUsed($filename, true);
        }
    }

    /**
     * Mark an image as used
     */
    protected function markImageAsUsed($path, $filenameOnly = false)
    {
        if (empty($path)) {
            return;
        }
        
        $path = $this->normalizePath($path);
        $filename = basename($path);
        $basename = pathinfo($filename, PATHINFO_FILENAME);
        
        // Store full path (most reliable)
        if (!$filenameOnly) {
            $this->usedImages[$path] = true;
        }
        
        // Store filename (for direct filename references)
        if (!empty($filename) && strlen($filename) > 3) {
            $this->usedImages[$filename] = true;
        }
        
        // Store basename only if it's meaningful (not too short, not just numbers)
        // This helps with cases where extension might differ (jpg vs webp)
        if (!empty($basename) && strlen($basename) > 5 && !is_numeric($basename)) {
            // Only store basename if the path looks like an image path
            if (str_contains($path, 'uploads/') || str_contains($path, 'storage/') || str_contains($path, '.')) {
                $this->usedImages[$basename] = true;
            }
        }
    }

    /**
     * Scan database for image references
     */
    protected function scanDatabase()
    {
        $this->info('💾 Scanning database...');
        
        // Define all tables and columns that store image paths
        $imageFields = [
            'events' => ['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'],
            'blogs' => ['featured_image'],
            'stores' => ['store_logo', 'cover_image'],
            'categories' => ['media'],
            'pages' => ['media', 'banner_image'],
            'sliders' => ['background_image'],
            'coupons' => ['cover_logo'],
            'settings' => ['value'], // Settings table stores image paths in value column
        ];
        
        foreach ($imageFields as $table => $fields) {
            try {
                if ($table === 'settings') {
                    // Special handling for settings table
                    $settings = DB::table('settings')
                        ->whereIn('key', ['site_logo', 'site_favicon', 'home_banner'])
                        ->get();
                    
                    foreach ($settings as $setting) {
                        if ($setting->value) {
                            $this->markImageAsUsed($setting->value);
                            $this->scanStats['database_records']++;
                        }
                    }
                } else {
                    foreach ($fields as $field) {
                        $records = DB::table($table)
                            ->whereNotNull($field)
                            ->where($field, '!=', '')
                            ->pluck($field);
                        
                        foreach ($records as $path) {
                            // Only mark as used if path is not empty and looks like a valid image path
                            if (!empty($path) && (str_contains($path, '.') || str_contains($path, 'uploads/') || str_contains($path, 'storage/'))) {
                                $this->markImageAsUsed($path);
                                $this->scanStats['database_records']++;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                // Skip if table doesn't exist
            }
        }
    }

    /**
     * Find unused images
     */
    protected function findUnusedImages()
    {
        $this->info('🔎 Identifying unused images...');
        
        foreach ($this->allImages as $image) {
            $isUsed = false;
            
            // Normalize the image path for comparison
            $relativePath = $this->normalizePath($image['path']);
            $filename = $image['filename'];
            $basename = $image['basename'];
            
            // Check 1: Exact full path match (most reliable)
            if (isset($this->usedImages[$relativePath])) {
                $isUsed = true;
            }
            
            // Check 2: Exact filename match (reliable for direct references)
            if (!$isUsed && isset($this->usedImages[$filename])) {
                $isUsed = true;
            }
            
            // Check 3: Path ends with used reference (for relative paths)
            if (!$isUsed) {
                foreach (array_keys($this->usedImages) as $usedRef) {
                    // Only match if the used reference is a complete path or filename
                    // Don't do partial/contains matching which is too broad
                    if ($usedRef === $relativePath || 
                        $usedRef === $filename ||
                        $relativePath === $usedRef ||
                        basename($usedRef) === $filename) {
                        $isUsed = true;
                        break;
                    }
                    
                    // Also check if usedRef is a path and our image path ends with it
                    // (e.g., usedRef = "uploads/image.webp", image path = "uploads/image.webp")
                    if (str_ends_with($relativePath, $usedRef) && strlen($usedRef) > 10) {
                        $isUsed = true;
                        break;
                    }
                }
            }
            
            // Check 4: Basename match only for database records (where extension might differ)
            // This is less reliable, so we only use it as a last resort
            if (!$isUsed) {
                // Check if basename appears in database paths (more specific check)
                foreach (array_keys($this->usedImages) as $usedRef) {
                    $usedBasename = pathinfo($usedRef, PATHINFO_FILENAME);
                    $usedFilename = basename($usedRef);
                    
                    // Only match basename if the used reference looks like a database path
                    // (contains 'uploads/' or 'storage/')
                    if (str_contains($usedRef, 'uploads/') || str_contains($usedRef, 'storage/')) {
                        if ($basename === $usedBasename && strlen($basename) > 5) {
                            // Additional verification: check if extensions are similar image types
                            $imageExt = strtolower($image['extension']);
                            $usedExt = strtolower(pathinfo($usedRef, PATHINFO_EXTENSION));
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                            
                            if (in_array($imageExt, $imageExtensions) && in_array($usedExt, $imageExtensions)) {
                                $isUsed = true;
                                break;
                            }
                        }
                    }
                }
            }
            
            // Exclude system files (favicon, logo, etc.) - only if they're actually used
            if (!$isUsed) {
                $excludedPatterns = [
                    'favicon.ico',
                    'logo.png',
                    'logo.jpg',
                    'default-store.png',
                ];
                
                // Only exclude if the file actually matches AND is referenced
                foreach ($excludedPatterns as $pattern) {
                    if (strtolower($filename) === strtolower($pattern) && isset($this->usedImages[$pattern])) {
                        $isUsed = true;
                        break;
                    }
                }
            }
            
            if (!$isUsed) {
                $this->unusedImages[] = $image;
            }
        }
    }

    /**
     * Normalize file path for comparison
     */
    protected function normalizePath($path)
    {
        // Remove base path
        $path = str_replace(base_path(), '', $path);
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');
        
        // Handle public path
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }
        
        // Handle storage path
        if (str_starts_with($path, 'storage/app/public/')) {
            $path = 'storage/' . substr($path, 20);
        }
        
        return $path;
    }

    /**
     * Get unused images list
     */
    public function getUnusedImages()
    {
        return $this->unusedImages;
    }

    /**
     * Get scan statistics
     */
    public function getStats()
    {
        return $this->scanStats;
    }

    protected $outputCallback = null;

    /**
     * Set output callback for logging
     */
    public function setOutputCallback($callback)
    {
        $this->outputCallback = $callback;
    }

    /**
     * Helper method for output (can be overridden)
     */
    protected function info($message)
    {
        if ($this->outputCallback) {
            call_user_func($this->outputCallback, $message);
        }
    }
}


