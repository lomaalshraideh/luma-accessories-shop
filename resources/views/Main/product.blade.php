@extends('main.layouts.main')

@section('content')
<section class="py-5">
    <div class="container">
        <h4 class="text-uppercase mb-4">Our Products</h4>
        <div class="row g-4">

            @forelse ($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="product-item image-zoom-effect link-effect h-100">
                        <div class="image-holder position-relative">
                            @if($product->images->first())
                                <a href="#">
                                    <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="{{ $product->name }}" class="product-image img-fluid">
                                </a>
                            @endif

                            <a href="#" class="btn-icon btn-wishlist">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <use xlink:href="#heart"></use>
                                </svg>
                            </a>

                            <div class="product-content text-center">
                                <h5 class="text-uppercase fs-5 mt-3">
                                    <a href="#">{{ $product->name }}</a>
                                </h5>
                                <form action="{{ route('carts.store') }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <a href="javascript:void(0)" onclick="this.closest('form').submit()" class="text-decoration-none" data-after="Add to cart">
                                        <span>${{ number_format($product->price, 2) }}</span>
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p>No products available.</p>
            @endforelse

        </div>
        <div class="row mt-4">
            <div class="col text-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
