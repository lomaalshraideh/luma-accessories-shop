@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">User Details</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <strong>Name:</strong>
                            <p class="text-dark">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <strong>Email:</strong>
                            <p class="text-dark">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <strong>Phone:</strong>
                            <p class="text-dark">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-4">
                            <strong>Address:</strong>
                            <p class="text-dark">{{ $user->address ?? 'N/A' }}</p>
                        </div>
                        <div class="col-12 text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
