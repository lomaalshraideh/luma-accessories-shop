@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Product Details</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p class="text-dark">{{ $product->name }}</p>
                        </div>

                        <div class="col-md-6">
                            <strong>Price:</strong>
                            <p class="text-dark">{{ $product->price }}</p>
                        </div>

                        <div class="col-md-6">
                            <strong>Stock:</strong>
                            <p class="text-dark">{{ $product->stock }}</p>
                        </div>

                        <div class="col-md-6">
                            <strong>Description:</strong>
                            <p class="text-dark">{{ $product->description ?? 'No description available' }}</p>
                        </div>

                        <div class="col-md-12">
                            <strong>Images:</strong>
                            <div class="row mt-2">
                                @forelse ($product->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-2">
                                            <img src="{{ asset('storage/' . $image->image_url) }}" class="img-fluid rounded" alt="Product Image">
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted ms-3">No images available for this product.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-dark">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
