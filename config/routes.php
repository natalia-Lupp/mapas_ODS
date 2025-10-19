<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\BuildingController;
use Core\Router\Route;
use App\Controllers\TemporariaController;

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
        Route::get('/buildings', [BuildingController::class, 'index'])->name('buildings.index');

        // Create Building
        //Route::get('/buildings/new', [ProblemsController::class, 'new'])->name('problems.new');
        Route::post('/buildings', [BuildingController::class, 'create'])->name('buildings.create');

        // Retrieve Building
        Route::get('/buildings/new', [BuildingController::class, 'new'])->name('buildings.new'); // rota nova
        Route::get('/buildings/page/{page}', [BuildingController::class, 'index'])->name('buildings.paginate');
        Route::get('/buildings/{id}', [BuildingController::class, 'show'])->name('buildings.show'); // usar essa 

        // Update Building
        Route::get('/buildings/{id}/edit', [BuildingController::class, 'edit'])->name('buildings.edit');
        Route::put('/buildings/{id}', [BuildingController::class, 'update'])->name('buildings.update');

        // Delete Building
        Route::delete('/buildings/{id}', [BuildingController::class, 'destroy'])->name('buildings.destroy');
    });

    // User Routes
    Route::middleware('client')->group(function () {
        Route::get('/client', [HomeController::class, 'dashboardClient'])->name('dashboard.client');
    });
});

// Rotas temporária para teste do componente
Route::get('/components/navbar.admin', [TemporariaController::class, 'navbarADM']);
Route::get('/components/sidenav.admin', [TemporariaController::class, 'sidenavADM']);
Route::get('/components/navbar.user', [TemporariaController::class, 'navbarUser']);
Route::get('/components/sidenav.user', [TemporariaController::class, 'sidebarUser']);
Route::get('/buildings/new', [TemporariaController::class, 'newBuilding']);
Route::get('/buildings/new.floor', [TemporariaController::class, 'newFloor']);
Route::get('/buildings/new.itens', [TemporariaController::class, 'newitens']);
Route::get('/buildings/edit', [TemporariaController::class, 'edit']);
Route::get('/buildings/relatorio/pdf', [TemporariaController::class, 'gerarPDF']);
