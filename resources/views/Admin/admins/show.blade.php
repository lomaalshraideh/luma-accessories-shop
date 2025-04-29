@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Admin Details</h6>
                </div>

                <div class="card-body px-4 py-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Name:</strong>
                            <p>{{ $admin->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p>{{ $admin->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">

                        {{-- <div class="col-md-6">
                            <strong>Role</strong>
                            <p>{{ $admin->updated_at->format('d M Y - h:i A') }}</p>
                        </div> --}}
                    </div>

                    <div class="text-end mt-4">
                        <a href="{{ route('admins.index') }}" class="btn btn-outline-dark">← Back to List</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
