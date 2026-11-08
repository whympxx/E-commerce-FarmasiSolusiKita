<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product Catalog Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// Shopping Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

// Checkout & customer orders
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Portal pelanggan
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Product Management
    Route::post('products/bulk-update-stock', [App\Http\Controllers\Admin\ProductController::class, 'bulkUpdateStock'])->name('products.bulk-update-stock');
    Route::post('products/{product}/toggle-featured', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);

    // Category Management
    Route::patch('categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);

    // Order Management (route statis harus sebelum resource, kalau tidak "export" dibaca sebagai ID)
    Route::get('orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::get('orders/{order}/invoice', [App\Http\Controllers\Admin\OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/{order}/shipping-label', [App\Http\Controllers\Admin\OrderController::class, 'shippingLabel'])->name('orders.shipping-label');
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class);

    // Customer Management
    Route::get('customers/export', [App\Http\Controllers\Admin\CustomerController::class, 'export'])->name('customers.export');
    Route::patch('customers/{customer}/toggle-verification', [App\Http\Controllers\Admin\CustomerController::class, 'toggleVerification'])->name('customers.toggle-verification');
    Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);

    // Reports Management
    Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index')->middleware('throttle:10,1');
    Route::get('reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export')->middleware('throttle:5,1');
    Route::get('reports/chart-data', [App\Http\Controllers\Admin\ReportController::class, 'getChartData'])->name('reports.chart-data')->middleware('throttle:20,1');

    // Settings Management
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings/update', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/backup', [App\Http\Controllers\Admin\SettingController::class, 'backup'])->name('settings.backup');
    Route::post('settings/clear-cache', [App\Http\Controllers\Admin\SettingController::class, 'clearCache'])->name('settings.clear-cache');
});

require __DIR__.'/auth.php';
