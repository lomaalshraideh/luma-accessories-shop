<!-- filepath: c:\wamp64\www\accessories-shop\resources\views\Main\checkout\thankyou.blade.php -->
@extends('main.layouts.main')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="mb-3">Thank You for Your Order!</h2>
                    <p class="lead mb-5">Your order has been placed successfully.</p>

                    <!-- Debug info -->
                    <div class="alert alert-info">
                        <p>Debug Info:</p>
                        <p>Order ID: {{ request()->route('orderId') }}</p>
                    </div>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="/" class="btn btn-outline-primary">Back to Home</a>
                        <a href="/shop" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
