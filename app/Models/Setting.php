<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
        'sort_order'
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    // Scope for grouping settings
    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group)->orderBy('sort_order');
    }

    // Static method to get setting value
    public static function get($key, $default = null)
    {
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    // Static method to set setting value
    public static function set($key, $value)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("setting.{$key}");
        return $setting;
    }

    // Get all settings as array
    public static function getAll()
    {
        return Cache::remember('settings.all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    // Clear all settings cache
    public static function clearCache()
    {
        Cache::forget('settings.all');
        static::pluck('key')->each(function ($key) {
            Cache::forget("setting.{$key}");
        });
    }

    // Override save to clear cache
    public function save(array $options = [])
    {
        $result = parent::save($options);
        static::clearCache();
        return $result;
    }

    // Override delete to clear cache
    public function delete()
    {
        $result = parent::delete();
        static::clearCache();
        return $result;
    }
}
