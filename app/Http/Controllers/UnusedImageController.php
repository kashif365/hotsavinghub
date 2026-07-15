<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UnusedImageDetector;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UnusedImageController extends Controller
{
    protected $detector;

    public function __construct(UnusedImageDetector $detector)
    {
        $this->detector = $detector;
    }

    /**
     * Display unused images list
     */
    public function index(Request $request)
    {
        // Get scan results
        $directories = ['public', 'storage/app/public'];
        
        // Reset detector state (create new instance to avoid caching)
        $this->detector = app(UnusedImageDetector::class);
        
        $results = $this->detector->scan($directories);
        
        $allImages = $results['all_images'];
        $unusedImages = $results['unused_images'];
        $usedImages = $results['used_images'];
        $stats = $results['stats'];
        
        // No pagination needed - DataTables will handle it
        $totalUnused = count($unusedImages);
        
        // Calculate total size
        $totalSize = collect($unusedImages)->sum('size');
        
        return view('admin.unused-images.index', compact(
            'unusedImages',
            'totalUnused',
            'stats',
            'totalSize',
            'allImages'
        ));
    }

    /**
     * Delete unused images
     */
    public function destroy(Request $request)
    {
        // Handle JSON string from form
        $imagesJson = $request->input('images');
        $images = [];
        
        if (is_string($imagesJson)) {
            $images = json_decode($imagesJson, true) ?? [];
        } elseif (is_array($imagesJson)) {
            $images = $imagesJson;
        }
        
        if (empty($images) || !is_array($images)) {
            return redirect()->route('admin.unused-images.index')
                ->with('error', 'No images selected for deletion.');
        }

        $deleted = 0;
        $errors = 0;
        $deletedFiles = [];
        $errorMessages = [];

        foreach ($images as $imagePath) {
            try {
                if (empty($imagePath)) {
                    continue;
                }
                
                // Normalize path
                $fullPath = $this->getFullPath($imagePath);
                
                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                    $deletedFiles[] = $imagePath;
                    $deleted++;
                } else {
                    $errors++;
                    $errorMessages[] = "File not found: {$imagePath}";
                }
            } catch (\Exception $e) {
                $errors++;
                $errorMessages[] = "Error deleting {$imagePath}: " . $e->getMessage();
            }
        }

        if ($deleted > 0) {
            $message = "Successfully deleted {$deleted} unused image(s).";
            if ($errors > 0) {
                $message .= " {$errors} error(s) occurred.";
            }
            return redirect()->route('admin.unused-images.index')
                ->with('success', $message);
        }

        return redirect()->route('admin.unused-images.index')
            ->with('error', "Failed to delete images. Errors: {$errors}");
    }

    /**
     * Delete single image
     */
    public function deleteSingle(Request $request)
    {
        $request->validate([
            'image_path' => 'required|string',
        ]);

        try {
            $imagePath = $request->input('image_path');
            $fullPath = $this->getFullPath($imagePath);
            
            if (File::exists($fullPath)) {
                File::delete($fullPath);
                return redirect()->route('admin.unused-images.index')
                    ->with('success', 'Image deleted successfully.');
            }
            
            return redirect()->route('admin.unused-images.index')
                ->with('error', 'Image not found: ' . $imagePath);
        } catch (\Exception $e) {
            return redirect()->route('admin.unused-images.index')
                ->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    /**
     * Refresh scan (rescan images)
     */
    public function refresh()
    {
        // Just redirect to index - it will rescan automatically
        return redirect()->route('admin.unused-images.index')
            ->with('info', 'Scan refreshed. Showing latest unused images.');
    }

    /**
     * Get full path from relative path
     */
    protected function getFullPath($relativePath)
    {
        // Handle public path
        if (str_starts_with($relativePath, 'uploads/') || 
            str_starts_with($relativePath, 'assets/') ||
            str_starts_with($relativePath, 'frontend_assets/')) {
            return public_path($relativePath);
        }
        
        // Handle storage path
        if (str_starts_with($relativePath, 'storage/')) {
            $storagePath = str_replace('storage/', '', $relativePath);
            return storage_path('app/public/' . $storagePath);
        }
        
        // Default to public
        return public_path($relativePath);
    }
}

