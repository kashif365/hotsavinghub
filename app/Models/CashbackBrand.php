<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashbackBrand extends Model
{
    protected $fillable = [
        'logo',
        'store_name',
        'cashback_rate',
        'redirect_url',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];
}
