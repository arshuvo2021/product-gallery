<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_cannot_upload_invalid_image_types()
    {
        $images = [
            UploadedFile::fake()->create('document.pdf', 100),
            UploadedFile::fake()->create('document.pdf', 100),
            UploadedFile::fake()->create('document.pdf', 100),
        ];

        $response = $this->post(route('products.store'), [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'images' => $images
        ]);

        $response->assertSessionHasErrors('images.*');
    }

    public function test_can_update_product()
    {
        $product = Product::factory()->create();
        ProductImage::factory(3)->create(['product_id' => $product->id]);

        $response = $this->put(route('products.update', $product), [
            'name' => 'Updated Product',
            'description' => 'Updated Description'
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'description' => 'Updated Description'
        ]);
    }

    public function test_can_delete_product_and_its_images()
    {
        $product = Product::factory()->create();
        $images = ProductImage::factory(3)->create(['product_id' => $product->id]);

        $response = $this->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
        $this->assertDatabaseMissing('product_images', ['product_id' => $product->id]);

        foreach ($images as $image) {
            Storage::disk('public')->assertMissing($image->image_path);
        }
    }

    public function test_cannot_remove_images_if_less_than_three_remain()
    {
        $product = Product::factory()->create();
        $images = ProductImage::factory(3)->create(['product_id' => $product->id]);

        $response = $this->put(route('products.update', $product), [
            'name' => $product->name,
            'description' => $product->description,
            'remove_images' => [$images[0]->id, $images[1]->id]
        ]);

        $response->assertSessionHasErrors('images');
        $this->assertDatabaseHas('product_images', ['id' => $images[0]->id]);
        $this->assertDatabaseHas('product_images', ['id' => $images[1]->id]);
    }

    public function test_can_add_new_images_to_existing_product()
    {
        $product = Product::factory()->create();
        ProductImage::factory(3)->create(['product_id' => $product->id]);

        $newImage = UploadedFile::fake()->image('new-product.jpg');

        $response = $this->put(route('products.update', $product), [
            'name' => $product->name,
            'description' => $product->description,
            'images' => [$newImage]
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertCount(4, $product->fresh()->images);
        Storage::disk('public')->assertExists($product->fresh()->images->last()->image_path);
    }
}
