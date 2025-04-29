<!-- filepath: c:\wamp64\www\accessories-shop\resources\views\main\orders\index.blade.php -->
@extends('main.layouts.main')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Your Orders</h2>

    @if($orders->isEmpty())
        <div class="alert alert-info">
            You haven't placed any orders yet.
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('main-products.index') }}" class="btn btn-primary">Start Shopping</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <span class="badge bg-{{
                                $order->status === 'completed' ? 'success' :
                                ($order->status === 'cancelled' ? 'danger' :
                                ($order->status === 'processing' ? 'info' : 'warning'))
                            }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{
                                $order->payment_status === 'paid' ? 'success' :
                                ($order->payment_status === 'failed' ? 'danger' : 'warning')
                            }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <a href="{{ route('main-orders.show', $order) }}" class="btn btn-sm btn-primary">View</a>

                            @if(in_array($order->status, ['pending', 'processing']))
                            <form action="{{ route('main-orders.cancel', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this order?')">Cancel</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
