<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

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
