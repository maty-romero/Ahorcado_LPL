<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PalabraController;
use App\Http\Controllers\PartidaController;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/auth.php';

// URL de la aplicacion: http://ahorcadoapp.test/

Route::middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/interrumpidos', [PartidaController::class, 'index_interrumpidos'])->name('index_interrumpidos');
    Route::get('/partida/{idPartida}', [PartidaController::class, 'show'])->name('show');
    
    Route::post('/evaluarLetra', [PalabraController::class, 'evaluaLetra'])->name('ingresoLetra');
    Route::post('/evaluarPalabra', [PalabraController::class, 'evaluarPalabra'])->name('ingresoPalabra');

    Route::post('/finalizarPartida', [PartidaController::class, 'finalizarPartida'])->name('finPartida');
    Route::delete('/eliminarPartida', [PartidaController::class, 'eliminarPartida'])->name('deletePartida');
    Route::delete('/eliminarInterrumpidas', [PartidaController::class, 'eliminarPartidasInterrumpidas'])->name('deleteInterrumpidas');

    Route::post('/iniciarPartida', [PartidaController::class, 'iniciarPartida'])->name('inicioPartida');

});






