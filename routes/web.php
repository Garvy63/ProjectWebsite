<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;  


// Routes lainnya...

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

    
});
// routes/web.php

