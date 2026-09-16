<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.attempt');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/test', function () {
        return view('admin.test');
    });
});
