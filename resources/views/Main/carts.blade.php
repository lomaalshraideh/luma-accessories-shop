@extends('main.layouts.main')

@section('content')
<div class="container py-5 mb-5">
  <div class="row">
    <div class="col-12">
      <h1 class="display-6 mb-4">Your Shopping Cart</h1>

      @if(session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif

      @if(session('error'))
          <div class="alert alert-danger">
              {{ session('error') }}
          </div>
      @endif
    </div>
  </div>

  @if(isset($cartItems) && count($cartItems) > 0)
  <div class="row">
    <!-- Cart Items -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-body p-0">
          <table class="table table-hover shopping-cart-wrap mb-0">
            <thead class="text-muted">
              <tr>
                <th scope="col" width="50%">Product</th>
                <th scope="col" width="15%">Price</th>
                <th scope="col" width="20%">Quantity</th>
                <th scope="col" width="15%" class="text-end">Action</th>
              </tr>
            </thead>

            <tbody>
              @php
                $subtotal = 0;
              @endphp

              @foreach($cartItems as $item)
              @php
                $subtotal += $item->product->price * $item->quantity;
              @endphp
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <div class="me-3" style="width: 100px; height: 100px;">
                      @if(isset($item->product->images[0]))
                      <img src="{{ asset('storage/' . $item->product->images[0]->image_url) }}" alt="{{ $item->product->name }}" class="img-fluid rounded-3">
                      @else
                      <div class="bg-light rounded-3 d-flex justify-content-center align-items-center" style="width: 100px; height: 100px;">
                        <span class="text-muted">No Image</span>
                      </div>
                      @endif
                    </div>
                    <div>
                      <h6 class="mb-1">{{ $item->product->name }}</h6>
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
                  <div class="quantity-selector d-flex align-items-center">
                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                      @csrf
                      @method('PUT')
                      <button type="button" class="btn btn-sm btn-outline-secondary border-0 quantity-btn" onclick="decrementQuantity(this)">
                        <svg width="16" height="16">
                          <use xlink:href="#minus"></use>
                        </svg>
                      </button>
                      <input type="number" name="quantity" class="form-control form-control-sm mx-2 text-center quantity-input" value="{{ $item->quantity }}" min="1" style="width: 60px;">
                      <button type="button" class="btn btn-sm btn-outline-secondary border-0 quantity-btn" onclick="incrementQuantity(this)">
                        <svg width="16" height="16">
                          <use xlink:href="#plus"></use>
                        </svg>
                      </button>
                    </form>
                  </div>
                </td>
                <td class="text-end">
                  <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger border-0" type="submit">
                      <svg width="16" height="16">
                        <use xlink:href="#trash"></use>
                      </svg>
                    </button>
                  </form>
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
        <button type="button" class="btn btn-outline-dark" id="update-cart-btn">
          <svg width="16" height="16" class="me-2">
            <use xlink:href="#refresh"></use>
          </svg>
          Update Cart
        </button>
      </div>
    </div>

    <!-- Cart Summary -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-0">
          <h5 class="mb-0">Order Summary</h5>
        </div>
        <div class="card-body">
          @php
            $shipping = 10.00; // Default shipping cost
          @endphp
          <div class="d-flex justify-content-between mb-3">
            <span>Subtotal</span>
            <span class="fw-semibold">${{ number_format($subtotal, 2) }}</span>
          </div>
          <div class="d-flex justify-content-between mb-3">
            <span>Shipping</span>
            <span class="fw-semibold">${{ number_format($shipping, 2) }}</span>
          </div>
          <hr>
          <div class="d-flex justify-content-between mb-4">
            <span class="fw-semibold">Total</span>
            <span class="fw-bold fs-5">${{ number_format($subtotal + $shipping, 2) }}</span>
          </div>

          <div class="d-grid">
            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg">
              Proceed to Checkout
            </a>
          </div>

          <div class="mt-4">
            <h6 class="mb-3">We Accept</h6>
            <div class="d-flex gap-2">
              <div class="payment-icon">
                <img src="{{ asset('images/visa.svg') }}" alt="Visa" height="30">
              </div>
              <div class="payment-icon">
                <img src="{{ asset('images/mastercard.svg') }}" alt="Mastercard" height="30">
              </div>
              <div class="payment-icon">
                <img src="{{ asset('images/paypal.svg') }}" alt="PayPal" height="30">
              </div>
              <div class="payment-icon">
                <img src="{{ asset('images/apple-pay.svg') }}" alt="Apple Pay" height="30">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @else
  <div class="row">
    <div class="col-12">
      <div class="text-center py-5">
        <svg width="80" height="80" class="text-muted mb-4">
          <use xlink:href="#cart"></use>
        </svg>
        <h3>Your cart is empty</h3>
        <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
        <a href="{{ route('main-products.index') }}" class="btn btn-primary">Start Shopping</a>
      </div>
    </div>
  </div>
  @endif
</div>

@push('scripts')
<script>
  // Make sure we have the CSRF token in the header for AJAX requests
  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

  // Define these functions in the global scope since they're called directly from onclick attributes
  function decrementQuantity(button) {
    const form = button.closest('form');
    const input = form.querySelector('input[name="quantity"]');

    if (parseInt(input.value) > 1) {
      input.value = parseInt(input.value) - 1;

      // Visual feedback that quantity is changing
      button.disabled = true;
      input.classList.add('bg-light');

      // Send AJAX request to update cart
      const formData = new FormData(form);

      fetch(form.action, {
        method: 'PUT',
        headers: {
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Success feedback
          input.classList.add('bg-success', 'bg-opacity-25');
          setTimeout(() => {
            window.location.reload();
          }, 300);
        } else {
          // Error feedback
          input.classList.add('bg-danger', 'bg-opacity-25');
          input.value = parseInt(input.value) + 1; // Revert
          setTimeout(() => {
            input.classList.remove('bg-light', 'bg-danger', 'bg-opacity-25');
            button.disabled = false;
          }, 1000);
        }
      })
      .catch(error => {
        console.error('Error updating cart:', error);
        input.classList.remove('bg-light');
        input.value = parseInt(input.value) + 1; // Revert
        button.disabled = false;
      });
    }
  }

  function incrementQuantity(button) {
    const form = button.closest('form');
    const input = form.querySelector('input[name="quantity"]');

    // Increment the value
    input.value = parseInt(input.value) + 1;

    // Visual feedback that quantity is changing
    button.disabled = true;
    input.classList.add('bg-light');

    // Send AJAX request to update cart
    const formData = new FormData(form);

    fetch(form.action, {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Success feedback
        input.classList.add('bg-success', 'bg-opacity-25');
        setTimeout(() => {
          window.location.reload();
        }, 300);
      } else {
        // Error feedback
        input.classList.add('bg-danger', 'bg-opacity-25');
        input.value = parseInt(input.value) - 1; // Revert
        setTimeout(() => {
          input.classList.remove('bg-light', 'bg-danger', 'bg-opacity-25');
          button.disabled = false;
        }, 1000);
      }
    })
    .catch(error => {
      console.error('Error updating cart:', error);
      input.classList.remove('bg-light');
      input.value = parseInt(input.value) - 1; // Revert
      button.disabled = false;
    });
  }

  // Make sure the DOM is fully loaded before attaching events
  document.addEventListener('DOMContentLoaded', function() {
    // Add confirmation for delete buttons
    document.querySelectorAll('form[action*="cart/"]').forEach(form => {
      if (form.method === 'post' && form.innerHTML.includes('DELETE')) {
        const deleteBtn = form.querySelector('button[type="submit"]');
        if (deleteBtn) {
          deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to remove this item from your cart?')) {
              form.submit();
            }
          });
        }
      }
    });

    // Update Cart button functionality
    const updateCartBtn = document.getElementById('update-cart-btn');
    if (updateCartBtn) {
      updateCartBtn.addEventListener('click', function() {
        let updateCount = 0;
        const forms = document.querySelectorAll('.quantity-selector form');
        const totalForms = forms.length;

        if (totalForms === 0) return;

        updateCartBtn.disabled = true;
        updateCartBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

        forms.forEach(form => {
          const formData = new FormData(form);

          fetch(form.action, {
            method: 'PUT',
            headers: {
              'X-CSRF-TOKEN': csrfToken,
              'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            updateCount++;
            if (updateCount === totalForms) {
              setTimeout(() => {
                window.location.reload();
              }, 300);
            }
          })
          .catch(error => {
            console.error('Error updating cart:', error);
            updateCount++;
            if (updateCount === totalForms) {
              setTimeout(() => {
                window.location.reload();
              }, 300);
            }
          });
        });
      });
    }
  });
</script>
@endpush
@endsection


