<?php

use App\Controllers\HomeController;
use Core\Router\Route;

// Authentication
Route::get('/', [HomeController::class, 'index'])->name('root');


use App\Controllers\PreviewController;

Route::get('/preview-login', [PreviewController::class, 'login']);
