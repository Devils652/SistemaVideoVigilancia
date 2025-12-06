<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\CameraController;
use App\Http\Controllers\SupervisorController;

Route::view('/', 'index')->name('index');

// Auth
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- ADMINISTRADOR ---
Route::middleware(['auth', 'no_cache', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::prefix('personal')->name('personal.')->group(function () {
            Route::get('/', [PersonalController::class, 'index'])->name('index');
            Route::get('/create', [PersonalController::class, 'create'])->name('create');
            Route::post('/', [PersonalController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [PersonalController::class, 'edit'])->name('edit');
            Route::put('/{user}', [PersonalController::class, 'update'])->name('update');
            Route::delete('/{user}', [PersonalController::class, 'destroy'])->name('destroy');
            Route::patch('/{user}/toggle', [PersonalController::class, 'toggle'])->name('toggle');
        });

        Route::get('/cameras/multiview', [CameraController::class, 'multiview'])->name('cameras.multiview');
        Route::post('/cameras/group', [CameraController::class, 'storeGroup'])->name('cameras.group.store'); // <--- ESTA ESTABA BIEN
        Route::resource('cameras', CameraController::class);
    });

// --- USUARIO (Guardia) ---
Route::middleware(['auth', 'no_cache', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        Route::prefix('cameras')->name('cameras.')->group(function () {
            Route::get('/multiview', [CameraController::class, 'multiview'])->name('multiview');
            Route::get('/', [CameraController::class, 'index'])->name('index');
            Route::get('/{camera}', [CameraController::class, 'show'])->name('show');
        });
    });

// --- SUPERVISOR (AQUÍ FALTABA LA RUTA) ---
Route::middleware(['auth', 'no_cache', 'role:supervisor'])
    ->prefix('supervisor')
    ->name('supervisor.')
    ->group(function () {
        Route::get('/dashboard', [SupervisorController::class, 'dashboard'])->name('dashboard');

        Route::prefix('cameras')->name('cameras.')->group(function () {
            Route::get('/multiview', [CameraController::class, 'multiview'])->name('multiview');
            
            // --- RUTA NUEVA AGREGADA ---
            Route::post('/group', [CameraController::class, 'storeGroup'])->name('group.store');
            // ---------------------------
            
            Route::get('/', [CameraController::class, 'index'])->name('index');
            Route::get('/create', [CameraController::class, 'create'])->name('create');
            Route::post('/', [CameraController::class, 'store'])->name('store');
            Route::get('/{camera}', [CameraController::class, 'show'])->name('show');
            Route::get('/{camera}/edit', [CameraController::class, 'edit'])->name('edit');
            Route::put('/{camera}', [CameraController::class, 'update'])->name('update');
        });
    });

// --- MANTENIMIENTO ---
Route::middleware(['auth', 'no_cache', 'role:mantenimiento'])
    ->prefix('mantenimiento')
    ->name('mantenimiento.')
    ->group(function () {
        Route::get('/dashboard', function () {
            $totalCameras = \App\Models\Camera::count();
            $offlineCameras = \App\Models\Camera::where('status', false)->count();
            return view('mantenimiento.dashboard', compact('totalCameras', 'offlineCameras'));
        })->name('dashboard');

        Route::get('/cameras/multiview', [CameraController::class, 'multiview'])->name('cameras.multiview');
        Route::resource('cameras', CameraController::class)->except(['destroy', 'create', 'store']);
    });