<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; 
use App\Http\Controllers\PartidaController; 

// http://ahorcadoapp.test/
/*
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/
require __DIR__.'/auth.php';

//Auth::routes();


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/interrumpidos', [PartidaController::class, 'index_interrumpidos'])->name('index_interrumpidos');
Route::get('/partida/{idPartida}', [PartidaController::class, 'show'])->name('show');

Route::post('/evaluarLetra', [PartidaController::class, 'evaluaLetra'])->name('ingresoLetra');

//Logs 
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);