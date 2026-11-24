<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ProfitShareController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\SupplierPayoutController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CatalogController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\CustomerOrderController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductDetailController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\PwaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Customer Marketplace & PWA Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/product/{code}', [ProductDetailController::class, 'show'])->name('product.show');
Route::get('/about', function () { return view('customer.about'); })->name('about');
Route::get('/categories/{id}/subcategories', function ($id) {
    return response()->json(\App\Models\Category::where('parent_id', $id)->where('is_active', true)->orderBy('sort_order')->orderBy('name')->get());
})->name('categories.subcategories');
Route::get('/offline', [PwaController::class, 'offline'])->name('pwa.offline');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showCustomerLoginForm'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/auth/demo-login', [LoginController::class, 'demoLogin'])->name('auth.demo-login');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Admin Auth
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| Protected Customer Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Shopping Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Customer Orders & Tracking
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderNumber}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderNumber}/upload-proof', [CustomerOrderController::class, 'uploadProof'])->name('orders.upload-proof');
    Route::post('/orders/{orderNumber}/confirm-delivery', [CustomerOrderController::class, 'confirmDelivery'])->name('orders.confirm-delivery');
    Route::post('/orders/{orderNumber}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

    // Profile & Address
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])->name('profile.address.store');
    Route::post('/profile/address/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.address.default');
    Route::delete('/profile/address/{id}', [ProfileController::class, 'destroyAddress'])->name('profile.address.destroy');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Products
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::delete('products/image/{imageId}', [AdminProductController::class, 'deleteImage'])->name('products.image.delete');

    // Orders
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::post('orders/{id}/shipping', [AdminOrderController::class, 'updateShipping'])->name('orders.shipping.update');

    // Payments
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{id}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    // Profit Sharing
    Route::get('profit-sharing', [ProfitShareController::class, 'index'])->name('profit-sharing.index');

    // Supplier Payouts
    Route::get('payouts', [SupplierPayoutController::class, 'index'])->name('payouts.index');
    Route::get('payouts/create', [SupplierPayoutController::class, 'create'])->name('payouts.create');
    Route::post('payouts', [SupplierPayoutController::class, 'store'])->name('payouts.store');
    Route::get('payouts/{id}', [SupplierPayoutController::class, 'show'])->name('payouts.show');

    // Financial Reports & Export
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('settings/shipping/{id}', [SettingController::class, 'updateShippingRate'])->name('settings.shipping.update');
});
