<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WishlistItemController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\DiscountCouponController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MainProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DashboardController;
use App\Models\Admin;
use Illuminate\Auth\Events\Login;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/admin/login', [AdminController::class, 'index'])->name('admin.login');

// Route::resource('wishlist-items', WishlistItemController::class);

// Route::resource('cart-items', CartItemController::class);

// Route::resource('order-items', OrderItemController::class);

//Admin routes
// Route::get('/admin/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

// Route::get('/admins',[AdminController::class,'index'])->name('admins');

Route::resource('wishlists', WishlistController::class);
Route::resource('carts', CartController::class);

// Add these cart item routes
Route::put('/cart/{cartItem}', [CartItemController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItem}', [CartItemController::class, 'destroy'])->name('cart.destroy');

Route::prefix('admin')->middleware(AdminMiddleware::class)->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('payments', PaymentController::class);
    Route::resource('addresses', AddressController::class);
    Route::resource('discount-coupons', DiscountCouponController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::resource('user-profiles', UserProfileController::class);
    Route::resource('admins', AdminController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::resource('product-images', ProductImageController::class);
    Route::resource('product-reviews', ProductReviewController::class);

    Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');

} );
Route::prefix('admin')->group(function () {

Route::post('login', [AdminController::class, 'login'])->name('admin.login');
// Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('login', [AdminController::class, 'showLoginForm'])->name('login.form');

});

// main site routes
Route::resource('main-products', MainProductController::class)->except(['index']); // Exclude index to avoid conflict
Route::get('/main-products', [MainController::class, 'products'])->name('main-products.index');
// Route::post('/cart', [CartController::class, 'store'])->middleware('auth')->name('cart.store');
Route::get('/', [MainController::class, 'index'])->name('landing');

Auth::routes();

// Route::get('/', [App\Http\Controllers\MainController::class, 'index'])->name('home');


// Cart routes
// Route::post('/cart/add', [App\Http\Controllers\CartController::class, 'addToCart'])->name('cart.add');
// Route::post('/cart/add-redirect', [App\Http\Controllers\CartController::class, 'addToCartAndRedirect'])->name('cart.add.redirect');

// Add or update wishlist item routes if they don't exist
Route::delete('/wishlists/items/{id}', [WishlistItemController::class, 'destroy'])->name('wishlists.items.destroy');

// Make sure this route exists for clearing the entire wishlist
Route::delete('/wishlists/clear', [WishlistController::class, 'clearAll'])->name('wishlists.clear');

// Add this route for adding products to wishlist
Route::post('/wishlists/add-product', [WishlistController::class, 'addProduct'])->name('wishlists.add-product');

// Add this if it's not already there


// And make sure this route exists
Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

// Add or update these routes for user profile
Route::get('/profile', [\App\Http\Controllers\UserProfileController::class, 'show'])->middleware('auth')->name('main.user_profile');
Route::put('/profile/update', [\App\Http\Controllers\UserProfileController::class, 'update'])->middleware('auth')->name('main.user_profile.update');

// Add or update cart item routes
Route::delete('/carts/items/{id}', [CartItemController::class, 'destroy'])->name('cart-items.destroy');

// Main site order routes
Route::middleware(['auth'])->group(function () {
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/thankyou/{orderId}', [CheckoutController::class, 'thankyou'])
    ->name('checkout.thankyou')
    ->middleware('auth');
});


