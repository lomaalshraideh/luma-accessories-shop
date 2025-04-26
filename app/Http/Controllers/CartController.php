<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;

class CartController extends Controller
{


    public function index()
    {
        $carts = Cart::with('user')->get();
         return view('main.carts');

    }

    public function create()
    {
        $users = User::all();
        return view('carts.create', compact('users'));
    }

    public function store(Request $request)

    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Cart::create([
            // 'user_id' => Auth::id(), // assuming users must be logged in
            'product_id' => $request->product_id,
            'quantity' => 1,
        ]);

        return back()->with('success', 'Product added to cart.');
    }

    public function show(Cart $cart)
    {
        return view('carts.show', compact('cart'));
    }

    public function edit(Cart $cart)
    {
        $users = User::all();
        return view('carts.edit', compact('cart', 'users'));
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $cart->update($request->all());

        return redirect()->route('carts.index')->with('success', 'Cart updated!');
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('carts.index')->with('success', 'Cart deleted!');
    }
}
