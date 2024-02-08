<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Palabra;
use Barryvdh\Debugbar\LaravelDebugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
class PartidaController extends Controller
{
    public function index_interrumpidos()
    {
        $partidas_interrumpidas = Auth::user()->partidas()
        ->where('estado', '=', 'interrumpida')
        ->orderBy('updated_at', 'desc') // Ordenar por updated_at de forma descendente
        ->get();        
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

        $resultado['estadoPartida'] = session('partida')->estado; 
        return response()->json($resultado);
    }

    public function finalizarPartida(Request $request)
    {
        $nuevoEstado = $request->input('nuevoEstado');
        Log::info("Nuevo estado partida: " .$nuevoEstado);

        session('partida')->estado = $nuevoEstado; 

        $tiempoInicio = session('hora_inicio_juego'); // comienzo partida
        $tiempoFin = time();
        session('partida')->guardarPartida($tiempoInicio, $tiempoFin); 

        if(session('partida')->estado == 'interrumpida')
        {
            session()->forget('partida');
            session()->forget('hora_inicio_juego');
            return to_route('home');
            //('home') 
        }

        $partidasRanking = [];
        if(session('partida')->estado == 'derrota')
        {
            //Log::info('Partida luego de guardar: ' .$partida);
            $dificultadPartida = session('partida')->palabra->dificultad->nombre_dificultad; 
            $partidasRanking = Partida::obtenerRankingPartidas($dificultadPartida);
        }
        
        return View('resultado', ['partida' => session('partida'), 'partidasRanking' => $partidasRanking]);
        
    }
    
    public function evaluarPalabra(Request $request)
    {
        $palabraIngreso = $request->input('palabraIngreso'); 
        $response = session('partida')->palabra->verificarPalabra($palabraIngreso);
        return response()->json($response);
    }

    public function eliminarPartida(Request $request)
    {
        $partidaId = $request->input('partidaId'); 
        $user = Auth::user();
        try {
            $partida = $user->partidas()->where('partida_id', '=', $partidaId)->firstOrFail();
            $user->partidas()->detach($partidaId);
            $partida->delete();
            
            return response()->json(['message' => 'Partida eliminada correctamente'], 200);
        
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudo encontrar la partida con el ID proporcionado'], 404);
        }

    }
    public function eliminarPartidasInterrumpidas()
    {
        $user = Auth::user();
        $partidasInterrumpidas = $user->partidas()->where('estado', 'interrumpida')->get();

        try {
            // Eliminar partidas interrumpidas tabla user_partida
            $user->partidas()->detach($partidasInterrumpidas->pluck('id'));

            // tabla partida
            $partidasInterrumpidas->each(function ($partida) {
                $partida->delete();
            });

            return response()->json(['message' => 'Partidas interrumpidas eliminadas correctamente'], 200);
        
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudieron eliminar las partidas interrumpidas'], 404);
        }
    }

    public function iniciarPartida(Request $request)
    {
        $idDificultad = $request->input('dificultad'); 

        try {
            $idPalabraRandom = Palabra::getPalabraRandomificultad($idDificultad);
            $partida = Partida::crearPartida($idPalabraRandom);
            session()->put('partida', $partida);

            return view('juego', ['partida' => $partida]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Error al iniciar una nueva partida'], 404);
        }

    }

}
