<?php

// routes/api.php
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockInController;
use App\Http\Controllers\Api\StockOutController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard',           [DashboardController::class, 'index']);    // ringkasan sesuai role
    // Route::get('/dashboard/widgets',  [DashboardController::class, 'widgets']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-all', [AuthController::class, 'logoutAll']);

    Route::middleware('role:admin')->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/',           [UserController::class, 'index']);
            Route::post('/',          [UserController::class, 'store']);
            Route::get('/{user}',    [UserController::class, 'show']);
            Route::put('/{user}',    [UserController::class, 'update']);
            Route::patch('/{user}/role', [UserController::class, 'updateRole']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
        });

        Route::prefix('activities')->group(function () {
            Route::get('/',          [ActivityController::class, 'index']);       // list + filter + paginate
            Route::get('/recent',   [ActivityController::class, 'recent']);      // untuk widget
            Route::get('/export',   [ActivityController::class, 'export']);      // download csv/xlsx
        });
    });

    Route::middleware('role:owner')->group(function () {
        Route::prefix('reports')->group(function () {
            Route::get('/stock-ins',         [StockInController::class, 'reports']);
            Route::get('/stock-ins/pdf',     [StockInController::class, 'exportPdf']);
            Route::get('/stock-outs',          [StockOutController::class, 'reports']);
            Route::get('/stock-outs/pdf',      [StockOutController::class, 'exportPdf']);
        });
    });

    Route::middleware('role:staff')->group(function () {
        Route::prefix('categories')->group(function () {
            Route::get('/',            [CategoryController::class, 'index']);
            Route::post('/',           [CategoryController::class, 'store']);
            Route::get('/{category}', [CategoryController::class, 'show']);
            Route::put('/{category}', [CategoryController::class, 'update']);
            Route::delete('/{category}', [CategoryController::class, 'destroy']);
        });

        Route::prefix('products')->group(function () {
            Route::post('/',              [ProductController::class, 'store']);
            Route::put('/{product}',     [ProductController::class, 'update']);
            Route::delete('/{product}',  [ProductController::class, 'destroy']);
        });

        Route::prefix('stock-ins')->group(function () {
            Route::get('/',                 [StockInController::class, 'index']);
            Route::get('/reference',       [StockInController::class, 'reference']);
            Route::get('/history',         [StockInController::class, 'history']);
            Route::get('/{stockIn}',       [StockInController::class, 'show'])->whereNumber('stockIn');
            Route::post('/',                [StockInController::class, 'store']);
            Route::put('/{stockIn}',       [StockInController::class, 'update'])->whereNumber('stockIn');
            Route::delete('/{stockIn}',    [StockInController::class, 'destroy'])->whereNumber('stockIn');
        });

        Route::prefix('stock-outs')->group(function () {
            Route::get('/',                  [StockOutController::class, 'index']);
            Route::get('/reference',        [StockOutController::class, 'reference']);
            Route::get('/history',          [StockOutController::class, 'history']);
            Route::get('/{stockOut}',       [StockOutController::class, 'show'])->whereNumber('stockOut');
            Route::post('/',                 [StockOutController::class, 'store']);
            Route::put('/{stockOut}',       [StockOutController::class, 'update'])->whereNumber('stockOut');
            Route::delete('/{stockOut}',    [StockOutController::class, 'destroy'])->whereNumber('stockOut');
        });
    });

    Route::middleware('role:admin,owner,staff')->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('/',               [ProductController::class, 'index']);
            Route::get('/filters',       [ProductController::class, 'filters']);
            Route::get('/{product}',     [ProductController::class, 'show']);
        });
    });
});
