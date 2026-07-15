<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Generate picture element with next-gen formats (WebP/AVIF) and fallback
     * 
     * @param string $imagePath
     * @param string $alt
     * @param array $attributes
     * @return string
     */
    public static function picture($imagePath, $alt = '', $attributes = [])
    {
        $defaultAttributes = [
            'loading' => 'lazy',
            'decoding' => 'async',
            'width' => $attributes['width'] ?? null,
            'height' => $attributes['height'] ?? null,
            'class' => $attributes['class'] ?? '',
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $imageUrl = asset($imagePath);
        $imagePathInfo = pathinfo($imagePath);
        $basePath = $imagePathInfo['dirname'] . '/' . $imagePathInfo['filename'];
        $extension = strtolower($imagePathInfo['extension'] ?? 'jpg');
        
        // Generate WebP path
        $webpPath = $basePath . '.webp';
        $webpUrl = asset($webpPath);
        $webpExists = file_exists(public_path($webpPath));
        
        // Generate AVIF path (if supported)
        $avifPath = $basePath . '.avif';
        $avifUrl = asset($avifPath);
        $avifExists = file_exists(public_path($avifPath));
        
        // Build picture element
        $html = '<picture>';
        
        // AVIF source (best compression)
        if ($avifExists) {
            $html .= '<source srcset="' . $avifUrl . '" type="image/avif">';
        }
        
        // WebP source
        if ($webpExists) {
            $html .= '<source srcset="' . $webpUrl . '" type="image/webp">';
        }
        
        // Fallback image
        $imgAttributes = '';
        foreach ($attributes as $key => $value) {
            if ($value !== null && $value !== '') {
                $imgAttributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        $html .= '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($alt) . '"' . $imgAttributes . '>';
        $html .= '</picture>';
        
        return $html;
    }
    
    /**
     * Simple image tag with WebP support if available
     * 
     * @param string $imagePath
     * @param string $alt
     * @param array $attributes
     * @return string
     */
    public static function img($imagePath, $alt = '', $attributes = [])
    {
        // For now, return simple img tag with proper attributes
        // WebP conversion should be done at upload time via ImageService
        $defaultAttributes = [
            'loading' => 'lazy',
            'decoding' => 'async',
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $imgAttributes = '';
        foreach ($attributes as $key => $value) {
            if ($value !== null && $value !== '') {
                $imgAttributes .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        
        return '<img src="' . asset($imagePath) . '" alt="' . htmlspecialchars($alt) . '"' . $imgAttributes . '>';
    }
}

