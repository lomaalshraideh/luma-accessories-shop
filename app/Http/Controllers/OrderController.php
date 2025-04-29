<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'address'])->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        $addresses = \App\Models\Address::all();
        $products = \App\Models\Product::all();
        return view('admin.orders.create', compact('users', 'addresses', 'products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255', // Changed from address_id
            'products' => 'required|array',
            'quantities' => 'required|array',
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'notes' => 'nullable|string',
            'total' => 'required|numeric',
        ]);

        // Create the order
        $order = Order::create([
            'user_id' => $request->user_id,
            'address' => $request->address, // Store the address text directly
            'order_number' => 'ORD-' . strtoupper(uniqid()),
            'status' => $request->status,
            'total_amount' => $request->total,
            'notes' => $request->notes,
        ]);

        // Create order items (same as before)
        foreach ($request->products as $key => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantities[$key];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $quantity,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }


    public function show($id)
    {
        $order = Order::with(['Items.product', 'user', 'address'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }



    public function edit($id)
    {
        $order = Order::with(['user', 'address'])->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }


    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted!');
    }
}
