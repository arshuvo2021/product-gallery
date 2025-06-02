@extends('layouts.app')

@section('content')
    <h2>{{ $product->name }}</h2>

    <p>{{ $product->description }}</p>

    <h5>Images:</h5>
    <div class="row">
        @foreach ($product->images as $image)
            <div class="col-md-4 mb-3">
                <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid rounded shadow">
            </div>
        @endforeach
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
@endsection
