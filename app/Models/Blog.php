<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'featured_image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'schema',
        'author',
        'category_id',
        'status',
        'sort_order',
        'views_count',
        'featured',
        'recommended'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Auto-generate slug from title (disabled - handled manually in form)
    // public function setTitleAttribute($value)
    // {
    //     $this->attributes['title'] = $value;
    //     $this->attributes['slug'] = Str::slug($value);
    // }

    // Get formatted date
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }

    // Get excerpt from description if not set
    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        return Str::limit(strip_tags($this->description), 150);
    }

    // Scope for published blogs
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope for ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // Relationship with blog category
    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
}
