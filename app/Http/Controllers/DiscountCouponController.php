<?php

namespace App\Http\Controllers;

use App\Models\DiscountCoupon;
use Illuminate\Http\Request;

class DiscountCouponController extends Controller
{
    public function index()
    {
        $coupons = DiscountCoupon::all();
        return view('admin.discount_coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('discount_coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:discount_coupons,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        DiscountCoupon::create($request->all());

        return redirect()->route('discount-coupons.index')->with('success', 'Coupon created!');
    }

    public function show(DiscountCoupon $discountCoupon)
    {
        return view('discount_coupons.show', compact('discountCoupon'));
    }

    public function edit(DiscountCoupon $discountCoupon)
    {
        return view('discount_coupons.edit', compact('discountCoupon'));
    }

    public function update(Request $request, DiscountCoupon $discountCoupon)
    {
        $request->validate([
            'code' => 'required|unique:discount_coupons,code,' . $discountCoupon->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'expires_at' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $discountCoupon->update($request->all());

        return redirect()->route('discount-coupons.index')->with('success', 'Coupon updated!');
    }

    public function destroy(DiscountCoupon $discountCoupon)
    {
        $discountCoupon->delete();
        return redirect()->route('discount-coupons.index')->with('success', 'Coupon deleted!');
    }
}
