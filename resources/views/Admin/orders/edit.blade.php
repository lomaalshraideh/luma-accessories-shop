@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card my-4">
                <div class="card-header bg-gradient-dark text-white">
                    <h6>Edit Order - #{{ $order->id }}</h6>
                </div>
                <div class="card-body">

                    <form action="{{ route('orders.update', $order->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-control" required>
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">
                                ← Back to Orders
                            </a>
                            <button type="submit" class="btn btn-dark">Update Order</button>
                        </div>
                    </form>

                    @if($order->status !== 'cancelled')
                        <hr>
                        <form action="{{ route('orders.update', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="btn btn-danger mt-2 w-100">Cancel Order</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

