<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Storage::disk('public')->makeDirectory('products');

        Product::factory(5)->create()->each(function ($product) {
            for ($i = 1; $i <= 3; $i++) {
                $imageUrl = "https://picsum.photos/640/480?random=" . rand(1, 10000);
                $imageContents = file_get_contents($imageUrl);

                $imageName = 'products/' . uniqid() . '.jpg';

                Storage::disk('public')->put($imageName, $imageContents);

                $product->images()->create([
                    'image_path' => $imageName
                ]);
            }
        });
    }
}
