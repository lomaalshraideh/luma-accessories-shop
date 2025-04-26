<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{

    public function index()
    {
        $reviews = \App\Models\ProductReview::with('user', 'product')->latest()->get();
        return view('admin.product_reviews.index', compact('reviews'));
    }



    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('admin.product_reviews.create', compact('products', 'users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string',
        ]);

        ProductReview::create($request->all());

        return redirect()->route('product_reviews.index')->with('success', 'Review added!');
    }


    public function show(ProductReview $productReview)
    {
        return view('product-reviews.show', compact('productReview'));
    }

    public function edit(ProductReview $productReview)
    {
        $products = Product::all();
        $users = User::all();
        return view('admin.product-reviews.edit', compact('productReview', 'products', 'users'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:success,rejected',
        ]);

        $review = \App\Models\ProductReview::findOrFail($id);
        $review->status = $request->status;
        $review->save();

        return redirect()->back()->with('success', 'Review status updated to ' . $request->status . '.');
    }



    public function destroy(ProductReview $productReview)
    {
        $productReview->delete();
        return redirect()->route('product-reviews.index')->with('success', 'Review deleted!');
    }
    }

