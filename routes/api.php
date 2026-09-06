<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\BrandController;
use Illuminate\Support\Facades\Route;

//GET
Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{route}', [ProductController::class, 'show']);

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{route}', [BrandController::class, 'show']);


//POST
Route::post('products/search', [ProductController::class, 'search']);

Route::post('/auth/request-code', [AuthController::class, 'requestCode'])
    ->middleware('throttle:verification-code');
Route::post('/auth/verify-code', [AuthController::class, 'verifyCode'])
    ->middleware('throttle:code-verification');


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/me', [AuthController::class, 'updateProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

});
