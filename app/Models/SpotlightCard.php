<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpotlightCard extends Model
{
    protected $fillable = [
        'image',
        'logo',
        'heading',
        'cta_label',
        'cta_url',
        'bg_color',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
