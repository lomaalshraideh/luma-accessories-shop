<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\WishlistItem; // Add this import
use App\Models\User;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index()
    {
        if (auth()->check()) {
            // Get the user's wishlist
            $wishlist = Wishlist::where('user_id', auth()->id())->first();

            if ($wishlist) {
                // INCORRECT: $wishlistItems = $wishlist->product;

                // CORRECT: Get wishlist items with their related products
                $wishlistItems = $wishlist->items()->with('product')->get();
            } else {
                $wishlistItems = collect(); // Empty collection if no wishlist exists
            }
        } else {
            return redirect()->route('login')->with('error', 'Please login to view your wishlist');
        }

        return view('main.wishlist', compact('wishlistItems'));
    }


    public function create()
    {
        $users = User::all();
        return view('wishlists.create', compact('users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        Wishlist::create($request->all());

        return redirect()->route('wishlists.index')->with('success', 'Wishlist created!');
    }



    public function show(Wishlist $wishlist)
    {
        return view('wishlists.show', compact('wishlist'));
    }


    public function edit(Wishlist $wishlist)
    {
        $users = User::all();
        return view('wishlists.edit', compact('wishlist', 'users'));
    }


    public function update(Request $request, Wishlist $wishlist)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $wishlist->update($request->all());

        return redirect()->route('wishlists.index')->with('success', 'Wishlist updated!');
    }


    public function clearAll()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to manage your wishlist');
        }

        $wishlist = Wishlist::where('user_id', auth()->id())->first();

        if ($wishlist) {
            // Delete all items associated with this wishlist
            $wishlist->items()->delete();
            return redirect()->back()->with('success', 'Your wishlist has been cleared');
        }

        return redirect()->back();
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->route('wishlists.index')->with('success', 'Wishlist deleted!');
    }

    /**
     * Add a product to the wishlist
     */
    public function addProduct(Request $request)
    {
        // Check if user is logged in
        if (!auth()->check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to add items to your wishlist',
                    'redirect' => route('login')
                ]);
            }
            return redirect()->route('login')->with('error', 'Please login to add items to your wishlist');
        }

        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Get or create user's wishlist
        $wishlist = Wishlist::firstOrCreate([
            'user_id' => auth()->id()
        ]);

        // Check if product is already in wishlist
        $existingItem = WishlistItem::where('wishlist_id', $wishlist->id)
                                 ->where('product_id', $request->product_id)
                                 ->first();

        // If not already in wishlist, add it
        if (!$existingItem) {
            WishlistItem::create([
                'wishlist_id' => $wishlist->id,
                'product_id' => $request->product_id
            ]);
            $message = 'Product added to wishlist';
            $added = true;
        } else {
            // Optional: Remove from wishlist if already there (toggle behavior)
            // $existingItem->delete();
            // $message = 'Product removed from wishlist';
            // $added = false;

            // Or just inform it's already there:
            $message = 'Product is already in your wishlist';
            $added = false;
        }

        // Get updated wishlist count
        $wishlistCount = WishlistItem::where('wishlist_id', $wishlist->id)->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'added' => $added,
                'message' => $message,
                'wishlistCount' => $wishlistCount
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
