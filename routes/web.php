<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\StockOutController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\DashboardController;
use App\Models\User;

// TAMU: form login & register
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', fn() => view('auth.register'))->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// AUTH: dashboard, profile, logout
Route::middleware(['auth'])->group(function () {
    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 'me' Anda sudah diubah ke 'profile'
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Management (hanya admin)
    Route::middleware('roles:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', fn() => view('users.create'))->name('users.create');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', fn(User $user) => view('users.edit', compact('user')))->name('users.edit');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
    });

    // Activity History (Admin, Manager, Operator access)
    Route::middleware('roles:admin,manager,operator')->group(function () {
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/export', [ActivityController::class, 'export'])->name('activities.export');
    });

    // Inventory Management Features (Admin, Operator, Owner)
    Route::middleware('roles:admin,operator,owner')->group(function () {
        // Categories Management
        Route::resource('categories', CategoryController::class);
        
        // Products Management
        Route::resource('products', ProductController::class);
        
        // Suppliers Management
        Route::resource('suppliers', SupplierController::class);
        
        // Stock In Management
        Route::resource('stock-ins', StockInController::class);
        
        // Stock Out Management
        Route::resource('stock-outs', StockOutController::class);
    });

    // Owner Features - Reports with PDF Export
    Route::middleware('roles:owner')->group(function () {
        Route::get('/reports/stock-in', [StockInController::class, 'reports'])->name('reports.stock-in');
        Route::get('/reports/stock-out', [StockOutController::class, 'reports'])->name('reports.stock-out');
        Route::get('/reports/stock-in/pdf', [StockInController::class, 'exportPdf'])->name('reports.stock-in.pdf');
        Route::get('/reports/stock-out/pdf', [StockOutController::class, 'exportPdf'])->name('reports.stock-out.pdf');
    });

    // Operator Features - Activity History
    Route::middleware('roles:operator')->group(function () {
        Route::get('/history/stock-in', [StockInController::class, 'history'])->name('history.stock-in');
        Route::get('/history/stock-out', [StockOutController::class, 'history'])->name('history.stock-out');
    });

    // Owner Features (will be added in Phase 3)
    Route::middleware('roles:owner')->group(function () {
        // Enhanced dashboard with charts
        // Stock management overview
        // Reports
    });

    // Admin Features (will be added in Phase 4)
    Route::middleware('roles:admin')->group(function () {
        // System-wide activity logs
    });
});
