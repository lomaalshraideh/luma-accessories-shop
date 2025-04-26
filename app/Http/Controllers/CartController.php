<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        // Add auth middleware to relevant methods
        $this->middleware('auth')->only(['store', 'update', 'destroy']);
    }

    public function index()
    {
        // If user is logged in, show their cart
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();

            if (!$cart) {
                return view('main.carts', ['cartItems' => []]);
            }

            $cartItems = $cart->items()->with('product')->get();
            return view('main.carts', compact('cartItems'));
        }

        return redirect()->route('login')->with('error', 'Please login to view your cart');
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
            'quantity' => 'integer|min:1|nullable',
        ]);

        // Get or create the user's cart
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Check if this product is already in the user's cart items
        $existingCartItem = CartItem::where('cart_id', $cart->id)
                                  ->where('product_id', $request->product_id)
                                  ->first();

        if ($existingCartItem) {
            // Update quantity if product already in cart
            $existingCartItem->quantity += $request->quantity ?? 1;
            $existingCartItem->save();
        } else {
            // Add new product to cart
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return back()->with('success', 'Product added to cart');
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
