<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminPayoutController;
use App\Http\Controllers\Admin\AdminVendorController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\Vendor\VendorDashboardController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorPayoutController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorSettingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Multi-Vendor Marketplace
|--------------------------------------------------------------------------
*/

// Public Marketplace Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
Route::get('/stores/{slug}', [StoreController::class, 'show'])->name('stores.show');

// Shopping Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/coupon/apply', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Authentication Routes & Demo Fast Switcher
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/vendor-register', [AuthController::class, 'showVendorRegister'])->name('vendor.register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/demo-login/{role}', [AuthController::class, 'demoLogin'])->name('demo.login');

// Customer Dashboard Routes (Auth Required)
Route::middleware(['auth'])->prefix('account')->name('customer.')->group(function () {
    Route::get('/orders', [CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [CustomerDashboardController::class, 'orderDetail'])->name('orders.show');
    Route::post('/reviews', [CustomerDashboardController::class, 'storeReview'])->name('reviews.store');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::post('/profile', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
});

// Vendor Portal Routes (Auth + Role Vendor)
Route::middleware(['auth', 'role:vendor,admin'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [VendorSettingsController::class, 'edit'])->name('settings');
    Route::post('/settings', [VendorSettingsController::class, 'update'])->name('settings.update');

    // Vendor store creation if not yet set up
    Route::get('/store/create', [VendorSettingsController::class, 'edit'])->name('store.create');

    // Routes requiring approved vendor status
    Route::middleware(['vendor.approved'])->group(function () {
        Route::resource('products', VendorProductController::class);
        Route::get('/orders', [VendorOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [VendorOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/status', [VendorOrderController::class, 'updateStatus'])->name('orders.status');
        Route::get('/payouts', [VendorPayoutController::class, 'index'])->name('payouts.index');
        Route::post('/payouts/request', [VendorPayoutController::class, 'requestWithdrawal'])->name('payouts.request');
    });
});

// Admin Control Center (Auth + Role Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Vendor Management
    Route::get('/vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/{store}', [AdminVendorController::class, 'show'])->name('vendors.show');
    Route::post('/vendors/{store}/status', [AdminVendorController::class, 'updateStatus'])->name('vendors.status');

    // Category Management
    Route::resource('categories', AdminCategoryController::class);

    // Global Orders Oversight
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');

    // Payout Approvals
    Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/{transaction}/approve', [AdminPayoutController::class, 'approve'])->name('payouts.approve');
    Route::post('/payouts/{transaction}/reject', [AdminPayoutController::class, 'reject'])->name('payouts.reject');
});
