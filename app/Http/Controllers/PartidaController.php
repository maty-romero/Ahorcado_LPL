<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida; 
use Illuminate\Support\Facades\Auth; 

class PartidaController extends Controller
{
    public function index_interrumpidos()
    {
        Auth::user()->partidas->where('estado', '=', 'interrumpida')->get(); 
        $partidas_interrumpidas = Partida::where('estado', '=', 'interrumpida')->get();
        return view('interrumpidos', ['partidas' => $partidas_interrumpidas]);
    } 
    public function show(string $idPartida){
        $partida = Partida::findOrFail($idPartida);
        return view('juego', ['partida' => $partida]);
    }
}
