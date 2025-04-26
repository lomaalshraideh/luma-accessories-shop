@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Edit Category</h6>
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

                    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="input-group input-group-static mb-4">
                            <label>Category Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                        </div>


                        {{-- <div class="input-group input-group-static mb-4">
                            <label>Current Image</label><br>
                            @if($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="Category Image" class="img-thumbnail mt-2" width="150">
                            @else
                                <p class="text-secondary">No image uploaded</p>
                            @endif
                        </div>

                        <div class="input-group input-group-static mb-4">
                            <label>Upload New Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div> --}}

                        <div class="mt-4">
                            <strong>Current Image:</strong>
                            <div class="row mt-2">
                                @if($category->image)
                                    <div class="col-md-3 mb-3">
                                        <div class="border rounded p-2 position-relative">
                                            <img src="{{ asset('storage/' . $category->image) }}"
                                                 class="img-fluid rounded w-100" style="max-height: 150px;" alt="Category Image">

                                            {{-- Delete Image Form --}}
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                  style="position:absolute; top:5px; right:10px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this image?')">×</button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted">No image uploaded.</p>
                                @endif
                            </div>
                        </div>

                        <div class="input-group input-group-static mb-4 mt-3">
                            <label>Upload New Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>




                        <div class="text-end">
                            <button type="submit" class="btn bg-gradient-dark mb-0">Update</button>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-dark mb-0">Cancel</a>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
