@extends('layouts.app')

@section('content')
    <h1>Edit Product: {{ $product->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Product Images (at least 3 total)</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/jpeg,image/png,image/webp">
            <small class="form-text text-muted">Only jpeg, png, webp. Max size 2MB each.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Existing Images:</label>
            <div class="row">
                @foreach ($product->images as $image)
                    <div class="col-md-3">
                        <div class="card mb-2">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top" style="height: 100px; object-fit: cover;">
                            <div class="card-body text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_images[]" value="{{ $image->id }}" id="remove-{{ $image->id }}">
                                    <label class="form-check-label" for="remove-{{ $image->id }}">
                                        Remove
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
             @if($product->images->count() < 3)
                 <p class="text-danger mt-2">Warning: You currently have less than 3 images. Please add more.</p>
             @endif
        </div>


        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>

    </form>
@endsection 