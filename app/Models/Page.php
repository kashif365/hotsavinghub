<?php

// app/Models/Page.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'page_title',
        'status',
        'sort_order',
        'page_content',
        'seo_url',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'canonical_url',
        'schema',
        'media',
        'banner_image',
    ];
}
