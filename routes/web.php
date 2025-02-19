<?php

use App\Http\Controllers\AutorizacionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Ruta para la vista de login
Route::get('/', function () {
    return view('auth.login');
})->name('login');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', [AutorizacionController::class, 'index']);

});

require __DIR__.'/auth.php';
