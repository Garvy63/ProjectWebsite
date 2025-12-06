<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// <-- Login -->

Route::get('/', function () {
    return view('index.index');
})->name('home')->middleware('auth');

Route::get('/registration', function () {
    return view('login.registration');
})->name('registration');

Route::post('/register', [RegistrationController::class, 'register'])
->name('register');

// Route untuk menampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])
->name('login');

// Route untuk memproses data login (action dari form)
Route::post('/login', [LoginController::class, 'login']);

// Route untuk logout
Route::post('/logout', [LoginController::class, 'logout'])
->name('logout');

// Route GET: Untuk menampilkan formulir
Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
->name('register');

// Route POST: Untuk memproses pengiriman data formulir
Route::post('/register', [RegistrationController::class, 'register']);

Route::get('/admindashboard', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('admin');

//routes cart
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    // PASTIKAN ROUTE INI ADA DAN NAMANYA BENAR
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::put('/update', [CartController::class, 'update'])->name('cart.update'); // Biasanya via AJAX
    Route::delete('/remove/{productId}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply_coupon');
    Route::post('/calculate-shipping', [CartController::class, 'calculateShipping'])->name('cart.calculate_shipping');
});

// Pastikan ada route untuk produk agar bisa diuji:
// Route::get('/products/{product}', ...);


// Route Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Route POST untuk memproses Order
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

// Route Konfirmasi setelah pesanan berhasil
Route::get('/confirmation/{orderId}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');