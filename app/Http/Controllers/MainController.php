<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Testimonial;

class MainController extends Controller
{
    public function index()
    {
        $products = Product::with('images', 'category')->take(10)->get(); // Get 10 products
        $random_products = Product::with('images', 'category')->inRandomOrder()->take(10)->get(); // get 10 random products
        $categories = Category::all(); // Get all categories
        $testimonials = Testimonial::where('status', 'confirmed')->latest()->take(6)->get(); // get latest 6 confirmed testimonials


        return view('landing', compact('products', 'categories', 'testimonials', 'random_products'));
    }

    public function products()
    {
        $categorySlug = request('category');
        $categories = Category::all();
        $menuCategories = $categories; // For the menu in the layout

        $query = Product::with('images', 'category');

        // Filter by category if provided
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)
                       ->orWhere('name', $categorySlug)
                       ->first();

            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Get paginated results
        $products = $query->paginate(12);

        // Pass the active category to the view
        $activeCategory = $categorySlug;

        return view('main.product', compact('products', 'categories', 'categorySlug', 'menuCategories', 'activeCategory'));
    }
}


