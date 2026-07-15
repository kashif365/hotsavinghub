<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Events extends Model
{
    protected $fillable = [
        'event_name',
        'event_type',
        'date_available',
        'date_expiry',
        'seo_url',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'front_image',
        'button_icon',
        'cover_image',
        'no_coupon_cover',
        'event_short_content',
        'detail_description',
        'status',
        'show_footer',
        'views_count',
        'sort_order'
    ];
    
    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'event_id');
    }
    
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'event_store', 'event_id', 'store_id');
    }

    /**
     * Get the correct image path, checking multiple possible locations
     */
    protected function getImagePath($path, $fieldName = null)
    {
        if (empty($path)) {
            return null;
        }

        // Normalize path
        $originalPath = $path;
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        // Check in public/uploads (new location)
        if (File::exists(public_path($path))) {
            return $path;
        }

        // Check if path starts with storage/ and try public/storage (symlink)
        if (str_starts_with($path, 'storage/')) {
            $publicStoragePath = public_path($path);
            if (File::exists($publicStoragePath)) {
                return $path;
            }
        }

        // Check in storage/app/public/uploads (old location)
        $storagePath = storage_path('app/public/' . $path);
        if (File::exists($storagePath)) {
            // Try to move file to public/uploads if possible
            $newPath = 'uploads/' . basename($path);
            $newFullPath = public_path($newPath);
            try {
                if (!File::exists(public_path('uploads'))) {
                    File::makeDirectory(public_path('uploads'), 0755, true);
                }
                File::copy($storagePath, $newFullPath);
                // Update database with new path (use original attributes to avoid recursion)
                if ($fieldName) {
                    $this->attributes[$fieldName] = $newPath;
                    $this->saveQuietly(); // Save without triggering events/observers
                }
                return $newPath;
            } catch (\Exception $e) {
                // If copy fails, return storage path (will need symlink)
                return 'storage/' . $path;
            }
        }

        // Return original path if nothing found (might be external URL)
        return $originalPath;
    }

    /**
     * Accessor for front_image
     */
    public function getFrontImageAttribute($value)
    {
        return $this->getImagePath($value, 'front_image');
    }

    /**
     * Accessor for button_icon
     */
    public function getButtonIconAttribute($value)
    {
        return $this->getImagePath($value, 'button_icon');
    }

    /**
     * Accessor for cover_image
     */
    public function getCoverImageAttribute($value)
    {
        return $this->getImagePath($value, 'cover_image');
    }

    /**
     * Accessor for no_coupon_cover
     */
    public function getNoCouponCoverAttribute($value)
    {
        return $this->getImagePath($value, 'no_coupon_cover');
    }
}

