@extends('main.layouts.main')

@section('content')
<div class="container py-5 mb-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-6 mb-4">My Wishlist</h1>
    </div>
  </div>

  @if(isset($wishlistItems) && count($wishlistItems) > 0)
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
          <table class="table table-hover wishlist-wrap mb-0">
            <thead class="text-muted">
              <tr>
                <th scope="col" width="55%">Product</th>
                <th scope="col" width="15%">Price</th>
                <th scope="col" width="15%">Stock Status</th>
                <th scope="col" width="15%" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($wishlistItems as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="me-3" style="width: 100px; height: 100px;">
                      @if(isset($item->product->images[0]))
                      <img src="{{ asset('storage/' . $item->product->images[0]->image_path) }}" alt="{{ $item->product->name }}" class="img-fluid rounded-3">
                      @else
                      <div class="bg-light rounded-3 d-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                        <span class="text-muted">No Image</span>
                      </div>
                      @endif
                    </div>
                    <div>
                      <a href="{{ route('main-products.show', $item->product->id) }}" class="text-decoration-none">
                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                      </a>
                      @if(isset($item->product->category))
                      <p class="text-muted small mb-0">{{ $item->product->category->name }}</p>
                      @endif
                    </div>
                  </div>
                </td>
                <td>
                  <span class="fw-semibold">${{ number_format($item->product->price, 2) }}</span>
                </td>
                <td>
                  @if($item->product->stock > 0)
                    <span class="badge bg-success-subtle text-success">In Stock</span>
                  @else
                    <span class="badge bg-danger-subtle text-danger">Out of Stock</span>
                  @endif
                </td>
                <td class="text-end">
                  <div class="d-flex justify-content-end">
                    <!-- Add to Cart Button -->
                    <form action="{{ route('cart.store') }}" method="POST" class="me-2">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" class="btn btn-sm btn-outline-primary" @if($item->product->stock <= 0) disabled @endif>
                        <svg width="16" height="16" class="me-1">
                          <use xlink:href="#cart"></use>
                        </svg>
                        Add
                      </button>
                    </form>

                    <!-- Remove from Wishlist Button -->
                    <form action="{{ route('wishlists.destroy', $item->id) }}" method="POST" class="wishlist-remove-form">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <svg width="16" height="16">
                          <use xlink:href="#trash"></use>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('main-products.index') }}" class="btn btn-outline-dark">
          <svg width="16" height="16" class="me-2">
            <use xlink:href="#arrow-left"></use>
          </svg>
          Continue Shopping
        </a>

        @if(count($wishlistItems) > 0)
        <form action="{{ route('wishlists.clear') }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">
            <svg width="16" height="16" class="me-2">
              <use xlink:href="#trash"></use>
            </svg>
            Clear Wishlist
          </button>
        </form>
        @endif
      </div>
    </div>
  </div>

  @else
  <div class="row">
    <div class="col-12">
      <div class="text-center py-5">
        <svg width="80" height="80" class="text-muted mb-4">
          <use xlink:href="#heart"></use>
        </svg>
        <h3>Your wishlist is empty</h3>
        <p class="text-muted">Save items you love to your wishlist and come back to them anytime.</p>
        <a href="{{ route('main-products.index') }}" class="btn btn-primary">Discover Products</a>
      </div>
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Use a class to select the forms instead of matching URL
    const removeForms = document.querySelectorAll('.wishlist-remove-form');
    removeForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        const row = this.closest('tr');
        row.style.transition = 'opacity 0.3s ease';
        row.style.opacity = '0.5';
      });
    });
  });
</script>
@endpush
@endsection
