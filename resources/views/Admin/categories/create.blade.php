 @extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Create New Category</h6>
                    </div>
                </div>
                <div class="card-body px-4 pb-2">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

               <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="input-group input-group-static mb-4">
        <label>Category Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="input-group input-group-static mb-4">
        <label>Category Image</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>

    <div class="text-end">
        <button type="submit" class="btn bg-gradient-dark mb-0">Create</button>
        <a href="{{ route('categories.index') }}" class="btn btn-outline-dark mb-0">Cancel</a>
    </div>
</form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection


