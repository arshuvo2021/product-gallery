<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')->latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'images' => 'required|array|min:3',
        'images.*' => 'image|mimes:jpeg,png,webp|max:2048'
    ]);

    // Create the product
    $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    // Store images
    foreach ($request->file('images') as $image) {
        $path = $image->store('products', 'public');

        $product->images()->create([
            'image_path' => $path
        ]);
    }

    return redirect()->route('products.index')->with('success', 'Product added successfully.');
}


    public function show(Product $product)
    {
        $product->load('images');
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $product->load('images');
        return view('products.edit', compact('product'));
    }

   public function update(Request $request, Product $product)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'images.*' => 'image|mimes:jpeg,png,webp|max:2048',
    ]);

    // Update name/description
    $product->update([
        'name' => $request->name,
        'description' => $request->description,
    ]);

    // Handle new image uploads (optional)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            $product->images()->create([
                'image_path' => $path
            ]);
        }
    }

    // Handle image removals
    if ($request->remove_images) {
        $remainingImages = $product->images()->count() - count($request->remove_images);
        if ($remainingImages < 3) {
            return back()->withErrors(['images' => 'Product must have at least 3 images.']);
        }

        foreach ($request->remove_images as $imageId) {
            $image = $product->images()->find($imageId);
            if ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }
    }

    return redirect()->route('products.index')->with('success', 'Product updated.');
}


    public function destroy(Product $product)
    {
        // Delete all associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Delete the product (this will cascade delete the images from DB)
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }


    public function uploadImage(Request $request, Product $product)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,webp|max:2048',
    ]);

    $path = $request->file('image')->store('products', 'public');

    $image = $product->images()->create([
        'image_path' => $path,
    ]);

    return response()->json([
        'message' => 'Image uploaded successfully.',
        'image_url' => asset('storage/' . $path),
        'image_id' => $image->id,
    ]);
}



}
