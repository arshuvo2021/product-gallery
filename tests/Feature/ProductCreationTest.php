<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Product;

class ProductCreationTest extends TestCase
{
    use RefreshDatabase;

    public function a_product_can_be_created_with_minimum_three_images()
    {
        Storage::fake('public');

        $response = $this->post('/products', [
            'name' => 'Test Product',
            'description' => 'This is a test description.',
            'images' => [
                UploadedFile::fake()->image('img1.jpg'),
                UploadedFile::fake()->image('img2.jpg'),
                UploadedFile::fake()->image('img3.jpg'),
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
        $this->assertCount(3, Product::first()->images);
    }

    public function it_fails_to_create_product_with_less_than_three_images()
    {
        Storage::fake('public');

        $response = $this->from('/products/create')->post('/products', [
            'name' => 'Invalid Product',
            'description' => 'Missing images',
            'images' => [
                UploadedFile::fake()->image('img1.jpg'),
            ],
        ]);

        $response->assertRedirect('/products/create');
        $response->assertSessionHasErrors('images');
    }
}
