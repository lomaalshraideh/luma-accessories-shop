@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Edit User</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- User Information Form -->
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                                </div>
                            </div>

                        </div>

                        <div class="row password-fields">
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>New Password</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static mb-4">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn bg-gradient-dark mb-0">Update User</button>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-dark mb-0">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div> <!-- end .card-body -->
            </div> <!-- end .card -->
        </div> <!-- end .col-12 -->
    </div> <!-- end .row -->
</div> <!-- end .container-fluid -->
@endsection
