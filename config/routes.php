<?php

use App\Controllers\Admins\BathroomImagesController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\Admins\HomeController as AdminHomeController;
use App\Controllers\Users\HomeController as UserHomeController;
use App\Controllers\Admins\BuildingsController;
use App\Controllers\Admins\BathroomsController;
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
    Route::get('/admin', [AdminHomeController::class, 'dashboard'])->name('admin.dashboard');

    //Bathroom Routes-----------------------------------------------------------------------
    Route::get('/admin/bathrooms', [BathroomsController::class, 'index'])
      ->name('admin.bathrooms.index');

    Route::get('/admin/buildings/{building_id}/bathrooms', [BathroomsController::class, 'index'])
      ->name('admin.buildings.bathrooms.index');

    // Create Bathroom
    Route::get('/admin/buildings/{building_id}/bathrooms/new', [BathroomsController::class, 'new'])
      ->name('admin.buildings.bathrooms.new');

    Route::get('/admin/buildings/{building_id}/bathrooms/{id}', [BathroomsController::class, 'show'])
      ->name('admin.buildings.bathrooms.show');

    // Route::get('/admin/bathrooms/new', [BathroomsController::class, 'new'])
    //   ->name('admin.bathrooms.new');

    // Route::post('/admin/bathrooms', [BathroomsController::class, 'create'])
    //   ->name('admin.bathrooms.create');

    // // Update Bathroom
    // Route::get('/admin/bathrooms/{id}/edit', [BathroomsController::class, 'edit'])
    //   ->name('admin.bathrooms.edit');

    // Route::put('/admin/bathrooms/{id}', [BathroomsController::class, 'update'])
    //   ->name('admin.bathrooms.update');

    // Route::get('/admin/bathrooms/{id}', [BathroomsController::class, 'show'])
    //   ->name('admin.bathrooms.show');

    // // Delete Bathroom
    // Route::delete('/admin/bathrooms/{id}', [BathroomsController::class, 'destroy'])
    //   ->name('admin.bathrooms.destroy');

    // Bathroom images ----------------------------------------------------------------------------
    Route::post('/admin/buildings/{building_id}/bathrooms/{id}/images', [BathroomImagesController::class, 'create'])
      ->name('admin.buildings.bathrooms.images.create');

    // Buildings ----------------------------------------------------------------------------
    Route::get('/admin/buildings', [BuildingsController::class, 'index'])->name('admin.buildings.index');

    // Create Building
    Route::get('/admin/buildings/new', [BuildingsController::class, 'new'])->name('admin.buildings.new'); // rota nova
    Route::post('/admin/buildings', [BuildingsController::class, 'create'])->name('admin.buildings.create');

    // Retrieve Building
    Route::get('/admin/buildings/page/{page}', [BuildingsController::class, 'index'])->name('admin.buildings.paginate');
    Route::get('/admin/buildings/{id}', [BuildingsController::class, 'show'])->name('admin.buildings.show'); // usar essa


    // Update Building
    // Route::get('/admin/buildings/buildings.list', [BuildingController::class, 'new'])->name('admin.buildings.new');
    Route::get('/admin/buildings/{id}/edit', [BuildingsController::class, 'edit'])->name('admin.buildings.edit');
    Route::put('/admin/buildings/{id}', [BuildingsController::class, 'update'])->name('admin.buildings.update');

    // Delete Building
    Route::delete('/admin/buildings/{id}', [BuildingsController::class, 'destroy'])->name('admin.buildings.destroy');
  });

  // User Routes
  Route::middleware('client')->group(function () {
    Route::get('/client', [UserHomeController::class, 'dashboard'])->name('client.dashboard');
  });
});
