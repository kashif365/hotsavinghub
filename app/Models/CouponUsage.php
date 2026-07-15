<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable = [
        'coupon_id',
        'usage_date',
        'ip_address',
        'session_id'
    ];

    protected $casts = [
        'usage_date' => 'date',
    ];

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}
