<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total',
        'status',
        'address',
        'payment_id',
        'method',
        'notes',
        'order_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function Items()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    public function getFormattedAddressAttribute()
    {
        return nl2br(e($this->address));
    }
}
