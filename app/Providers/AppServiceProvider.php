<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema; // Add this import

use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Add view composer to make variables available to all views
        View::composer('*', function ($view) {
            // Get categories for menu
            $menuCategories = Category::all();
            $view->with('menuCategories', $menuCategories);

            // Initialize wishlist variables with defaults
            $offcanvasWishlistItems = collect();
            $wishlistCount = 0;

            // Initialize cart variables with defaults
            $offcanvasCartItems = collect();
            $cartCount = 0;
            $cartTotal = 0;

            // Update with actual data if user is logged in
            if (auth()->check()) {
                // Wishlist data
                $wishlist = Wishlist::where('user_id', auth()->id())->first();
                if ($wishlist) {
                    $offcanvasWishlistItems = $wishlist->items()->with('product.images')->get();
                    $wishlistCount = $offcanvasWishlistItems->count();
                }

                // Cart data
                $cart = Cart::where('user_id', auth()->id())->first();
                if ($cart) {
                    $offcanvasCartItems = $cart->items()->with('product.images')->get();
                    $cartCount = $offcanvasCartItems->sum('quantity');
                    $cartTotal = $offcanvasCartItems->sum(function($item) {
                        return $item->quantity * $item->product->price;
                    });
                }
            }

            // Share variables with all views
            $view->with('offcanvasWishlistItems', $offcanvasWishlistItems);
            $view->with('wishlistCount', $wishlistCount);
            $view->with('offcanvasCartItems', $offcanvasCartItems);
            $view->with('cartCount', $cartCount);
            $view->with('cartTotal', $cartTotal);
        });
    }
}
