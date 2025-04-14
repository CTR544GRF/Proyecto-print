<?php

use App\Http\Controllers\AutorizacionesController;
use App\Http\Controllers\Api\FingerprintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Autorizaciones;
use Illuminate\Support\Facades\Route;

// Ruta para la vista de login
Route::get('/', function () {
    return view('auth.login');
})->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AutorizacionesController::class, 'index'])->name('dashboard'); // ← Agrega esta ruta para cargar las autorizaciones
    Route::get('/autorizaciones/crear', [AutorizacionesController::class, 'create'])->name('autorizaciones.create');
    Route::post('/autorizaciones', [AutorizacionesController::class, 'store'])->name('autorizaciones.store');

    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');

// Rutas para el sensor de huellas
    Route::post('/capturar-huella', [UserController::class, 'captureFingerprint']);
    
});


require __DIR__.'/auth.php';
