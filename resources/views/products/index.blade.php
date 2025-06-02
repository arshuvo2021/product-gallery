@extends('layouts.app')

@section('content')
<h1>All Products</h1>
<a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Add Product</a>
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
