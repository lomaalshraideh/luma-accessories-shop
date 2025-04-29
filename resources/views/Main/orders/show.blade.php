<!-- filepath: c:\wamp64\www\accessories-shop\resources\views\main\orders\show.blade.php -->
@extends('main.layouts.main')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('main-orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="mb-0">Order #{{ $mainOrder->order_number }}</h4>
                </div>
                <div class="col text-end">
                    <span class="badge bg-{{
                        $mainOrder->status === 'completed' ? 'success' :
                        ($mainOrder->status === 'cancelled' ? 'danger' :
                        ($mainOrder->status === 'processing' ? 'info' : 'warning'))
                    }}">
                        {{ ucfirst($mainOrder->status) }}
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Order Details</h5>
                    <p class="mb-1"><strong>Date:</strong> {{ $mainOrder->created_at->format('F d, Y h:i A') }}</p>
                    <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($mainOrder->payment_method) }}</p>
                    <p class="mb-0">
                        <strong>Payment Status:</strong>
                        <span class="badge bg-{{
                            $mainOrder->payment_status === 'paid' ? 'success' :
                            ($mainOrder->payment_status === 'failed' ? 'danger' : 'warning')
                        }}">
                            {{ ucfirst($mainOrder->payment_status) }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Shipping Address</h5>
                    @if($mainOrder->address)
                        <p class="mb-1">{{ $mainOrder->address->name }}</p>
                        <p class="mb-1">{{ $mainOrder->address->street_address }}</p>
                        <p class="mb-1">{{ $mainOrder->address->city }}, {{ $mainOrder->address->state }} {{ $mainOrder->address->postal_code }}</p>
                        <p class="mb-1">{{ $mainOrder->address->country }}</p>
                        <p class="mb-0">{{ $mainOrder->address->phone }}</p>
                    @else
                        <p class="text-muted">Address not available</p>
                    @endif
                </div>
            </div>

            <h5 class="mb-3">Order Items</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mainOrder->items as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product && $item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                                         alt="{{ $item->product_name }}" width="60" class="img-thumbnail me-3">
                                    @endif
                                    <div>
                                        <h6 class="mb-0">{{ $item->product_name }}</h6>
                                        @if($item->product)
                                        <a href="{{ route('main-products.show', $item->product) }}" class="small text-muted">View Product</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-md-4">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th>Subtotal:</th>
                                <td class="text-end">${{ number_format($mainOrder->total_amount - $mainOrder->tax_amount - $mainOrder->shipping_amount + $mainOrder->discount_amount, 2) }}</td>
                            </tr>
                            @if($mainOrder->discount_amount > 0)
                            <tr>
                                <th>Discount:</th>
                                <td class="text-end">-${{ number_format($mainOrder->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Tax:</th>
                                <td class="text-end">${{ number_format($mainOrder->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Shipping:</th>
                                <td class="text-end">${{ number_format($mainOrder->shipping_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td class="text-end"><strong>${{ number_format($mainOrder->total_amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @if($mainOrder->notes)
            <div class="mt-4">
                <h5>Notes</h5>
                <p class="mb-0">{{ $mainOrder->notes }}</p>
            </div>
            @endif

            @if(in_array($mainOrder->status, ['pending', 'processing']))
            <div class="mt-4">
                <form action="{{ route('main-orders.cancel', $mainOrder) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel Order</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
