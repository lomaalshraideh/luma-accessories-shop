<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MainOrder;
use App\Models\Cart;
use Illuminate\Http\Request;

class MainOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the user's orders.
     */
    public function index()
    {
        $orders = auth()->user()->mainOrders()->latest()->paginate(10);
        return view('main.orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     */
    public function show(MainOrder $mainOrder)
    {
        // Check if the order belongs to the authenticated user
        if ($mainOrder->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('main.orders.show', compact('mainOrder'));
    }

    /**
     * Cancel the specified order.
     */
    public function cancel(MainOrder $mainOrder)
    {
        // Check if the order belongs to the authenticated user
        if ($mainOrder->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if the order can be cancelled
        if (!in_array($mainOrder->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $mainOrder->status = 'cancelled';
        $mainOrder->cancelled_at = now();
        $mainOrder->save();

        return back()->with('success', 'Order cancelled successfully.');
    }
}
