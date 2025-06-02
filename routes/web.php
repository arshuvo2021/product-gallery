<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/', [ProductController::class, 'index']);

Route::resource('products', ProductController::class);

Route::post('/products/{product}/images', [ProductController::class, 'uploadImage'])->name('products.images.upload');

