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
            <thead>
              <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($wishlistItems as $item)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    @if($item->product->images->first())
                    <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                         alt="{{ $item->product->name }}" width="60" class="img-thumbnail me-3">
                    @endif
                    <div>
                      <h6 class="mb-1">{{ $item->product->name }}</h6>
                      <small class="text-muted">{{ Str::limit($item->product->description, 60) }}</small>
                    </div>
                  </div>
                </td>
                <td class="align-middle">${{ number_format($item->product->price, 2) }}</td>
                <td class="align-middle">
                  <div class="d-flex">
                    <form action="{{ route('carts.store') }}" method="POST" class="me-2 add-to-cart-form">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                      <input type="hidden" name="quantity" value="1">
                      <button type="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                    </form>

                    <form action="{{ route('wishlists.items.destroy', $item->id) }}" method="POST" class="wishlist-remove-form">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
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
          Continue Shopping
        </a>

        @if(count($wishlistItems) > 0)
        <form action="{{ route('wishlists.clear') }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger">Clear Wishlist</button>
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
    // Handle wishlist item removal animation
    const removeForms = document.querySelectorAll('.wishlist-remove-form');
    removeForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const row = this.closest('tr');
        row.style.transition = 'opacity 0.3s ease';
        row.style.opacity = '0.5';

        // Get the actual wishlistItemId from the form action URL
        const actionUrl = this.getAttribute('action');

        // Submit the form with AJAX
        fetch(actionUrl, {
          method: 'POST',
          body: new FormData(this),
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Remove the row completely after fade out
            setTimeout(() => {
              row.remove();

              // Check if wishlist is now empty
              const remainingRows = document.querySelectorAll('.wishlist-wrap tbody tr');
              if (remainingRows.length === 0) {
                window.location.reload(); // Refresh to show empty state
              }
            }, 500);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          row.style.opacity = '1'; // Restore opacity if error
        });
      });
    });

    // Handle "Add to Cart" with automatic wishlist removal
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    addToCartForms.forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        const row = this.closest('tr');

        // Add to cart via AJAX
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
            // Get the wishlist item removal URL from the nearby form
            const removeForm = row.querySelector('.wishlist-remove-form');
            const removeUrl = removeForm.getAttribute('action');

            // Fade out the row
            row.style.transition = 'all 0.5s ease';
            row.style.opacity = '0.2';

            // Now remove from wishlist
            fetch(removeUrl, {
              method: 'POST',
              headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
              },
              body: JSON.stringify({
                '_method': 'DELETE'
              })
            })
            .then(response => response.json())
            .then(wishlistData => {
              if (wishlistData.success) {
                // Remove the row
                setTimeout(() => {
                  row.remove();

                  // Check if wishlist is now empty
                  const remainingRows = document.querySelectorAll('.wishlist-wrap tbody tr');
                  if (remainingRows.length === 0) {
                    window.location.reload(); // Refresh to show empty state
                  }
                }, 500);

                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                  Product added to cart and removed from wishlist
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.querySelector('.container').prepend(alertDiv);
              }
            });
          }
        })
        .catch(error => console.error('Error:', error));
      });
    });
  });
</script>
@endpush
@endsection
