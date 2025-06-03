<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductImage;

class ProductModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_has_many_images()
    {
        $product = Product::factory()->create();
        $images = ProductImage::factory(3)->create(['product_id' => $product->id]);

        $this->assertCount(3, $product->images);
        $this->assertInstanceOf(ProductImage::class, $product->images->first());
    }

    public function test_product_image_belongs_to_product()
    {
        $product = Product::factory()->create();
        $image = ProductImage::factory()->create(['product_id' => $product->id]);

        $this->assertInstanceOf(Product::class, $image->product);
        $this->assertEquals($product->id, $image->product->id);
    }
}
