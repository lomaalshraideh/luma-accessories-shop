<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductImageController extends Controller
{

    public function index()
    {
        $images = ProductImage::with('product')->get();
        return view('product_images.index', compact('images'));
    }


    public function create()
    {
        $products = Product::all();
        return view('product_images.create', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url'  => 'required|url',
        ]);

        ProductImage::create([
            'product_id' => $request->product_id,
            'image_url'  => $request->image_url,
        ]);

        return redirect()->route('product-images.index')->with('success', 'Image added!');

    }

    public function show(ProductImage $productImage)
    {
        return view('product_images.show', compact('productImage'));
    }


    public function edit(ProductImage $productImage)
    {
        $products = Product::all();
        return view('product_images.edit', compact('productImage', 'products'));
    }


    public function update(Request $request, ProductImage $productImage)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image_url'  => 'required|url',
        ]);

        $productImage->update([
            'product_id' => $request->product_id,
            'image_url'  => $request->image_url,
        ]);

        return redirect()->route('product-images.index')->with('success', 'Image updated!');
    }


    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);

        // Delete file from storage
        Storage::disk('public')->delete($image->image_url);

        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }

}
