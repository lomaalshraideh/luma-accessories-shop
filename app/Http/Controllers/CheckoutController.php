<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Address;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    // public function index()
    // {
    //     $user = auth()->user();
    //     $cart = Cart::where('user_id', $user->id)->first();

    //     if (!$cart || $cart->items->isEmpty()) {
    //         return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
    //     }

    //     $addresses = $user->addresses;

    //     return view('main.checkout.index', compact('cart', 'addresses'));
    // }
    public function index()
    {
        $user = auth()->user();
        $cart = Cart::where('user_id', $user->id)->first();

        // Check if cart exists and has items
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cart->items as $item) {
            $subtotal += $item->quantity * $item->price;
        }

        // Calculate tax (example: 10% of subtotal)
        $tax = $subtotal * 0.10;

        // Calculate shipping (example logic - adjust as needed)
        $shipping = ($subtotal > 100) ? 0 : 10;

        // Calculate total
        $total = $subtotal + $tax + $shipping;

        // Get user addresses
        $addresses = $user->addresses;

        // Create a temporary order object for the checkout page
        $order = new \stdClass();
        $order->order_number = 'Preview';
        $order->total = $total;
        $order->items = $cart->items;
        $order->address = $addresses->first() ? $addresses->first()->street . ', ' . $addresses->first()->city . ', ' . $addresses->first()->country : 'No address available';
        $order->payment_method = 'Not selected yet';

        return view('main.checkout.index', compact('cart', 'addresses', 'subtotal', 'tax', 'shipping', 'total', 'order'));
    }

    /**
     * Process the order
     */
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:credit_card,paypal,stripe',
            'notes' => 'nullable|string',
        ]);

        // Get the cart for the current user
        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate order total from cart items
        $total = 0;
        foreach ($cart->items as $item) {
            // Make sure price is a number
            $price = floatval($item->price);
            $total += $item->quantity * $price;
        }

        // Get the address
        $address = Address::find($request->address_id);

        // Create the order record
        $order = Order::create([
            'user_id' => auth()->id(),
            'address' => $address->street . ', ' . $address->city . ', ' . $address->country,
            'payment_method' => $request->payment_method,
            'total' => $total,
            'status' => 'pending',
            'notes' => $request->notes,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
        ]);

        // Log the order creation for debugging
        \Log::info('Order created', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'user_id' => auth()->id()
        ]);

        // Create order items from cart items
        foreach ($cart->items as $cartItem) {
            // Get the product to ensure we have the price
            $product = Product::find($cartItem->product_id);

            if (!$product) {
                continue; // Skip if product not found
            }

            // Use product price directly to ensure we have a value
            $price = floatval($cartItem->price) > 0 ? floatval($cartItem->price) : $product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $price, // Ensure we have a valid price
            ]);

            // Update product stock
            $product->update([
                'stock' => $product->stock - $cartItem->quantity
            ]);
        }

        // Clear the cart after successful order
        $cart->items()->delete();

        // Make sure the redirect uses the right parameter name
        return redirect()->route('checkout.thankyou', ['orderId' => $order->id]);
    }

    /**
     * Show the thank you page
     */
    public function thankyou($orderId)
    {
        // For testing, get the latest order for the current user
        $order = Order::where('user_id', auth()->id())
            ->latest()
            ->first();

        // Debug
        if ($order) {
            \Log::info('Using latest order for testing', [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);
        }

        return view('Main.checkout.thankyou', compact('order'));
    }
}
