<?php


namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function cart() {
        return $this->hasOne(Cart::class);
    }

    public function wishlist()
    {
        return $this->hasOne(Wishlist::class);
    }

    public function reviews() {
        return $this->hasMany(ProductReview::class);
    }

    public function addresses() {
        return $this->hasMany(Address::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
public function testimonials()
{
    return $this->hasMany(Testimonial::class);
}
public function profile()
{
    return $this->hasOne(UserProfile::class);
}
public function mainOrders()
{
    return $this->hasMany(MainOrder::class);
}

}
