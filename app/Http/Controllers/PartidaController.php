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
        $tiempoTotalAnterior = $partida->tiempo_jugado; // tiempo anterior jugado
        // tiempo jugado hasta el momento
        $tiempoTotalNuevo = Partida::getNuevoTiempoJugado($tiempoInicio, $tiempoFin, $tiempoTotalAnterior);
        $partida->tiempo_jugado = $tiempoTotalNuevo;

        $actualizado = $partida->actualizarPartida($partida);

        if (!$actualizado) {
            // hubo un error al actualizar 
        } 
        // se ha actualizado la partida con exito

        // Redireccionar a pantalla mensaje 
    }
    

}
