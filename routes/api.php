<?php

namespace App\Http\Controllers\Api;

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function () {
    // login
    Route::post('/login', [LoginController::class, 'index', ['as' => 'admin']]);
    // middleware
    Route::group(['middleware' => 'auth:api_admin'], function () {
        // user
        Route::get('/user', [LoginController::class, 'getUser', ['as' => 'admin']]);
        // refresh Token
        Route::get('/refresh', [LoginController::class, 'refreshToken', ['as' => 'admin']]);
        // Logout
        Route::post('/logout', [LoginController::class, 'logout', ['as' => 'admin']]);
        // Logout
        Route::get('/dashboard', [DashboardController::class, 'index', ['as' => 'admin']]);
        // Category
        Route::apiResource('/categories', CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
        // product
        Route::apiResource('/products', ProductController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
        // invoice 
        Route::apiResource('/invoices', InvoiceController::class, ['except' => ['store, create, delete, update, edit'], 'as' => 'admin']);
        // customer
        Route::get('/customers', [CustomerController::class, 'index', ['as' => 'admin']]);
        // Slider 
        Route::apiResource('/sliders', SliderController::class, ['except' => ['show, edit, update, create'], 'as' => 'admin']);
        // User 
        Route::apiResource('/users', UserController::class, ['except' => ['edit, update'], 'as' => 'admin']);
    });
});