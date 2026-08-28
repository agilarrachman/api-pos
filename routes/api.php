<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\ProductCategoryController;
use App\Http\Controllers\Api\V1\ProductCategoryImageController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::get('/product-categories/options', [ProductCategoryController::class, 'options']);
        Route::apiResource('product-categories', ProductCategoryController::class);
        Route::post('/product-categories/{id}/image', [ProductCategoryImageController::class, 'store']);

        Route::get('/products/options', [ProductController::class, 'options']);
        Route::apiResource('products', ProductController::class);
        Route::post('/products/{id}/image', [ProductImageController::class, 'store']);

        Route::get('/customers/options', [CustomerController::class, 'options']);
        Route::apiResource('customers', CustomerController::class);
    });
});

