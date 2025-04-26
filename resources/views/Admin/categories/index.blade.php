@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3">Categories table</h6>
                    <div class="pe-3">
                        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
                            + Create New Category
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 pb-2">

                <form method="GET" action="{{ route('categories.index') }}" class="d-flex justify-content-end align-items-center gap-2 mb-3 me-3">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control form-control-sm w-auto" placeholder="Search by name...">
                    <button type="submit" class="btn btn-dark btn-sm">Search</button>
                </form>


                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>

                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $category)
                        <tr>

                            <td>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">{{ $category->name }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" width="60" class="img-thumbnail">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning"
                                        data-toggle="tooltip" data-original-title="Edit category">
                                        Edit
                                    </a>

                                    <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete({{ $category->id }})"
                                        data-toggle="tooltip"
                                        data-original-title="Delete category">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3">No categories found.</td>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(categoryId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This category will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + categoryId).submit();
            }
        });
    }
</script>
@endpush
