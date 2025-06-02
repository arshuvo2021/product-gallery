@extends('layouts.app')

@section('content')
<h2>Add New Product</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
    </div>

    <div class="mb-3">
        <label>Product Images (at least 3)</label>
        <input type="file" name="images[]" class="form-control" multiple required>
        <small class="text-muted">Only jpeg, png, webp. Max size 2MB each.</small>
    </div>

    <button class="btn btn-success">Save Product</button>
</form>
@endsection
