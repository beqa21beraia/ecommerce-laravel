<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AddressController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::post('/auth/request-code', [AuthController::class, 'requestCode'])
    ->middleware('throttle:verification-code');

Route::post('/auth/verify-code', [AuthController::class, 'verifyCode'])
    ->middleware('throttle:code-verification');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'updateProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/addresses', [AddressController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::put('/addresses/{address}', [AddressController::class, 'update']);
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

Route::get('/categories', [CategoryController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Brands
|--------------------------------------------------------------------------
*/

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{route}', [BrandController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/products', [ProductController::class, 'index']);
Route::post('/products/search', [ProductController::class, 'search']);
Route::get('/products/{route}', [ProductController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/

Route::middleware('auth.optional')->group(function () {
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::put('/cart/items', [CartController::class, 'updateItem']);
    Route::delete('/cart/items', [CartController::class, 'removeItem']);
});
