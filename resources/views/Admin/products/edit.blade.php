@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Edit Product</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Main Product Edit Form --}}
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Product Name -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Product Name</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $product->name) }}" required>
                                </div>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Category</label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $category->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Price</label>
                                    <input type="number" step="0.01" name="price" class="form-control"
                                        value="{{ old('price', $product->price) }}" required>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Stock</label>
                                    <input type="number" name="stock" class="form-control"
                                        value="{{ old('stock', $product->stock) }}" required>
                                </div>
                            </div>

                            <!-- Upload New Images -->
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Upload New Images</label>
                                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <div class="input-group input-group-static mb-4">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn bg-gradient-dark mb-0">Update</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-dark mb-0">Cancel</a>
                        </div>
                    </form>

                    {{-- Existing Images --}}
                    <div class="mt-5">
                        <strong>Current Images:</strong>
                        <div class="row mt-2">
                            @forelse ($product->images as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="border rounded p-2 position-relative">
                                        <img src="{{ asset('storage/' . $image->image_url) }}"
                                            class="img-fluid rounded w-100" style="max-height: 150px;" alt="Product Image">
                                        {{-- Delete Image Form (separate from product form!) --}}
                                        <form action="{{ route('product-images.destroy', $image->id) }}" method="POST"
                                            style="position:absolute; top:5px; right:10px;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this image?')">×</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No images uploaded.</p>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
