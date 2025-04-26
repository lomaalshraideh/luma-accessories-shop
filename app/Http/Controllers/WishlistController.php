<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\User;
use Illuminate\Http\Request;

class WishlistController extends Controller
{

    public function index()
    {
        if (auth()->check()) {
            // User is logged in
            $wishlistItems = Wishlist::with('product', 'product.category', 'product.images')
                ->where('user_id', auth()->id())
                ->get();
        } else {
            // Guest user - either redirect to login or handle guest wishlists
            // Option 1: Redirect to login
            return redirect()->route('login')->with('error', 'Please login to view your wishlist');

            // Option 2: If you have session-based guest wishlists
            // $wishlistItems = collect(session('wishlist', []));
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


    public function clearAll(Request $request)
    {
        $userId = auth()->id();
        Wishlist::where('user_id', $userId)->delete();

        return redirect()->route('wishlists.index')
            ->with('success', 'Your wishlist has been cleared!');
    }

    public function destroy(Wishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->route('wishlists.index')->with('success', 'Wishlist deleted!');
    }
}
