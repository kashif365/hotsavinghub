<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeContentBlock extends Model
{
    protected $fillable = [
        'title',
        'content',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
