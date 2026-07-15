<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Networks extends Model
{
    protected $fillable = ['name', 'affiliate_id', 'status', 'sort_order'];
}

