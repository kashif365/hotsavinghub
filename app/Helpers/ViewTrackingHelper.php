<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class ViewTrackingHelper
{
    /**
     * Track a view for a specific item (coupon, store, category, blog, event)
     * Only increments if not already viewed in this session
     * 
     * @param string $type Type of item: 'coupon', 'store', 'category', 'blog', 'event'
     * @param int $id ID of the item
     * @param object $model The model instance to increment
     * @return bool Returns true if view was counted, false if already viewed
     */
    public static function trackView($type, $id, $model)
    {
        // Generate session key for this view
        $sessionKey = "viewed_{$type}_{$id}";
        
        // Check if already viewed in this session
        if (Session::has($sessionKey)) {
            return false; // Already viewed in this session
        }
        
        // Mark as viewed in this session (expires when session ends)
        Session::put($sessionKey, true);
        
        // Increment view count in database
        $model->increment('views_count');
        
        return true; // View was counted
    }
    
    /**
     * Check if an item has been viewed in this session
     * 
     * @param string $type Type of item
     * @param int $id ID of the item
     * @return bool
     */
    public static function hasViewed($type, $id)
    {
        $sessionKey = "viewed_{$type}_{$id}";
        return Session::has($sessionKey);
    }
    
    /**
     * Get view count for multiple items
     * 
     * @param string $type Type of item
     * @param array $ids Array of item IDs
     * @return array Associative array with id => views_count
     */
    public static function getViewCounts($type, $ids)
    {
        $modelClass = self::getModelClass($type);
        if (!$modelClass) {
            return [];
        }
        
        $items = $modelClass::whereIn('id', $ids)->pluck('views_count', 'id');
        return $items->toArray();
    }
    
    /**
     * Get model class name based on type
     * 
     * @param string $type
     * @return string|null
     */
    private static function getModelClass($type)
    {
        $models = [
            'coupon' => \App\Models\Coupon::class,
            'store' => \App\Models\Store::class,
            'category' => \App\Models\Category::class,
            'blog' => \App\Models\Blog::class,
            'event' => \App\Models\Events::class,
        ];
        
        return $models[$type] ?? null;
    }
}

