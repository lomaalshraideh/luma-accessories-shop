@extends('admin.layouts.admin')
@section('content')
<div class="container-fluid py-2">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3">Users Table</h6>
                    <div class="pe-3">
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                            + Create New User
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body px-0 pb-2">
                <form method="GET" action="{{ route('users.index') }}" class="d-flex justify-content-end align-items-center gap-2 mb-3 me-3">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control form-control-sm w-auto" placeholder="Search by name...">
                    <button type="submit" class="btn btn-dark btn-sm">Search</button>
                </form>

                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($users as $user)
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                              <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                            </div>
                          </div>
                        </td>
                        <td class="align-middle text-center text-sm">
                        </td>
                        <td class="align-middle text-center">
                        </td>
                        <td class="align-middle text-center">
                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-success" data-toggle="tooltip" data-original-title="Show user">
                                View
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning" data-toggle="tooltip" data-original-title="Edit user">
                                Edit
                            </a>

                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>

                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $user->id }})" data-toggle="tooltip" data-original-title="Delete user">
                                Delete
                            </button>
                        </td>
                      </tr>
                      @endforeach
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
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This user will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }

    // Success message after redirect
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    // Error message if any
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}'
    });
    @endif
</script>
@endpush

