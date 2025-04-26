@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Reviews Table</h6>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Review</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Product</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-between" style="min-width: 250px;">
                                                <div class="text-truncate me-2" style="max-width: 200px;" title="{{ $review->comment }}">
                                                    {{ \Illuminate\Support\Str::limit($review->comment, 80) }}
                                                </div>
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal{{ $review->id }}">
                                                    View
                                                </button>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="reviewModal{{ $review->id }}" tabindex="-1" aria-labelledby="reviewModalLabel{{ $review->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-dark text-white">
                                                            <h6 class="modal-title" id="reviewModalLabel{{ $review->id }}">Full Review</h6>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                                                            <p class="mb-0" style="word-break: break-word;">
                                                                {{ $review->comment }}
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-sm btn-outline-dark" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <h6 class="mb-0 text-sm">{{ $review->user->name ?? 'N/A' }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $review->user->email ?? '' }}</p>
                                        </td>

                                        <td class="align-middle text-center">
                                            <span class="text-xs font-weight-bold">{{ $review->product->name ?? 'N/A' }}</span>
                                        </td>

                                        <!-- Image Preview -->
                                        <td class="align-middle text-center">
                                            @if ($review->image)
                                                <img src="{{ asset('storage/' . $review->image) }}" alt="Review Image" class="img-fluid rounded" style="height: 50px; width: 50px;">
                                            @else
                                                <span class="text-muted text-xs">No Image</span>
                                            @endif
                                        </td>

                                        <!-- Status -->
                                        <td class="align-middle text-center">
                                            @php
                                            $badgeColor = match($review->status) {
                                                'success' => 'bg-gradient-success',
                                                'rejected' => 'bg-gradient-danger',
                                                default => 'bg-gradient-warning',
                                            };
                                        @endphp
                                            <span class="badge badge-sm {{ $badgeColor }}">{{ ucfirst($review->status) }}</span>
                                        </td>

                                        <!-- Action Buttons -->
                                        <td class="align-middle text-center">
                                           <!-- Confirm Button -->
<form action="{{ route('product-reviews.update', $review->id) }}" method="POST" class="d-inline-block">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="success">
    <button type="submit" class="btn btn-success btn-sm">Confirm</button>
</form>

<!-- Reject Button -->
<form action="{{ route('product-reviews.update', $review->id) }}" method="POST" class="d-inline-block ms-1">
    @csrf
    @method('PATCH')
    <input type="hidden" name="status" value="rejected">
    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
</form>
                  </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No reviews found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
