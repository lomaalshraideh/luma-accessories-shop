<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCoupon extends Model
{
    /** @use HasFactory<\Database\Factories\DiscountCouponFactory> */
    use HasFactory;
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'expires_at',
        'is_active',
    ];

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
