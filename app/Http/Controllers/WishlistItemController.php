<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistItemController extends Controller
{

    public function index()
    {
        $items = WishlistItem::with(['wishlist', 'product'])->get();
        return view('wishlist_items.index', compact('items'));
    }


    public function create()
    {
        $wishlists = Wishlist::all();
        $products = Product::all();
        return view('wishlist_items.create', compact('wishlists', 'products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'wishlist_id' => 'required|exists:wishlists,id',
            'product_id'  => 'required|exists:products,id',
        ]);

        WishlistItem::create($request->all());

        return redirect()->route('wishlist-items.index')->with('success', 'Item added!');
    }



    public function show(WishlistItem $wishlistItem)
    {
        return view('wishlist_items.show', compact('wishlistItem'));
    }


    public function edit(WishlistItem $wishlistItem)
    {
        $wishlists = Wishlist::all();
        $products = Product::all();
        return view('wishlist_items.edit', compact('wishlistItem', 'wishlists', 'products'));
    }


    public function update(Request $request, WishlistItem $wishlistItem)
    {
        $request->validate([
            'wishlist_id' => 'required|exists:wishlists,id',
            'product_id'  => 'required|exists:products,id',
        ]);

        $wishlistItem->update($request->all());

        return redirect()->route('wishlist-items.index')->with('success', 'Item updated!');
    }



    public function destroy($id)
    {
        // Find the wishlist item
        $wishlistItem = WishlistItem::findOrFail($id);

        // Check if user owns this wishlist item
        if (auth()->check() && $wishlistItem->wishlist->user_id === auth()->id()) {
            // Get the wishlist for calculations later
            $wishlist = $wishlistItem->wishlist;

            // Delete the wishlist item
            $wishlistItem->delete();

            // Count remaining items
            $wishlistCount = $wishlist->items->count();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'wishlistCount' => $wishlistCount
                ]);
            }

            return redirect()->back()->with('success', 'Item removed from wishlist');
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to remove this item'
            ], 403);
        }

        return redirect()->back()->with('error', 'You do not have permission to remove this item');
    }
}
