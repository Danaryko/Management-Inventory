<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


// View register & login
Route::get('/login', function() { return view('auth.login'); })->name('login');
Route::get('/register', function() { return view('auth.register'); })->name('register');

// Proses register & login
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', function() { return view('dashboard'); })->middleware('auth')->name('dashboard');

// Example protected route with multi role
Route::middleware(['auth', 'role:admin'])->get('/admin-only', function () {
    return 'Welcome, admin!';
});
Route::middleware(['auth', 'role:owner'])->get('/owner-only', function () {
    return 'Welcome, owner!';
});
Route::middleware(['auth', 'role:operator'])->get('/operator-only', function () {
    return 'Welcome, operator!';
});

Route::get('/', function () {
    return view('welcome');
});
