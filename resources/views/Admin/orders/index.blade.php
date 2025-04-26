@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Orders Table</h6>
                        <div class="pe-3">
                            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">
                                + Create New Order
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Order ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Address</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Total</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <!-- Order ID -->
                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $order->id }}</h6>
                                        </td>

                                        <!-- User Info -->
                                        <td>
                                            <p class="text-sm mb-0">{{ $order->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $order->user->email ?? 'N/A' }}</p>
                                        </td>

                                        <!-- Address -->
                                        <td>
                                            @if($order->address)
                                                <p class="text-sm mb-0">{{ $order->address->city }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $order->address->street }}</p>
                                            @else
                                                <span class="text-muted text-xs">No Address</span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @php
                                                $statusClass = match($order->status) {
                                                    'pending' => 'bg-gradient-warning',
                                                    'shipped' => 'bg-gradient-primary',
                                                    'delivered' => 'bg-gradient-success',
                                                    'cancelled' => 'bg-gradient-danger',
                                                    default => 'bg-gradient-secondary',
                                                };
                                            @endphp

                                            <span class="badge {{ $statusClass }} text-white">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>


                                        <!-- Total -->
                                        <td class="text-center">
                                            <span class="text-sm">${{ number_format($order->total, 2) }}</span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-success btn-sm" title="View">View</a>
                                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm" title="Edit">Edit</a>

                                            <form id="delete-form-{{ $order->id }}" action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmDelete({{ $order->id }})"
                                                data-toggle="tooltip"
                                                data-original-title="Delete order">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($orders->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center py-3">
                                            <span class="text-muted">No orders found.</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3 px-3">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(orderId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This order will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + orderId).submit();
            }
        });
    }
</script>
@endpush
