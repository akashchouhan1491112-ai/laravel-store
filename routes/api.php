<?php

use App\Http\Controllers\Api\AdminApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CartApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CheckoutApiController;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\StoreApiController;
use App\Http\Controllers\Api\VendorApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - RESTful Multi-Vendor Marketplace Endpoints (v1)
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    // Public Authentication
    Route::post('/auth/register', [AuthApiController::class, 'register']);
    Route::post('/auth/login', [AuthApiController::class, 'login']);

    // Public Marketplace Catalog
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::get('/products/{slug}', [ProductApiController::class, 'show']);
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/{slug}', [CategoryApiController::class, 'show']);
    Route::get('/stores', [StoreApiController::class, 'index']);
    Route::get('/stores/{slug}', [StoreApiController::class, 'show']);

    // Public / Session Cart
    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart/add', [CartApiController::class, 'add']);
    Route::put('/cart/{id}', [CartApiController::class, 'update']);
    Route::delete('/cart/{id}', [CartApiController::class, 'remove']);
    Route::post('/cart/clear', [CartApiController::class, 'clear']);
    Route::post('/cart/coupon', [CartApiController::class, 'applyCoupon']);

    // Checkout
    Route::post('/checkout', [CheckoutApiController::class, 'checkout']);

    // Authenticated Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/user', [AuthApiController::class, 'user']);
        Route::post('/auth/logout', [AuthApiController::class, 'logout']);

        // Customer Endpoints
        Route::get('/customer/orders', [CustomerApiController::class, 'orders']);
        Route::get('/customer/orders/{orderNumber}', [CustomerApiController::class, 'orderDetail']);
        Route::post('/customer/reviews', [CustomerApiController::class, 'submitReview']);

        // Vendor Endpoints (Vendor or Admin)
        Route::middleware(['role:vendor,admin'])->prefix('vendor')->group(function () {
            Route::get('/dashboard', [VendorApiController::class, 'dashboard']);
            Route::get('/products', [VendorApiController::class, 'products']);
            Route::post('/products', [VendorApiController::class, 'storeProduct']);
            Route::put('/products/{product}', [VendorApiController::class, 'updateProduct']);
            Route::delete('/products/{product}', [VendorApiController::class, 'deleteProduct']);
            Route::get('/orders', [VendorApiController::class, 'orders']);
            Route::put('/orders/{id}/status', [VendorApiController::class, 'updateOrderItemStatus']);
            Route::get('/payouts', [VendorApiController::class, 'payouts']);
            Route::post('/payouts/request', [VendorApiController::class, 'requestPayout']);
        });

        // Admin Endpoints
        Route::middleware(['role:admin'])->prefix('admin')->group(function () {
            Route::get('/dashboard', [AdminApiController::class, 'dashboard']);
            Route::get('/vendors', [AdminApiController::class, 'vendors']);
            Route::put('/vendors/{id}/status', [AdminApiController::class, 'updateVendorStatus']);
            Route::get('/payouts', [AdminApiController::class, 'payouts']);
            Route::put('/payouts/{id}', [AdminApiController::class, 'processPayout']);
        });
    });
});
