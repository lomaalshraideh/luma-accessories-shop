<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\MainOrder;
use App\Models\MainOrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainCheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the checkout page.
     */
    public function index()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        // Redirect to cart if empty
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Get user addresses
        $addresses = $user->addresses;

        // Calculate order totals
        $subtotal = $cart->items->sum('subtotal');
        $tax = $subtotal * 0.10; // 10% tax
        $shipping = 5.00; // Flat shipping rate
        $total = $subtotal + $tax + $shipping;

        return view('main.checkout.index', compact('cart', 'addresses', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Process the checkout.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        // Validate request
        $request->validate([
            'address_id' => 'required|exists:addresses,id,user_id,' . $user->id,
            'payment_method' => 'required|in:credit_card,paypal,stripe',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if cart exists and has items
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Calculate totals
            $subtotal = $cart->items->sum('subtotal');
            $tax = $subtotal * 0.10; // 10% tax
            $shipping = 5.00; // Flat shipping rate
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = new MainOrder([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'total_amount' => $total,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            $order->save();

            // Create order items
            foreach ($cart->items as $cartItem) {
                MainOrderItem::create([
                    'main_order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'subtotal' => $cartItem->subtotal,
                ]);
            }

            // Here, you would integrate with a payment gateway
            // For now, let's simulate a successful payment

            // Clear the cart after successful order
            CartItem::where('cart_id', $cart->id)->delete();

            // Commit transaction
            DB::commit();

            // Redirect to thank you page
            return redirect()->route('main-checkout.thankyou', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            // Log the error
            \Log::error('Checkout error: ' . $e->getMessage());

            return redirect()->route('main-checkout.index')->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    /**
     * Display the thank you page.
     */
    public function thankYou(MainOrder $mainOrder)
    {
        // Check if the order belongs to the authenticated user
        if ($mainOrder->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('main.checkout.thankyou', compact('mainOrder'));
    }
}
