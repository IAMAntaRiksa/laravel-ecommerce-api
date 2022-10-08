<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // login
    Route::post('/login', [App\Http\Controllers\Api\Admin\LoginController::class, 'index', ['as' => 'admin']]);
    // middleware Admin
    Route::group(['middleware' => 'auth:api_admin'], function () {
        // user
        Route::get('/user', [App\Http\Controllers\Api\Admin\LoginController::class, 'getUser', ['as' => 'admin']]);
        // refresh Token
        Route::get('/refresh', [App\Http\Controllers\Api\Admin\LoginController::class, 'refreshToken', ['as' => 'admin']]);
        // Logout
        Route::post('/logout', [App\Http\Controllers\Api\Admin\LoginController::class, 'logout', ['as' => 'admin']]);
        // Logout
        Route::get('/dashboard', [App\Http\Controllers\Api\Admin\DashboardController::class, 'index', ['as' => 'admin']]);
        // Category
        Route::apiResource('/categories', App\Http\Controllers\Api\Admin\CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
        // product
        Route::apiResource('/products', App\Http\Controllers\Api\Admin\ProductController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
        // invoice 
        Route::apiResource('/invoices', App\Http\Controllers\Api\Admin\InvoiceController::class, ['except' => ['store, create, delete, update, edit'], 'as' => 'admin']);
        // customer
        Route::get('/customers', [App\Http\Controllers\Api\Admin\CustomerController::class, 'index', ['as' => 'admin']]);
        // Slider 
        Route::apiResource('/sliders', App\Http\Controllers\Api\Admin\SliderController::class, ['except' => ['show, edit, update, create'], 'as' => 'admin']);
        // User 
        Route::apiResource('/users', App\Http\Controllers\Api\Admin\UserController::class, ['as' => 'admin']);
    });
});

Route::prefix('customer')->group(function () {
    // register
    Route::post('/register', [App\Http\Controllers\Api\Customer\RegisterController::class, 'register', ['as' => 'customer']]);
    // register
    Route::post('/login', [App\Http\Controllers\Api\Customer\LoginController::class, 'login', ['as' => 'customer']]);
    // Middleware Customer
    Route::group(['middleware' => 'auth:api_customer'], function () {
        // user
        Route::get('/user', [App\Http\Controllers\Api\Customer\LoginController::class, 'getUser', ['as' => 'customer']]);
        // user
        Route::post('/logout', [App\Http\Controllers\Api\Customer\LoginController::class, 'logout', ['as' => 'customer']]);
        // dashboard
        Route::get('/dashboard', [App\Http\Controllers\Api\Customer\DashboardController::class, 'index', ['as' => 'customer']]);
        // Invoice
        Route::apiResource('/invoices', App\Http\Controllers\Api\Customer\InvoiceController::class, ['except' => ['edit, update, create'], 'as' => 'customer']);
        // Invoice
        Route::post('/reviews', [App\Http\Controllers\Api\Customer\ReviewController::class, 'store', ['as' => 'customer']]);
    });
});

Route::prefix('web')->group(function () {
    //categories 
    Route::get('/categories', App\Http\Controllers\Api\Web\CategoryController::class, 'index', ['as' => 'web']);
    Route::get('/categories/{slug?}', App\Http\Controllers\Api\Web\CategoryController::class, 'show', ['as' => 'web']);
    Route::get('/categoryHeader', App\Http\Controllers\Api\Web\CategoryController::class, 'categoryHeader', ['as' => 'web']);
    // Products 
    Route::apiResource('/products', App\Http\Controllers\Api\Web\ProductController::class, ['except' => ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);
    // slider
    Route::get('/sliders', [App\Http\Controllers\Api\Web\SliderController::class, 'index', ['as' => 'web']]);
    //rajaongkir
    Route::get('/rajaongkir/provinces', [App\Http\Controllers\Api\Web\RajaOngkirController::class, 'getProvinces'], ['as' => 'web']);
    Route::post('/rajaongkir/cities', [App\Http\Controllers\Api\Web\RajaOngkirController::class, 'getCities'], ['as' => 'web']);
    Route::post('/rajaongkir/checkOngkir', [App\Http\Controllers\Api\Web\RajaOngkirController::class, 'checkOngkir'], ['as' => 'web']);
});