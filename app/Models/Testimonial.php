<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    /** @use HasFactory<\Database\Factories\TestimonialFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'message',
        'image',
        'rating',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
