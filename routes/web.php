<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\PartidaController; 

// http://ahorcadoapp.test/

require __DIR__.'/auth.php';

//Auth::routes();
/*
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/interrumpidos', [PartidaController::class, 'index_interrumpidos'])->name('index_interrumpidos');
    Route::get('/partida/{idPartida}', [PartidaController::class, 'show'])->name('show');
    Route::post('/evaluarLetra', [PartidaController::class, 'evaluaLetra'])->name('ingresoLetra');
});
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/interrumpidos', [PartidaController::class, 'index_interrumpidos'])->name('index_interrumpidos');
Route::get('/partida/{idPartida}', [PartidaController::class, 'show'])->name('show');

Route::post('/evaluarLetra', [PartidaController::class, 'evaluaLetra'])->name('ingresoLetra');


//Logs 
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);