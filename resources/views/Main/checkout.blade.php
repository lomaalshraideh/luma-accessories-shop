@extends('main.layouts.main')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row">
        <!-- Order Summary Section -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h4>Order Summary</h4>
                </div>
                <div class="card-body">
                    @if(isset($cartItems) && count($cartItems) > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($cartItems as $item)
                                    @php
                                        $subtotal = $item->product->price * $item->quantity;
                                        $total += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product->images->isNotEmpty())
                                                    <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                                                        alt="{{ $item->product->name }}" width="60" class="img-thumbnail me-2">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex justify-content-center align-items-center"
                                                        style="width: 60px; height: 60px;"></div>
                                                @endif
                                                <span>{{ $item->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->product->price, 2) }}</td>
                                        <td>${{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                    <td>${{ number_format($total, 2) }}</td>
                                </tr>
                                <!-- You can add tax calculation here if needed -->
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="alert alert-info">
                            Your cart is empty. <a href="{{ route('main-products.index') }}">Continue shopping</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment and Shipping Information -->
        <div class="col-md-4">
            @if(isset($cartItems) && count($cartItems) > 0)
                <div class="card">
                    <div class="card-header bg-light">
                        <h4>Shipping & Payment</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.store') }}" method="POST">
                            @csrf

                            <!-- Shipping Information -->
                            <h5 class="mb-3">Shipping Address</h5>
                            <div class="mb-3">
                                <label for="address_line1" class="form-label">Address Line 1</label>
                                <input type="text" class="form-control" id="address_line1" name="address_line1" required>
                            </div>
                            <div class="mb-3">
                                <label for="address_line2" class="form-label">Address Line 2 (optional)</label>
                                <input type="text" class="form-control" id="address_line2" name="address_line2">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="postal_code" class="form-label">Postal Code</label>
                                    <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" required>
                            </div>

                            <!-- Payment Method -->
                            <h5 class="mt-4 mb-3">Payment Method</h5>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method_credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="payment_method_credit_card">
                                        Credit Card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method_paypal" value="paypal">
                                    <label class="form-check-label" for="payment_method_paypal">
                                        PayPal
                                    </label>
                                </div>
                            </div>

                            <!-- Credit Card Details (show/hide based on selected payment method) -->
                            <div id="credit_card_details">
                                <div class="mb-3">
                                    <label for="card_number" class="form-label">Card Number</label>
                                    <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cvv" class="form-label">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123">
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="total" value="{{ $total ?? 0 }}">

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary">Place Order</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Toggle payment method details
    document.addEventListener('DOMContentLoaded', function() {
        const creditCardRadio = document.getElementById('payment_method_credit_card');
        const paypalRadio = document.getElementById('payment_method_paypal');
        const creditCardDetails = document.getElementById('credit_card_details');

        if (creditCardRadio && paypalRadio && creditCardDetails) {
            function togglePaymentDetails() {
                creditCardDetails.style.display = creditCardRadio.checked ? 'block' : 'none';
            }

            creditCardRadio.addEventListener('change', togglePaymentDetails);
            paypalRadio.addEventListener('change', togglePaymentDetails);

            // Initial state
            togglePaymentDetails();
        }
    });
</script>
@endsection
