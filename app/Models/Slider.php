<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'background_image',
        'label',
        'heading',
        'subtitle',
        'cta_text',
        'cta_url',
        'secondary_image',
        'logo',
        'badge_color',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
