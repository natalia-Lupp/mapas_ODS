<?php

use App\Controllers\AuthController;
use App\Controllers\Admin\HomeController;
use App\Controllers\Admin\BuildingController;
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
        Route::get('/admin', [HomeController::class, 'dashboard'])->name('admin.dashboard');

        // Buildings ----------------------------------------------------------------------------
        Route::get('/admin/buildings', [BuildingController::class, 'index'])->name('admin.buildings.index');

        // Create Building
        Route::get('/admin/buildings/new', [BuildingController::class, 'new'])->name('admin.buildings.new'); // rota nova
        Route::post('/admin/buildings', [BuildingController::class, 'create'])->name('admin.buildings.create');

        // Retrieve Building
        Route::get('/admin/buildings/page/{page}', [BuildingController::class, 'index'])->name('admin.buildings.paginate');
        Route::get('/admin/buildings/{id}', [BuildingController::class, 'show'])->name('admin.buildings.show'); // usar essa 

        // Update Building
        Route::get('/admin/buildings/buildings.list', [BuildingController::class, 'new'])->name('admin.buildings.new'); // rota nova
        Route::get('/admin/buildings/{id}/edit', [BuildingController::class, 'edit'])->name('admin.buildings.edit');
        Route::put('/admin/buildings/{id}', [BuildingController::class, 'update'])->name('admin.buildings.update');

        // Delete Building
        Route::delete('/admin/buildings/{id}', [BuildingController::class, 'destroy'])->name('admin.buildings.destroy');
    });

    // User Routes
    Route::middleware('client')->group(function () {
        Route::get('/client', [HomeController::class, 'dashboardClient'])->name('dashboard.client');
    });
});
