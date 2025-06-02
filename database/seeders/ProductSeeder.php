<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    Storage::disk('public')->makeDirectory('products');

    Product::factory(10)->create()->each(function ($product) {
        // Add 3 fake images per product
        for ($i = 0; $i < 3; $i++) {
            $fakeImage = fake()->image(storage_path('app/public/products'), 640, 480, null, false);
            $product->images()->create([
                'image_path' => 'products/' . $fakeImage
            ]);
        }
    });
}
}
