<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PartidaController;
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/auth.php';

// http://ahorcadoapp.test/

//Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/interrumpidos', [PartidaController::class, 'index_interrumpidos'])->name('index_interrumpidos');
    Route::get('/partida/{idPartida}', [PartidaController::class, 'show'])->name('show');
    
    Route::post('/evaluarLetra', [PartidaController::class, 'evaluaLetra'])->name('ingresoLetra');
    Route::post('/evaluarPalabra', [PartidaController::class, 'evaluarPalabra'])->name('ingresoPalabra');

    Route::post('/finalizarPartida', [PartidaController::class, 'finalizarPartida'])->name('finPartida');
    
    Route::delete('/eliminarPartida', [PartidaController::class, 'eliminarPartida'])->name('deletePartida');
    
    Route::delete('/eliminarInterrumpidas', [PartidaController::class, 'eliminarPartidasInterrumpidas'])->name('deleteInterrumpidas');

});

//Logs 
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);





