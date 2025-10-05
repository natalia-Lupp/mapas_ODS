<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use Core\Router\Route;

// Authentication
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/login', [AuthController::class, 'processLogin'])->name('auth.login');

Route::middleware('admin')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/admin', [HomeController::class, 'dashboardAdmin'])->name('dashboard.admin');
    });
});


Route::middleware('client')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('/client', [HomeController::class, 'dashboardClient'])->name('dashboard.client');
    });
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
