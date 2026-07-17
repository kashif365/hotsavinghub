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

    /**
     * store_name is stored SEO-stuffed (e.g. "Barnes & Nobles Coupon & Discount Codes")
     * for use in page titles/meta tags. This strips the known trailing suffix for
     * contexts that just want the plain brand name (cards, badges, etc.).
     */
    public function getShortNameAttribute(): string
    {
        $name = trim((string) $this->store_name);

        $suffixes = [
            'coupons with promo codes',
            'coupon & discount codes',
            'coupon and discount codes',
            'coupon & discount code',
            'coupon & discount',
            'coupon codes',
            'coupon code',
            'promo codes',
            'coupons',
            'coupon',
        ];

        foreach ($suffixes as $suffix) {
            $pattern = '/\s*[-:|]?\s*'.preg_quote($suffix, '/').'\s*$/i';
            if (preg_match($pattern, $name)) {
                $name = trim(preg_replace($pattern, '', $name));
                break;
            }
        }

        return $name !== '' ? $name : trim((string) $this->store_name);
    }

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
