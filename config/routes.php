<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth/login', [AuthController::class, 'processLogin'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');


use App\Controllers\PreviewController;

Route::get('/preview-login', [PreviewController::class, 'login']);
