@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Testimonials Table</h6>
                    </div>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Testimonial</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($testimonials as $testimonial)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column justify-content-center">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="text-truncate" style="max-width: 200px;">
                                                        <span class="text-xs text-secondary">
                                                            {{ \Illuminate\Support\Str::limit($testimonial->message, 50) }}
                                                        </span>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#messageModal{{ $testimonial->id }}">
                                                        View
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="messageModal{{ $testimonial->id }}" tabindex="-1" aria-labelledby="messageModalLabel{{ $testimonial->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-dark text-white">
                                                            <h6 class="modal-title" id="messageModalLabel{{ $testimonial->id }}">Testimonial Message</h6>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" style="max-height: 300px; overflow-y: auto;">
                                                            <p class="mb-0 text-secondary" style="word-break: break-word;">
                                                                {{ $testimonial->message }}
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
                                            <p class="text-xs font-weight-bold mb-0">{{ $testimonial->user->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $testimonial->user->email ?? '' }}</p>
                                        </td>

                                        <td class="align-middle text-center text-sm">
                                            @php
                                                $color = match($testimonial->status) {
                                                    'confirmed' => 'bg-gradient-success',
                                                    'rejected' => 'bg-gradient-danger',
                                                    default => 'bg-gradient-warning',
                                                };
                                            @endphp
                                            <span class="badge badge-sm {{ $color }}">{{ ucfirst($testimonial->status) }}</span>
                                        </td>

                                        <td class="align-middle text-center">
                                            <form action="{{ route('testimonials.update', $testimonial->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="confirmed">
                                                <button type="submit" class="btn btn-success btn-sm">Confirm</button>
                                            </form>

                                            <form action="{{ route('testimonials.update', $testimonial->id) }}" method="POST" class="d-inline-block ms-1">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">No testimonials available.</td>
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
