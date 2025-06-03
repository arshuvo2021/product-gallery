@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Product Gallery</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Add New Product
    </a>
</div>

<div class="row">
    @foreach ($products as $product)
        <div class="col-md-4">
            <div class="card mb-4">
                @if($product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body">
                    <h5>{{ $product->name }}</h5>
                    <p>{{ Str::limit($product->description, 80) }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                    <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete product?')" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
