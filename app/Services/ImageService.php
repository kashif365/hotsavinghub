<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

class ImageService
{
    protected $manager;
    protected $quality = 100; // WebP quality (0-100) - Set to 100 for maximum quality
    protected $maxWidth = 10000; // Maximum width for optimization - Set very high to preserve original size

    public function __construct()
    {
        // Prefer Imagick when available (better format support, including SVG)
        $this->manager = new ImageManager(extension_loaded('imagick') ? new ImagickDriver() : new Driver());
    }

    /**
     * Extract dimensions from an SVG string.
     * Falls back to viewBox, otherwise returns nulls.
     *
     * @param string $svg
     * @return array{width: ?int, height: ?int}
     */
    protected function getSvgDimensions(string $svg): array
    {
        $width = null;
        $height = null;

        // width="123" / width="123px"
        if (preg_match('/\bwidth=["\']\s*([0-9.]+)\s*(px|pt|pc|cm|mm|in)?\s*["\']/i', $svg, $m)) {
            $width = (int) round((float) $m[1]);
        }
        if (preg_match('/\bheight=["\']\s*([0-9.]+)\s*(px|pt|pc|cm|mm|in)?\s*["\']/i', $svg, $m)) {
            $height = (int) round((float) $m[1]);
        }

        // Fallback to viewBox="min-x min-y width height"
        if ((!$width || !$height) && preg_match('/\bviewBox=["\']\s*[-0-9.]+\s+[-0-9.]+\s+([0-9.]+)\s+([0-9.]+)\s*["\']/i', $svg, $vb)) {
            $vbW = (int) round((float) $vb[1]);
            $vbH = (int) round((float) $vb[2]);
            $width = $width ?: ($vbW > 0 ? $vbW : null);
            $height = $height ?: ($vbH > 0 ? $vbH : null);
        }

        return ['width' => $width, 'height' => $height];
    }

    /**
     * Convert SVG content to WebP using Imagick (best effort).
     *
     * @param string $svgContent
     * @param int $quality
     * @param array{width: ?int, height: ?int} $dimensions
     * @param bool $preserveOriginal
     * @param int $maxWidth
     * @return string|null WebP binary string or null if conversion is not possible
     */
    protected function svgToWebpString(string $svgContent, int $quality, array $dimensions, bool $preserveOriginal, int $maxWidth): ?string
    {
        if (!extension_loaded('imagick')) {
            return null;
        }

        try {
            $imagick = new \Imagick();
            $imagick->setBackgroundColor(new \ImagickPixel('transparent'));

            // If SVG has explicit dimensions, set a render size to preserve "original" intent
            $w = $dimensions['width'] ?? null;
            $h = $dimensions['height'] ?? null;

            if ($w && $h) {
                // Respect maxWidth only when preserve_original is false
                if (!$preserveOriginal && $w > 0 && $w > $maxWidth) {
                    $ratio = $h / $w;
                    $w = $maxWidth;
                    $h = (int) max(1, round($w * $ratio));
                }
                // Note: setSize affects SVG rasterization in Imagick
                $imagick->setSize($w, $h);
            }

            $imagick->readImageBlob($svgContent);
            $imagick->setImageFormat('WEBP');
            $imagick->setImageCompressionQuality($quality);
            if ($quality >= 100) {
                $imagick->setOption('webp:lossless', 'true');
            }

            // Flatten layers (avoid issues with some SVGs)
            if ($imagick->getNumberImages() > 1) {
                $imagick = $imagick->mergeImageLayers(\Imagick::LAYERMETHOD_MERGE);
            }

            $blob = $imagick->getImagesBlob();
            $imagick->clear();
            $imagick->destroy();

            return $blob ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Upload and convert image to WebP format with optimization
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $options
     * @return string
     */
    public function uploadAndConvert(UploadedFile $file, string $directory = 'uploads', array $options = []): string
    {
        // Use public/uploads directory
        $publicPath = public_path($directory);
        
        // Ensure directory exists
        if (!File::exists($publicPath)) {
            File::makeDirectory($publicPath, 0755, true);
        }

        // Generate unique filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $baseName = time() . '_' . uniqid() . '_' . \Illuminate\Support\Str::slug($originalName);

        // Get options
        $maxWidth = $options['max_width'] ?? $this->maxWidth;
        $quality = $options['quality'] ?? $this->quality;
        $preserveOriginal = $options['preserve_original'] ?? true; // Default: preserve original size
        $convertSvg = $options['convert_svg'] ?? true; // Default: try to convert SVG to WebP

        $extension = strtolower($file->getClientOriginalExtension() ?: '');

        // SVG uploads need special handling (GD cannot decode SVG)
        if ($extension === 'svg' || $file->getClientMimeType() === 'image/svg+xml') {
            $svgContent = file_get_contents($file->getRealPath());
            if ($svgContent === false || trim($svgContent) === '') {
                throw new \RuntimeException('SVG file could not be read.');
            }

            $dimensions = $this->getSvgDimensions($svgContent);

            // Best effort: convert SVG → WebP if Imagick is available
            if ($convertSvg) {
                $webpString = $this->svgToWebpString($svgContent, $quality, $dimensions, $preserveOriginal, $maxWidth);
                if ($webpString) {
                    $fileName = $baseName . '.webp';
                    $fullPath = $publicPath . '/' . $fileName;
                    File::put($fullPath, $webpString);
                    return $directory . '/' . $fileName;
                }
            }

            // Fallback: store SVG as-is (keeps perfect sharpness, avoids 500 error)
            $fileName = $baseName . '.svg';
            $fullPath = $publicPath . '/' . $fileName;
            File::put($fullPath, $svgContent);
            return $directory . '/' . $fileName;
        }

        // Create image from uploaded file (raster formats)
        $image = $this->manager->read($file->getRealPath());

        // Resize only if explicitly requested and preserve_original is false
        if (!$preserveOriginal && $image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Optimize and encode as WebP
        $optimizedImage = $image->toWebp($quality);
        
        // Convert to string for storage
        $imageString = (string) $optimizedImage;

        // Save to public/uploads directory
        $fileName = $baseName . '.webp';
        $fullPath = $publicPath . '/' . $fileName;
        File::put($fullPath, $imageString);

        // Return relative path from public folder
        return $directory . '/' . $fileName;
    }

    /**
     * Convert existing image to WebP
     *
     * @param string $imagePath
     * @param array $options
     * @return string|null
     */
    public function convertToWebP(string $imagePath, array $options = []): ?string
    {
        $fullPath = public_path($imagePath);
        
        if (!file_exists($fullPath)) {
            return null;
        }

        // Check if already WebP
        if (pathinfo($fullPath, PATHINFO_EXTENSION) === 'webp') {
            return $imagePath;
        }

        // Handle SVG separately
        $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        if ($ext === 'svg') {
            $quality = $options['quality'] ?? $this->quality;
            $maxWidth = $options['max_width'] ?? $this->maxWidth;
            $preserveOriginal = $options['preserve_original'] ?? true;
            $convertSvg = $options['convert_svg'] ?? true;

            $svgContent = file_get_contents($fullPath);
            if ($svgContent === false || trim($svgContent) === '') {
                return $imagePath;
            }

            if ($convertSvg) {
                $dimensions = $this->getSvgDimensions($svgContent);
                $webpString = $this->svgToWebpString($svgContent, $quality, $dimensions, $preserveOriginal, $maxWidth);
                if ($webpString) {
                    $newPath = preg_replace('/\.svg$/i', '.webp', $imagePath) ?? ($imagePath . '.webp');
                    $newFullPath = public_path($newPath);
                    File::put($newFullPath, $webpString);
                    File::delete($fullPath);
                    return $newPath;
                }
            }

            // If conversion isn't possible, keep SVG
            return $imagePath;
        }

        // Create image
        $image = $this->manager->read($fullPath);

        // Get options
        $maxWidth = $options['max_width'] ?? $this->maxWidth;
        $quality = $options['quality'] ?? $this->quality;
        $preserveOriginal = $options['preserve_original'] ?? true; // Default: preserve original size

        // Resize only if explicitly requested and preserve_original is false
        if (!$preserveOriginal && $image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Generate new filename
        $newPath = str_replace('.' . pathinfo($imagePath, PATHINFO_EXTENSION), '.webp', $imagePath);
        $newFullPath = public_path($newPath);

        // Convert and save
        $optimizedImage = $image->toWebP($quality);
        $imageString = (string) $optimizedImage;
        File::put($newFullPath, $imageString);

        // Delete old image
        File::delete($fullPath);

        return $newPath;
    }

    /**
     * Optimize existing WebP image
     *
     * @param string $imagePath
     * @param int $quality
     * @return bool
     */
    public function optimize(string $imagePath, int $quality = 100): bool
    {
        $fullPath = public_path($imagePath);
        
        if (!file_exists($fullPath)) {
            return false;
        }

        // Skip SVG optimization
        if (strtolower(pathinfo($imagePath, PATHINFO_EXTENSION)) === 'svg') {
            return true;
        }

        $image = $this->manager->read($fullPath);
        
        // Don't resize - preserve original size
        // Resize only if explicitly needed (removed automatic resize)

        // Re-encode with new quality
        $optimizedImage = $image->toWebp($quality);
        $imageString = (string) $optimizedImage;
        File::put($fullPath, $imageString);

        return true;
    }

    /**
     * Get image info
     *
     * @param string $imagePath
     * @return array|null
     */
    public function getImageInfo(string $imagePath): ?array
    {
        $fullPath = public_path($imagePath);
        
        if (!file_exists($fullPath)) {
            return null;
        }

        $extension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
        $size = filesize($fullPath);
        
        // Handle SVG files separately (can't use Intervention Image)
        if ($extension === 'svg') {
            // Try to get dimensions from SVG
            $svgContent = file_get_contents($fullPath);
            $width = 0;
            $height = 0;
            
            if (preg_match('/width=["\']?(\d+)/i', $svgContent, $widthMatch)) {
                $width = (int)$widthMatch[1];
            }
            if (preg_match('/height=["\']?(\d+)/i', $svgContent, $heightMatch)) {
                $height = (int)$heightMatch[1];
            }
            
            // If no dimensions found, use default
            if ($width === 0 || $height === 0) {
                $width = 100;
                $height = 100;
            }
            
            return [
                'width' => $width,
                'height' => $height,
                'size' => $size,
                'format' => 'svg',
                'path' => $imagePath,
                'url' => asset($imagePath),
            ];
        }

        // Handle other image formats with Intervention Image
        try {
            $image = $this->manager->read($fullPath);
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => $size,
                'format' => $extension,
                'path' => $imagePath,
                'url' => asset($imagePath),
            ];
        } catch (\Exception $e) {
            // If image can't be read, return basic info
            return [
                'width' => 0,
                'height' => 0,
                'size' => $size,
                'format' => $extension,
                'path' => $imagePath,
                'url' => asset($imagePath),
            ];
        }
    }

    /**
     * Delete image
     *
     * @param string $imagePath
     * @return bool
     */
    public function delete(string $imagePath): bool
    {
        try {
            // Normalize path (remove leading slashes and normalize separators)
            $imagePath = str_replace('\\', '/', $imagePath);
            $imagePath = ltrim($imagePath, '/');
            
            // Ensure path is within public/uploads directory
            if (!str_starts_with($imagePath, 'uploads/')) {
                $imagePath = 'uploads/' . basename($imagePath);
            }
            
            $fullPath = public_path($imagePath);
            
            if (file_exists($fullPath) && is_file($fullPath)) {
                return File::delete($fullPath);
            }
            
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }
}

