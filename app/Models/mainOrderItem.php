<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_order_id',
        'product_id',
        'product_name',
        'quantity',
        'price',
        'subtotal',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(MainOrder::class, 'main_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
