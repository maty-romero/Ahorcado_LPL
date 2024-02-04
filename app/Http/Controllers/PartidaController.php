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
        session()->flush();
        return view('juego', ['partida' => $partida]);
    }

    public function evaluaLetra(Request $request)
    {
        $palabra = strtolower($request->input('palabra'));
        $caracter = strtolower($request->input('caracter'));

        $resultado = Palabra::evaluarLetra($palabra, $caracter);

        $resultado['juegoTerminado'] = false;
        
        if (session('partida')->oportunidades_restantes <= 0) {
            $resultado['juegoTerminado'] = true;  
            session('partida')->estado = 'derrota'; 
        }

        if (Palabra::verificarVictoria($palabra, $resultado['letras_ingresadas'])) {
            $resultado['juegoTerminado'] = true; 
            session('partida')->estado = 'victoria'; 
        } else {
            $resultado['letras_incorrectas'] = Palabra::getLetrasNoAcertadas($palabra, $resultado['letras_ingresadas']);
        }

        return response()->json($resultado);
    }

    public function finalizarPartida()
    {
        $partida = session('partida');

        if (!$partida) {
            return;
        } 
        $tiempoInicio = session('hora_inicio_juego'); // comienzo partida
        $tiempoFin = time();
        $partida->guardarPartida($tiempoInicio, $tiempoFin);

        $partidasRanking = [];
        if($partida->estado = 'derrota')
        {
            $dificultadPartida = $partida->palabra->dificultad->nombre_dificultad; 
            $partidasRanking = Partida::getRankingJugadoresRapidos($dificultadPartida);  
        }
        session()->flush(); 
        return View('resultado', ['partida' => $partida, 'partidasRanking' => $partidasRanking]); 
    }
    
    public function interrumpirPartida()
    {
        $partida = session('partida');

        if (!$partida) {
            return;
        } 
        $partida->estado = 'interrumpida'; 
        session()->put('partida', $partida); 
        $this->finalizarPartida();
    }
}
