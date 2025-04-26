@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Create New Product</h6>
                    </div>

                </div>

                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <!-- User -->
                        <div class="mb-3">
                            <label class="fw-bold">User</label>
                            <select name="user_id" class="form-control border border-dark" required>
                                <option value="">Select user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="fw-bold">Address</label>
                            <select name="address_id" class="form-control border border-dark" required>
                                <option value="">Select address</option>
                                @foreach($addresses as $address)
                                    <option value="{{ $address->id }}">
                                        {{ $address->city }}, {{ $address->street }} (User #{{ $address->user_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Order Items -->
                        <h6 class="mt-4">Order Items</h6>
                        <div id="order-items">
                            <div class="row g-2 mb-2 order-item">
                                <div class="col-md-6">
                                    <select name="products[]" class="form-control product-select border border-dark" required>
                                        <option value="">Select product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} - ${{ $product->price }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" name="quantities[]" class="form-control quantity-input border border-dark" min="1" value="1" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="add-item">+ Add Item</button>

                        <!-- Status -->
                        <div class="mb-3">
                            <label class="fw-bold">Status</label>
                            <select name="status" class="form-control border border-dark" required>
                                <option value="pending">Pending</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label class="fw-bold">Notes</label>
                            <textarea name="notes" rows="3" class="form-control border border-dark" placeholder="Write any special instructions here..."></textarea>
                        </div>

                        <!-- Total -->
                        <div class="mb-3">
                            <label class="fw-bold">Total</label>
                            <input type="text" name="total" id="total" class="form-control  border border-dark" readonly>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn bg-gradient-dark mb-0">Create</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-dark mb-0">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.order-item').forEach(function(item) {
            const product = item.querySelector('.product-select');
            const quantity = item.querySelector('.quantity-input').value;
            const price = product.options[product.selectedIndex]?.dataset.price || 0;
            total += parseFloat(price) * parseInt(quantity || 0);
        });
        document.getElementById('total').value = total.toFixed(2);
    }

    document.getElementById('add-item').addEventListener('click', function () {
        const newItem = document.querySelector('.order-item').cloneNode(true);
        newItem.querySelector('select').value = '';
        newItem.querySelector('input').value = 1;
        document.getElementById('order-items').appendChild(newItem);
        updateTotal();
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select') || e.target.classList.contains('quantity-input')) {
            updateTotal();
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.order-item').remove();
            updateTotal();
        }
    });

    document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endpush
@endsection
