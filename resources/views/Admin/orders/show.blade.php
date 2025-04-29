@extends('admin.layouts.admin')
@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card my-4">
                <div class="card-header bg-gradient-dark text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Order Details - #{{ $order->id }}</h6>
                    <td class="text-center">
                        @php
                            $statusClass = match($order->status) {
                                'pending'   => 'bg-warning text-dark px-3',
                                'shipped'   => 'bg-primary text-white px-3',
                                'delivered' => 'bg-success text-white px-3',
                                'cancelled' => 'bg-danger text-white px-3',
                                default     => 'bg-secondary text-white px-3',
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </div>

                <div class="card-body">

                    <!-- User Info -->
                    <h6>User Information</h6>
                    <p><strong>Name:</strong> {{ $order->user->name ?? 'N/A' }}</p>
                    <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>

                    <hr>

                    <!-- Address -->
                    <h6>Shipping Address</h6>
                    @if ($order->address)
                        {{-- <p><strong>City:</strong> {{ $order->address->city }}</p>
                        <p><strong>Street:</strong> {{ $order->address->street }}</p> --}}
                        {!! $order->formatted_address !!}
                    @else
                        <p class="text-muted">No address provided.</p>
                    @endif

                    <hr>

                    <!-- Order Items -->
                    <h6>Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="text-secondary">
                                <tr>
                                    <th>Product</th>
                                    <th>Image</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->Items as $item)
                                    <tr class="order-item">
                                        <td>{{ $item->product->name ?? 'Product Deleted' }}</td>
                                        <td>
                                            @if($item->product && $item->product->images->first())
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_url) }}"
                                                     width="50" height="50" class="rounded">
                                            @else
                                                <span class="text-muted">No image</span>
                                            @endif
                                        </td>
                                        <td class="text-center">${{ number_format($item->price, 2) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-center">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <hr>

                    <!-- Notes -->
                    @if (!empty($order->notes))
                        <h6>Order Notes</h6>
                        <p class="text-dark">{{ $order->notes }}</p>
                        <hr>
                    @endif

                    <!-- Total + Back -->
                    <h5 class="text-end mt-4">Total: $<span id="display-total">{{ number_format($order->total ?? 0, 2) }}</span></h5>

                    <div class="text-end mt-4">
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            ← Back to Orders
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Example JavaScript to update the total amount on the form
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(item => {
            const price = parseFloat(item.querySelector('[name="prices[]"]').value || 0);
            const quantity = parseInt(item.querySelector('[name="quantities[]"]').value || 0);
            total += price * quantity;
        });

        document.getElementById('total_amount').value = total.toFixed(2);
        document.getElementById('display-total').textContent = `$${total.toFixed(2)}`;
    }

    // Attach event listeners to update total when products or quantities change
    document.querySelectorAll('[name="products[]"], [name="quantities[]"]').forEach(el => {
        el.addEventListener('change', updateTotal);
    });
</script>
@endsection

