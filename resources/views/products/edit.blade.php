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
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', $product->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                      required>{{ old('description', $product->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Product Images (at least 3 total)</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple
                   accept="image/jpeg,image/png,image/webp">
            <small class="form-text text-muted">Only jpeg, png, webp. Max size 2MB each.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Existing Images:</label>
            <div class="row" id="image-gallery">
                @foreach ($product->images as $image)
                    <div class="col-md-3">
                        <div class="card mb-2">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                 style="height: 100px; object-fit: cover;">
                            <div class="card-body text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_images[]"
                                           value="{{ $image->id }}" id="remove-{{ $image->id }}">
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

    <hr>

    <h5 class="mt-4">Upload New Image (AJAX)</h5>
    <input type="file" id="ajax-image" class="form-control mb-2" accept="image/jpeg,image/png,image/webp">
    <div id="ajax-upload-status" class="text-success mb-2"></div>

    <h5 class="mt-4">Or Drag & Drop Upload</h5>
    <form action="{{ route('products.images.upload', $product) }}"
          class="dropzone border border-2 p-3"
          id="dropzoneForm">
        @csrf
    </form>
@endsection

@section('scripts')
<script>
    // Handle AJAX upload
    document.getElementById('ajax-image').addEventListener('change', function () {
        let file = this.files[0];
        if (!file) return;

        let formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route('products.images.upload', $product) }}', {
            method: 'POST',
            body: formData
        }).then(res => res.json())
          .then(data => {
              document.getElementById('ajax-upload-status').innerText = data.message;
              if (data.image_url && data.image_id) {
                  addImageToGallery(data.image_url, data.image_id);
              }
          })
          .catch(err => {
              document.getElementById('ajax-upload-status').innerText = 'Upload failed.';
          });
    });

    // Dropzone config
    Dropzone.options.dropzoneForm = {
        paramName: 'image',
        maxFilesize: 2, // MB
        acceptedFiles: 'image/jpeg,image/png,image/webp',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        success: function (file, response) {
            alert('Image uploaded successfully!');
            if (response.image_url && response.image_id) {
                addImageToGallery(response.image_url, response.image_id);
            }
        },
        error: function (file, response) {
            console.error('Upload failed:', response);
            alert('Image upload failed.');
        }
    };

    // Add image preview to gallery
    function addImageToGallery(url, id) {
        const col = document.createElement('div');
        col.className = 'col-md-3';
        col.innerHTML = `
            <div class="card mb-2">
                <img src="${url}" class="card-img-top" style="height: 100px; object-fit: cover;">
                <div class="card-body text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_images[]" value="${id}" id="remove-${id}">
                        <label class="form-check-label" for="remove-${id}">Remove</label>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('image-gallery').prepend(col);
    }
</script>
@endsection
