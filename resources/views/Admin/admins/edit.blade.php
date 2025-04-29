@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Edit Admin</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    <!-- Remove the Bootstrap alert -->

                    <form method="POST" action="{{ route('admins.update', $admin->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group input-group-static mb-4">
                                <label>Password <small>(leave blank to keep current)</small></label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn bg-gradient-dark mb-0">Update Admin</button>
                            <a href="{{ route('admins.index') }}" class="btn btn-outline-dark mb-0">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Check if there's a success message in the session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    @endif

    // Check if there are any validation errors
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        });
    @endif
</script>
@endpush


