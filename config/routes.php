<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\BuildingsController;
use Core\Router\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/login', [AuthController::class, 'processLogin'])->name('auth.login');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    // Route::get('/auth/login', [AuthController::class, 'processLogin'])->name('auth.login');

    // Admin Routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin', [HomeController::class, 'dashboardAdmin'])->name('dashboard.admin');

    // Buildings ----------------------------------------------------------------------------
        Route::get('/buildings', [BuildingsController::class, 'index'])->name('buildings.index');

        // Create Building
        Route::get('/buildings/new', [ProblemsController::class, 'new'])->name('problems.new');
        Route::post('/buildings', [BuildingsController::class, 'create'])->name('buildings.create');

        // Retrieve Building
        Route::get('/buildings/page/{page}', [BuildingsController::class, 'index'])->name('buildings.paginate');
        Route::get('/buildings/{id}', [BuildingsController::class, 'show'])->name('buildings.show');

        // Update Building
        Route::get('/buildings/{id}/edit', [BuildingsController::class, 'edit'])->name('buildings.edit');
        Route::put('/buildings/{id}', [BuildingsController::class, 'update'])->name('buildings.update');

        // Delete Building
        Route::delete('/buildings/{id}', [BuildingsController::class, 'destroy'])->name('buildings.destroy');
    });

    // User Routes
    Route::middleware('client')->group(function () {
            Route::get('/client', [HomeController::class, 'dashboardClient'])->name('dashboard.client');
    });
});
