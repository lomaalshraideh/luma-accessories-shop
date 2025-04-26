@extends('admin.layouts.admin')
@section('content')
<div class="container-fluid py-2">
    <div class="row">
      <div class="col-12">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3">Admins Table</h6>

                    <div class="pe-3">
                        <a href="{{ route('admins.create') }}" class="btn btn-primary btn-sm">
                            + Create New Admin
                        </a>
                    </div>
                </div>
            </div>

<div class="card-body px-0 pb-2">

    <form method="GET" action="{{ route('admins.index') }}" class="d-flex justify-content-end align-items-center gap-2 mb-3 me-3">
        <input type="text" name="name" value="{{ request('name') }}" class="form-control form-control-sm w-auto" placeholder="Search by name...">
        <button type="submit" class="btn btn-dark btn-sm">Search</button>
    </form>



    <div class="table-responsive p-0">
      <table class="table align-items-center mb-0">
        <thead>
          <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Admin</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
          </tr>
        </thead>
        <tbody>
            @foreach ($admins as $admin )
          <tr>
            <td>
              <div class="d-flex px-2 py-1">
                <div>
                  <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                </div>
                <div class="d-flex flex-column justify-content-center">
                  <h6 class="mb-0 text-sm">{{ $admin->name }}</h6>
                  <p class="text-xs text-secondary mb-0">{{ $admin->email }}</p>
                </div>
              </div>
            </td>
            <td>
              <p class="text-xs font-weight-bold mb-0">{{ $admin->role }}</p>

            </td>
            <td class="align-middle text-center text-sm">

            </td>
            <td class="align-middle text-center">

            </td>
            <td class="align-middle text-center">
                <a
                href="{{ route('admins.show', $admin->id) }}"
                    class="btn btn-success" data-toggle="tooltip"
                    data-original-title="show product">
                    view
                </a>

                <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-warning"
                    data-toggle="tooltip" data-original-title="Edit user">
                    Edit
                </a>

                <form id="delete-form-{{ $admin->id }}" action="{{ route('admins.destroy', $admin->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" class="btn btn-danger"
                onclick="confirmDelete({{ $admin->id }})"
                data-toggle="tooltip"
                data-original-title="Delete admin">
                Delete
            </button>
                </form>

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
</script>
@endpush
