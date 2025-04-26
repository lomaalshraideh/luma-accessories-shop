<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountCoupon;
use Illuminate\Support\Facades\DB;

class DiscountCouponSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: Reset the table first
        DB::table('discount_coupons')->truncate();

        DiscountCoupon::create([
            'code' => 'SAVE18',
            'discount_type' => 'fixed',
            'discount_value' => 25.97,
            'expires_at' => now()->addDays(30),
            'is_active' => true,
        ]);
    }
}
