<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Services\ImageService;
use Illuminate\Support\Str;

class MediaLibraryController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display media library
     */
    public function index(Request $request)
    {
        $directory = $request->get('directory', 'uploads');
        $search = $request->get('search', '');
        
        // Get all image files from public/uploads folder
        $uploadsPath = public_path($directory);
        
        if (!File::exists($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
        }
        
        // Get all files recursively from uploads directory
        $files = File::allFiles($uploadsPath);
        
        // Convert to relative paths from public folder
        $imageFiles = [];
        foreach ($files as $file) {
            $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath); // Normalize path separators
            
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $imageFiles[] = $relativePath;
            }
        }

        // Apply search filter
        if ($search) {
            $imageFiles = array_filter($imageFiles, function($file) use ($search) {
                return stripos(basename($file), $search) !== false;
            });
        }

        // Remove duplicates
        $imageFiles = array_unique($imageFiles);

        // Sort by modification time (newest first)
        usort($imageFiles, function($a, $b) {
            try {
                $timeA = filemtime(public_path($a));
                $timeB = filemtime(public_path($b));
                return $timeB - $timeA;
            } catch (\Exception $e) {
                return 0;
            }
        });

        // Get image info for each file
        $images = [];
        foreach ($imageFiles as $file) {
            try {
                $info = $this->imageService->getImageInfo($file);
                if ($info) {
                    $images[] = $info;
                }
            } catch (\Exception $e) {
                // Skip files that can't be read
                continue;
            }
        }

        // No pagination needed - DataTables will handle it
        $total = count($images);

        return view('admin.media.index', compact('images', 'total', 'directory', 'search'));
    }

    /**
     * Upload new image (from file or URL)
     */
    public function store(Request $request)
    {
        // Handle URL upload
        if ($request->has('image_url') && $request->filled('image_url')) {
            return $this->uploadFromUrl($request);
        }

        // Handle file upload
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB max
            'directory' => 'nullable|string',
        ]);

        $directory = $request->input('directory', 'uploads');
        
        try {
            $path = $this->imageService->uploadAndConvert(
                $request->file('image'),
                $directory,
                ['quality' => 100, 'preserve_original' => true]
            );

            // If AJAX request, return JSON
            if ($request->ajax() || $request->wantsJson()) {
                $imageInfo = $this->imageService->getImageInfo($path);
                return response()->json([
                    'success' => true,
                    'message' => 'Image uploaded and converted to WebP successfully!',
                    'path' => $path,
                    'url' => asset($path),
                    'image' => $imageInfo
                ]);
            }

            return redirect()->route('admin.media.index')
                ->with('success', 'Image uploaded and converted to WebP successfully!');
        } catch (\Exception $e) {
            // If AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to upload image: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.media.index')
                ->with('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }

    /**
     * Upload image from URL
     */
    protected function uploadFromUrl(Request $request)
    {
        $request->validate([
            'image_url' => 'required|url|max:2048',
        ]);

        $imageUrl = $request->input('image_url');
        $directory = $request->input('directory', 'uploads');

        try {
            // Download image from URL
            $imageData = @file_get_contents($imageUrl);
            
            if ($imageData === false) {
                throw new \Exception('Failed to download image from URL. Please check if the URL is accessible.');
            }

            // Validate it's an image
            $imageInfo = @getimagesizefromstring($imageData);
            if ($imageInfo === false) {
                throw new \Exception('The URL does not point to a valid image file.');
            }

            // Get image MIME type
            $mimeType = $imageInfo['mime'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new \Exception('Unsupported image type. Allowed: JPG, PNG, GIF, WebP, SVG');
            }

            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'img_');
            file_put_contents($tempFile, $imageData);

            // Create UploadedFile instance
            $uploadedFile = new \Illuminate\Http\UploadedFile(
                $tempFile,
                basename(parse_url($imageUrl, PHP_URL_PATH)),
                $mimeType,
                null,
                true
            );

            // Use ImageService to convert and optimize
            $path = $this->imageService->uploadAndConvert(
                $uploadedFile,
                $directory,
                ['quality' => 100, 'preserve_original' => true]
            );

            // Clean up temp file
            @unlink($tempFile);

            // Return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                $imageInfo = $this->imageService->getImageInfo($path);
                return response()->json([
                    'success' => true,
                    'message' => 'Image downloaded and converted to WebP successfully!',
                    'path' => $path,
                    'url' => asset($path),
                    'image' => $imageInfo
                ]);
            }

            return redirect()->route('admin.media.index')
                ->with('success', 'Image downloaded and converted to WebP successfully!');
        } catch (\Exception $e) {
            // If AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to download image: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.media.index')
                ->with('error', 'Failed to download image: ' . $e->getMessage());
        }
    }

    /**
     * Get media library images as JSON (for modal/popup)
     */
    public function getImages(Request $request)
    {
        $directory = $request->get('directory', 'uploads');
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 24);
        
        // Get all image files from public/uploads folder
        $uploadsPath = public_path($directory);
        
        if (!File::exists($uploadsPath)) {
            File::makeDirectory($uploadsPath, 0755, true);
        }
        
        // Get all files recursively from uploads directory
        $files = File::allFiles($uploadsPath);
        
        // Convert to relative paths from public folder
        $imageFiles = [];
        foreach ($files as $file) {
            $relativePath = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath); // Normalize path separators
            
            $extension = strtolower($file->getExtension());
            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $imageFiles[] = $relativePath;
            }
        }

        // Apply search filter
        if ($search) {
            $imageFiles = array_filter($imageFiles, function($file) use ($search) {
                return stripos(basename($file), $search) !== false;
            });
        }

        // Remove duplicates
        $imageFiles = array_unique($imageFiles);

        // Sort by modification time (newest first)
        usort($imageFiles, function($a, $b) {
            try {
                $timeA = filemtime(public_path($a));
                $timeB = filemtime(public_path($b));
                return $timeB - $timeA;
            } catch (\Exception $e) {
                return 0;
            }
        });

        // Get image info for each file
        $images = [];
        foreach ($imageFiles as $file) {
            try {
                $info = $this->imageService->getImageInfo($file);
                if ($info) {
                    $images[] = $info;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Paginate
        $total = count($images);
        $lastPage = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $paginatedImages = array_slice($images, $offset, $perPage);
        
        return response()->json([
            'success' => true,
            'images' => $paginatedImages,
            'pagination' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total
            ]
        ]);
    }

    /**
     * Delete image
     */
    public function destroy(Request $request)
    {
        $imagePath = $request->input('path');
        $force = $request->input('force', false); // Allow force delete
        
        if (!$imagePath) {
            return response()->json(['success' => false, 'message' => 'Image path is required'], 400);
        }

        try {
            // Ensure path is relative to public folder
            $imagePath = str_replace('\\', '/', $imagePath);
            $imagePath = ltrim($imagePath, '/');
            
            // Check if image exists
            $fullPath = public_path($imagePath);
            if (!file_exists($fullPath)) {
                return response()->json(['success' => false, 'message' => 'Image file not found'], 404);
            }

            // Check if image is being used in database (unless force delete)
            if (!$force) {
                $usageInfo = $this->checkImageUsage($imagePath);
                
                if ($usageInfo['is_used']) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'This image is being used and cannot be deleted. Use force delete to override.',
                        'usage_info' => $usageInfo['details'],
                        'can_force_delete' => true
                    ], 400);
                }
            }

            // Delete the image
            $deleted = $this->imageService->delete($imagePath);
            
            if ($deleted) {
                return response()->json([
                    'success' => true, 
                    'message' => $force ? 'Image force deleted successfully' : 'Image deleted successfully'
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to delete image'], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete images
     */
    public function bulkDelete(Request $request)
    {
        $paths = $request->input('paths', []);
        $force = $request->input('force', false);
        
        if (empty($paths) || !is_array($paths)) {
            return response()->json(['success' => false, 'message' => 'No images selected'], 400);
        }

        $deletedCount = 0;
        $failedCount = 0;
        $usedImages = [];
        $errors = [];

        foreach ($paths as $imagePath) {
            try {
                // Normalize path
                $imagePath = str_replace('\\', '/', $imagePath);
                $imagePath = ltrim($imagePath, '/');
                
                // Check if image exists
                $fullPath = public_path($imagePath);
                if (!file_exists($fullPath)) {
                    $failedCount++;
                    $errors[] = basename($imagePath) . ' - File not found';
                    continue;
                }

                // Check if image is being used in database (unless force delete)
                if (!$force) {
                    $usageInfo = $this->checkImageUsage($imagePath);
                    
                    if ($usageInfo['is_used']) {
                        $usedImages[] = [
                            'path' => basename($imagePath),
                            'details' => $usageInfo['details']
                        ];
                        $failedCount++;
                        continue;
                    }
                }

                // Delete the image
                $deleted = $this->imageService->delete($imagePath);
                
                if ($deleted) {
                    $deletedCount++;
                } else {
                    $failedCount++;
                    $errors[] = basename($imagePath) . ' - Failed to delete';
                }
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = basename($imagePath) . ' - ' . $e->getMessage();
            }
        }

        $message = "Deleted {$deletedCount} image(s)";
        if ($failedCount > 0) {
            if (count($usedImages) > 0) {
                $message .= ". {$failedCount} image(s) could not be deleted";
                if (count($usedImages) > 0) {
                    $message .= " (" . count($usedImages) . " are being used)";
                }
            } else {
                $message .= ". {$failedCount} image(s) failed to delete";
            }
        }

        return response()->json([
            'success' => $deletedCount > 0,
            'message' => $message,
            'deleted_count' => $deletedCount,
            'failed_count' => $failedCount,
            'used_images' => $usedImages,
            'errors' => $errors
        ]);
    }

    /**
     * Convert image to WebP
     */
    public function convertToWebP(Request $request)
    {
        $imagePath = $request->input('path');
        
        if (!$imagePath) {
            return response()->json(['success' => false, 'message' => 'Image path is required'], 400);
        }

        try {
            $newPath = $this->imageService->convertToWebP($imagePath);
            
            if ($newPath) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Image converted to WebP successfully',
                    'new_path' => $newPath
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to convert image'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Optimize image
     */
    public function optimize(Request $request)
    {
        $imagePath = $request->input('path');
        $quality = $request->input('quality', 100); // Default to 100 for maximum quality
        
        if (!$imagePath) {
            return response()->json(['success' => false, 'message' => 'Image path is required'], 400);
        }

        try {
            $optimized = $this->imageService->optimize($imagePath, $quality);
            
            if ($optimized) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Image optimized successfully'
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to optimize image'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Check if image is being used in database
     * Returns array with usage information
     */
    protected function checkImageUsage(string $imagePath): array
    {
        // Normalize path (remove 'storage/' prefix if present)
        $cleanPath = str_replace('storage/', '', $imagePath);
        $normalizedPath = str_replace('\\', '/', $cleanPath);
        $fileName = basename($normalizedPath);
        
        $usageDetails = [];
        $isUsed = false;
        
        // Check in various models
        $models = [
            \App\Models\Blog::class => ['featured_image', 'Blog'],
            \App\Models\Store::class => [['store_logo', 'cover_image'], 'Store'],
            \App\Models\Slider::class => ['background_image', 'Slider'],
            \App\Models\Page::class => [['media', 'banner_image'], 'Page'],
            \App\Models\Category::class => ['media', 'Category'],
            \App\Models\Events::class => [['front_image', 'button_icon', 'cover_image', 'no_coupon_cover'], 'Event'],
            \App\Models\Coupon::class => ['cover_logo', 'Coupon'],
        ];

        foreach ($models as $modelClass => $config) {
            $fields = is_array($config[0]) ? $config[0] : [$config[0]];
            $modelName = $config[1] ?? class_basename($modelClass);
            
            foreach ($fields as $field) {
                try {
                    // First, try exact matches
                    $exactMatches = $modelClass::where($field, $normalizedPath)
                        ->orWhere($field, $imagePath)
                        ->orWhere($field, 'uploads/' . $fileName)
                        ->get();
                    
                    if ($exactMatches->count() > 0) {
                        $isUsed = true;
                        foreach ($exactMatches as $match) {
                            $usageDetails[] = [
                                'model' => $modelName,
                                'field' => $field,
                                'id' => $match->id ?? null,
                                'title' => $match->title ?? $match->name ?? 'N/A'
                            ];
                        }
                        continue;
                    }
                    
                    // Then check for partial matches (but verify it's the same file)
                    $partialMatches = $modelClass::where(function($query) use ($field, $normalizedPath, $imagePath, $fileName) {
                        $query->where($field, 'like', '%' . $normalizedPath . '%')
                              ->orWhere($field, 'like', '%' . $imagePath . '%')
                              ->orWhere($field, 'like', '%' . $fileName . '%');
                    })->get();
                    
                    if ($partialMatches->count() > 0) {
                        foreach ($partialMatches as $match) {
                            $fieldValue = $match->{$field};
                            if (!$fieldValue) continue;
                            
                            // Verify it's actually the same image file
                            // Check if the path ends with the filename or contains the full normalized path
                            $normalizedFieldValue = str_replace('\\', '/', $fieldValue);
                            $normalizedFieldValue = ltrim($normalizedFieldValue, '/');
                            
                            // More strict matching - check if paths match or filename matches at the end
                            if ($normalizedFieldValue === $normalizedPath || 
                                $normalizedFieldValue === $imagePath ||
                                str_ends_with($normalizedFieldValue, '/' . $fileName) ||
                                str_ends_with($normalizedFieldValue, $fileName) ||
                                basename($normalizedFieldValue) === $fileName) {
                                $isUsed = true;
                                $usageDetails[] = [
                                    'model' => $modelName,
                                    'field' => $field,
                                    'id' => $match->id ?? null,
                                    'title' => $match->title ?? $match->name ?? 'N/A'
                                ];
                            }
                        }
                    }
                } catch (\Exception $e) {
                    // Skip if model or field doesn't exist
                    continue;
                }
            }
        }

        // Check in settings
        $settingsFields = ['site_logo', 'site_favicon', 'home_banner'];
        foreach ($settingsFields as $field) {
            try {
                $value = \App\Models\Setting::get($field, '');
                if ($value && (
                    str_contains($value, $normalizedPath) || 
                    str_contains($value, $imagePath) || 
                    str_contains($value, $fileName)
                )) {
                    $isUsed = true;
                    $usageDetails[] = [
                        'model' => 'Setting',
                        'field' => $field,
                        'id' => null,
                        'title' => 'Site Setting'
                    ];
                }
            } catch (\Exception $e) {
                // Skip if setting doesn't exist
                continue;
            }
        }

        return [
            'is_used' => $isUsed,
            'details' => $usageDetails
        ];
    }
}

