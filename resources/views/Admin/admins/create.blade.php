@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Create New Admin</h6>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admins.store') }}">
                        @csrf
                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control border-bottom" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control border-bottom" required>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control border-bottom" required>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control border-bottom" required>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn bg-gradient-dark">Create Admin</button>
                            <a href="{{ route('admins') }}" class="btn btn-outline-dark">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
