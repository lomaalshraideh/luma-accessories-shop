@extends('main.layouts.main')

@section('content')
<!-- Order Success Banner -->
<div class="bg-success-subtle py-5">
    <div class="container text-center">
        <div class="mb-4">
            <div class="d-inline-flex justify-content-center align-items-center bg-white rounded-circle p-3 mb-3 shadow-sm">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3.5rem;"></i>
            </div>
        </div>
        <h1 class="mb-2 fw-bold">Thank You for Your Order!</h1>
        <p class="lead mb-1">Your order has been placed successfully.</p>
        <p class="text-muted">Order #{{ $order->order_number }}</p>
    </div>
</div>

<!-- Order Details Section -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-light py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold">Order Details</h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->images->first())
                                            <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                                                 alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-light py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold">Shipping Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Shipping Address</p>
                            <p class="mb-0">{{ $order->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted mb-1">Payment Method</p>
                            <p class="mb-0">{{ ucfirst($order->payment_method) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-light py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold">Order Summary</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Order details -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($order->total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold">${{ number_format($order->total, 2) }}</span>
                    </div>

                    <!-- Order status -->
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            We'll send you an email confirmation with tracking details when your order ships.
                        </div>
                    </div>

                    <!-- Action buttons -->
                    <div class="d-grid gap-2">
                        <a href="/" class="btn btn-primary">Back to Home</a>
                        <a href="/shop" class="btn btn-outline-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/thankyou.css') }}">
@endpush
