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

                            <form action="{{ route('wishlists.add-product') }}" method="POST" class="wishlist-form d-inline">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn-icon btn-wishlist bg-transparent border-0
                                    {{ Auth::check() && Auth::user()->wishlist && Auth::user()->wishlist->items->where('product_id', $product->id)->count() > 0 ? 'text-danger' : '' }}">
                                    <svg width="24" height="24" viewBox="0 0 24 24">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </button>
                            </form>

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

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle wishlist form submissions
    const wishlistForms = document.querySelectorAll('.wishlist-form');

    wishlistForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update wishlist button appearance
                    const wishlistBtn = form.querySelector('.btn-wishlist');
                    if (data.added) {
                        wishlistBtn.classList.add('text-danger');
                    }

                    // Show feedback to user
                    alert(data.message);

                    // Update wishlist count if displayed in the header
                    const wishlistCount = document.querySelector('.wishlist-count');
                    if (wishlistCount) {
                        wishlistCount.textContent = data.wishlistCount;
                    }
                } else if (data.redirect) {
                    // Redirect to login if needed
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>
@endsection
