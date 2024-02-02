<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Palabra;
use Barryvdh\Debugbar\LaravelDebugbar;

class PartidaController extends Controller
{
    public function index_interrumpidos()
    {
        $partidas_interrumpidas = Auth::user()->partidas->where('estado', '=', 'interrumpida'); 
        return view('interrumpidos', ['partidas' => $partidas_interrumpidas]);
    } 
    public function show(string $idPartida){
        $partida = Partida::findOrFail($idPartida);
        return view('juego', ['partida' => $partida]);
    }

    public function evaluaLetra(Request $request)
    {
        $palabra = strtolower($request->input('palabra'));
        $caracter = strtolower($request->input('caracter'));

        $resultado = Palabra::evaluarLetra($palabra, $caracter);
        if(!$resultado['victoria']) // quedan oportunidades restantes 
        {
            if(Palabra::verificarVictoria($palabra, $resultado['letras_ingresadas']))
            {
                $resultado['victoria'] = true; 
            }else{
                $resultado['letras_incorrectas'] = Palabra::getLetrasNoAcertadas($palabra, $resultado['letras_ingresadas']);    
            }

        }
        return response()->json($resultado);
    }

}
