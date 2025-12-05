<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;



// <-- Login -->

Route::get('/', [ProductController::class, 'frontendIndex'])->name('home')->middleware('auth');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/cart/add', [CartController::class, 'add'])->name('cart.addicon');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/add/{product_id}/{quantity}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
Route::get('/orders/{id}', [CartController::class, 'show'])->name('orders.show');


Route::get('/registration', function () {
    return view('login.registration');
})->name('registration');

Route::post('/register', [RegistrationController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);

Route::get('/admindashboard', [AdminController::class, 'index'])
    ->middleware('auth')
    ->name('admin');

    Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Product Routes
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    // Admin
    Route::prefix('admin')->group(function () {
    Route::resource('products', App\Http\Controllers\ProductController::class)->names('admin.products');
    });

});

