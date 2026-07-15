<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Resize and serve image dynamically
     * Route: /image/resize?path=uploads/image.webp&w=800
     */
    public function resize(Request $request)
    {
        $path = $request->get('path');
        $width = (int) $request->get('w', 1920);
        $quality = (int) $request->get('q', 95); // Default to 95 for better quality

        if (!$path) {
            abort(404);
        }

        // Security: Ensure path is within uploads directory
        $path = ltrim($path, '/');
        if (!str_starts_with($path, 'uploads/')) {
            abort(404);
        }

        $fullPath = public_path($path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        // Generate cache key based on path, width, and file modification time
        $fileMtime = filemtime($fullPath);
        $cacheKey = md5($path . $width . $quality . $fileMtime);
        $cacheDir = storage_path('app/public/image-cache');
        $cachePath = $cacheDir . '/' . $cacheKey . '.webp';

        // Return cached version if exists - Fast path for cached images
        if (file_exists($cachePath)) {
            // Generate ETag for better browser caching
            $etag = md5_file($cachePath);
            $ifNoneMatch = $request->header('If-None-Match');
            
            // Return 304 Not Modified if ETag matches
            if ($ifNoneMatch && $ifNoneMatch === $etag) {
                return response('', 304, [
                    'Cache-Control' => 'public, max-age=31536000, immutable',
                    'ETag' => $etag,
                ]);
            }
            
            $response = response()->file($cachePath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
            // Add performance headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Vary', 'Accept');
            $response->headers->set('ETag', $etag);
            $response->headers->set('Content-Length', filesize($cachePath));
            return $response;
        }

        try {
            // Ensure cache directory exists
            if (!File::exists($cacheDir)) {
                File::makeDirectory($cacheDir, 0755, true);
            }

            // Read and resize image using ImageService
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->read($fullPath);
            
            // Get original dimensions
            $originalWidth = $image->width();
            $originalHeight = $image->height();

            // Always resize to requested width (even if same size, re-encode with new quality)
            if ($width <= $originalWidth) {
                // Calculate height maintaining aspect ratio
                $height = (int) (($width / $originalWidth) * $originalHeight);
                $image->scale(width: $width, height: $height);
            } else {
                // If requested width is larger, use original dimensions but still optimize quality
                $width = $originalWidth;
                $height = $originalHeight;
            }

            // Use high quality for all sizes - preserve image clarity
            // Quality is already set from request parameter (default 85, can be overridden)
            // Minimum quality set to 90 to prevent blur
            if ($quality < 90) {
                $quality = 90;
            }

            // Convert to WebP and save to cache
            $optimizedImage = $image->toWebp($quality);
            $imageString = (string) $optimizedImage;
            File::put($cachePath, $imageString);

            // Generate ETag for better browser caching
            $etag = md5($imageString);
            $ifNoneMatch = $request->header('If-None-Match');
            
            // Return 304 Not Modified if ETag matches (shouldn't happen on first request, but good practice)
            if ($ifNoneMatch && $ifNoneMatch === $etag) {
                return response('', 304, [
                    'Cache-Control' => 'public, max-age=31536000, immutable',
                    'ETag' => $etag,
                ]);
            }
            
            $response = response($imageString, 200, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
            // Add performance headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('Accept-Ranges', 'bytes');
            $response->headers->set('Vary', 'Accept');
            $response->headers->set('ETag', $etag);
            $response->headers->set('Content-Length', strlen($imageString));
            return $response;
        } catch (\Exception $e) {
            // Fallback: return original image
            return response()->file($fullPath, [
                'Content-Type' => 'image/webp',
                'Cache-Control' => 'public, max-age=31536000, immutable',
            ]);
        }
    }
}

