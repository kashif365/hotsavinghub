<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'covid_disable',
        'sort_order',
        'featured',
        'recommended',
        'auto_sort',
        'show_trending',
        'student_discount',
        'views_count',
        'status',
        'store_name',
        'affiliate_url',
        'store_logo',
    'facebook_url',
    'twitter_url',
    'instagram_url',
    'youtube_url',
        'current_network',
        'available_network',
        'content',
        'detail_description',
        'title_heading',
        'seo_url',
        'meta_title',
    'meta_description',
    'meta_keywords',
    'canonical_url',
    'schema',
        'cover_image',
    'faqs',
    ];

    // Relations
        public function categories()
        {
            return $this->belongsToMany(Category::class, 'category_store', 'store_id', 'category_id')
                        ->withTimestamps(); // ✅ ye add karo
        }

        public function events()
        {
            return $this->belongsToMany(Events::class, 'event_store', 'store_id', 'event_id')
                        ->withTimestamps(); // ✅ ye add karo
        }


// ✅ Relation with current_network (single)
public function currentNetwork()
{
    return $this->belongsTo(Networks::class, 'current_network'); // column ka naam wahi jo DB me hai
}

public function availableNetwork()
{
    return $this->belongsTo(Networks::class, 'available_network'); // column ka naam wahi jo DB me hai
}

public function coupons()
{
    return $this->hasMany(Coupon::class, 'brand_store', 'store_name');
}

}
