<?php

use App\Controllers\Admins\BathroomImagesController;
use App\Controllers\Admins\BathroomItemTypeController;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\Admins\HomeController as AdminHomeController;
use App\Controllers\Users\HomeController as UserHomeController;
use App\Controllers\Admins\BuildingsController;
use App\Controllers\Admins\BathroomsController;
use App\Controllers\Admins\ItemsBathroomController;
use App\Controllers\Admins\MaintenanceController;
use Codeception\Command\Run;
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

      //itens Routes---------------------------------------------------------------------------------
        Route::get(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items',
            [ItemsBathroomController::class, 'index']
        )->name('admin.buildings.bathrooms.items.index');

      // Create Bathroom items
        Route::get(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items/new',
            [ItemsBathroomController::class, 'new']
        )->name('admin.buildings.bathrooms.items.new');

        Route::post(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items',
            [ItemsBathroomController::class, 'create']
        )->name('admin.buildings.bathrooms.items.create');

      // Update Bathroom items
        Route::get(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items/{id}/edit',
            [ItemsBathroomController::class, 'edit']
        )->name('admin.buildings.bathrooms.items.edit');

        Route::get(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items/{item_id}',
            [ItemsBathroomController::class, 'show']
        )->name('admin.buildings.bathrooms.items.show');

        Route::put(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items/{id}',
            [ItemsBathroomController::class, 'update']
        )->name('admin.buildings.bathrooms.items.update');

      // Delete Bathroom items
        Route::delete(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/items/{id}',
            [ItemsBathroomController::class, 'destroy']
        )->name('admin.buildings.bathrooms.items.destroy');

      // Bathroom Item Types
        Route::get(
          '/admin/bathroom_item_types',
          [BathroomItemTypeController::class, 'index']
        )->name('admin.bathroom_item_types.index');

        Route::get(
          '/admin/bathroom_item_types/{id}',
          [BathroomItemTypeController::class, 'show']
        )->name('admin.bathroom_item_types.show');

        Route::get(
          '/admin/bathroom_item_types/{id}/edit',
          [BathroomItemTypeController::class, 'edit']
        )->name('admin.bathroom_item_types.edit');

        Route::get(
          '/admin/bathroom_item_types/new',
          [BathroomItemTypeController::class, 'new']
        )->name('admin.bathroom_item_types.new');

        Route::post(
          '/admin/bathroom_item_types',
          [BathroomItemTypeController::class, 'create']
        )->name('admin.bathroom_item_types.create');

        Route::put(
          '/admin/bathroom_item_types/{id}',
          [BathroomItemTypeController::class, 'update']
        )->name('admin.bathroom_item_types.update');

        Route::delete(
          '/admin/bathroom_item_types/{id}',
          [BathroomItemTypeController::class, 'destroy']
        )->name('admin.bathroom_item_types.destroy');

      //Bathroom Routes-----------------------------------------------------------------------
        Route::get('/admin/buildings/bathrooms', [BathroomsController::class, 'index'])
        ->name('admin.buildings.bathrooms.index');

        Route::get('/admin/buildings/{building_id}/bathrooms', [BathroomsController::class, 'index'])
        ->name('admin.buildings.bathrooms.index');

      // Create Bathroom
        Route::get('/admin/buildings/{building_id}/bathrooms/new', [BathroomsController::class, 'new'])
        ->name('admin.buildings.bathrooms.new');

        Route::get('/admin/buildings/{building_id}/bathrooms/new', [BathroomsController::class, 'new'])
        ->name('admin.buildings.bathrooms.new');

        Route::post('/admin/buildings/{building_id}/bathrooms', [BathroomsController::class, 'create'])
        ->name('admin.buildings.bathrooms.create');

      // Update Bathroom
        Route::get('/admin/buildings/{building_id}/bathrooms/{id}/edit', [BathroomsController::class, 'edit'])
        ->name('admin.buildings.bathrooms.edit');

        Route::put('/admin/buildings/{building_id}/bathrooms/{id}', [BathroomsController::class, 'update'])
        ->name('admin.buildings.bathrooms.update');

        Route::get('/admin/buildings/{building_id}/bathrooms/{id}', [BathroomsController::class, 'show'])
        ->name('admin.buildings.bathrooms.show');

      // Delete Bathroom
        Route::delete('/admin/buildings/{building_id}/bathrooms/{id}', [BathroomsController::class, 'destroy'])
        ->name('admin.buildings.bathrooms.destroy');

      // Bathroom images ----------------------------------------------------------------------------
        Route::post('/admin/buildings/{building_id}/bathrooms/{id}/images', [BathroomImagesController::class, 'create'])
        ->name('admin.buildings.bathrooms.images.create');

      // Bathroom images delete
        Route::delete(
            '/admin/buildings/{building_id}/bathrooms/{bathroom_id}/images/{image_id}',
            [BathroomImagesController::class, 'destroy']
        )->name('admin.buildings.bathrooms.images.destroy');

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
