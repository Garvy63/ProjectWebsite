<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderAdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

// ========================================
// PUBLIC ROUTES
// ========================================

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

// ========================================
// AUTHENTICATED USER ROUTES
// ========================================

Route::middleware('auth')->group(function () {
    // Home
    Route::get('/', [ProductController::class, 'frontendIndex'])->name('home');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Products
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/cart/add', [CartController::class, 'add'])->name('cart.addicon');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart/add/{product_id}/{quantity}', [CartController::class, 'add']);
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/confirmation/{orderId}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');
    
    // User Orders (untuk customer melihat pesanan mereka)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order_id}', [OrderController::class, 'show'])->name('orders.show');
});

// ========================================
// ADMIN ROUTES
// ========================================

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin');
    
    // Products Resource
    Route::resource('products', ProductController::class)->names('admin.products');
    
    // Orders CRUD
    Route::prefix('orders')->name('admin.orders.')->group(function () {
        Route::get('/', [OrderAdminController::class, 'index'])->name('index');
        Route::get('/create', [OrderAdminController::class, 'create'])->name('create');
        Route::post('/', [OrderAdminController::class, 'store'])->name('store');
        Route::get('/{order_id}', [OrderAdminController::class, 'show'])->name('show');
        Route::get('/{order_id}/edit', [OrderAdminController::class, 'edit'])->name('edit');
        Route::put('/{order_id}', [OrderAdminController::class, 'update'])->name('update');
        Route::delete('/{order_id}', [OrderAdminController::class, 'destroy'])->name('destroy');
        
        // Additional Actions
        Route::put('/{order_id}/status', [OrderAdminController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{order_id}/payment', [OrderAdminController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
    });
});

// Redirect /admindashboard to /admin/dashboard
Route::get('/admindashboard', function () {
    return redirect()->route('admin');
})->middleware('auth');