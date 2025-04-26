<?php
namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = CartItem::with(['cart', 'product'])->get();
        return view('cart_items.index', compact('items'));
    }

    public function create()
    {
        $carts = Cart::all();
        $products = Product::all();
        return view('cart_items.create', compact('carts', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart_id'    => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        CartItem::create($request->all());

        return redirect()->route('cart-items.index')->with('success', 'Item added to cart!');
    }

    public function show(CartItem $cartItem)
    {
        return view('cart_items.show', compact('cartItem'));
    }

    public function edit(CartItem $cartItem)
    {
        $carts = Cart::all();
        $products = Product::all();
        return view('cart_items.edit', compact('cartItem', 'carts', 'products'));
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Ensure users can only update their own cart items
        $userCart = Cart::where('user_id', Auth::id())->first();

        if (!$userCart || $cartItem->cart_id !== $userCart->id) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully'
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated successfully');
    }

    public function destroy(CartItem $cartItem)
    {
        // Ensure users can only delete their own cart items
        $userCart = Cart::where('user_id', Auth::id())->first();

        if (!$userCart || $cartItem->cart_id !== $userCart->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $cartItem->delete();
        return redirect()->back()->with('success', 'Item removed from cart');
    }
}

