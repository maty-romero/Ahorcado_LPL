<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida; 
use Illuminate\Support\Facades\Auth; 
use App\Models\Palabra;
use Barryvdh\Debugbar\LaravelDebugbar;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;
class PartidaController extends Controller
{
    public function iniciarPartida(Request $request)
    {
        $idDificultad = $request->input('dificultad'); 

        try {
            $idPalabraRandom = Palabra::getPalabraRandomificultad($idDificultad);
            $partida = Partida::crearPartida($idPalabraRandom, Auth::user()->id); // con jugador asociado

            $this->inicializarSesion($partida);

            return view('juego', ['partida' => $partida]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Error al iniciar una nueva partida'], 404);
        }

    }

    public function index_interrumpidos()
    {
        $partidas_interrumpidas = Auth::user()->partidas()
        ->where('estado', '=', 'interrumpida')
        ->orderBy('updated_at', 'desc') 
        ->get();        
        return view('interrumpidos', ['partidas' => $partidas_interrumpidas]);
    } 
    public function show(string $idPartida){
        $partida = Partida::findOrFail($idPartida);
        $this->inicializarSesion($partida);
        return view('juego', ['partida' => $partida]);
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

            $partidasInterrumpidas->each(function ($partida) {  // tabla partida
                $partida->delete(); 
            });

            return response()->json(['message' => 'Partidas interrumpidas eliminadas correctamente'], 200);
        
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'No se pudieron eliminar las partidas interrumpidas'], 404);
        }
    }
    public function finalizarPartida(Request $request)
    {
        $nuevoEstado = $request->input('nuevoEstado');
        session('partida')->estado = $nuevoEstado; 

        $tiempoInicio = session('hora_inicio_juego'); // comienzo partida
        $tiempoFin = time();
        Partida::guardarPartida($tiempoInicio, $tiempoFin, session('partida')->id);
        
        //set Cookie ultima partida 
        $valorCookie = session('partida')->estado .";". date("d/m/Y");
        $fechaExpiracion = mktime(date('H'), date('i'), date('s'), date('n'), date('j') + 7, date('Y')); // 7 dias desde fecha actual
        setcookie(Auth::user()->username); // delete cookie antigua
        setcookie(Auth::user()->username, $valorCookie, $fechaExpiracion); 

        if(session('partida')->estado == 'interrumpida')
        {
            session()->forget('partida');
            session()->forget('hora_inicio_juego');
            return to_route('home');
        }

        $partidasRanking = [];
        if(session('partida')->estado == 'derrota')
        {
            $dificultadPartida = session('partida')->palabra->dificultad->nombre_dificultad; 
            $partidasRanking = Partida::obtenerRankingPartidas($dificultadPartida);
        }
        
        return View('resultado', ['partida' => session('partida'), 'partidasRanking' => $partidasRanking]);
        
    }
    private function inicializarSesion($partida)
    {
        if (!session()->has('partida')) {
            session()->put('partida', $partida);
        }
        if (!session()->has('hora_inicio_juego')) {
            $tiempoInicio = time();
            session()->put('hora_inicio_juego', $tiempoInicio);
        }
    }
}
