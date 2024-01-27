<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida; 

class PartidaController extends Controller
{
    public function index_interrumpidos()
    {
        $partidas_interrumpidas = Partida::where('estado', 'interrumpida');
        return view('interrumpidos', $partidas_interrumpidas);
    } 
}
