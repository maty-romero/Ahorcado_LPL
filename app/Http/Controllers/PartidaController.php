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
            $partida = Partida::crearPartida($idPalabraRandom, Auth::user()->id); // jugador asociado

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

        session('partida')->guardarPartida($tiempoInicio, $tiempoFin);
         
        $this->setCookieUltimaPartida(session('partida')->estado);

        if(session('partida')->estado == 'interrumpida')
        {
            $this->finalizarSesion();
            return to_route('home');
        }

        $palabraAdivinar = session('partida')->palabra->palabra;
        $victoria =  session('partida')->estado == 'victoria'; 
        $dificultadPartida = session('partida')->palabra->dificultad->nombre_dificultad; 

        $partidasRanking = [];
        if(!$victoria)
        {
            $partidasRanking = Partida::obtenerRankingPartidas($dificultadPartida);
        }
        $this->finalizarSesion();
        
        return View('resultado', [
            'victoria' => $victoria,
            'palabra' => $palabraAdivinar, 
            'dificultad' => $dificultadPartida,
            'partidasRanking' => $partidasRanking
        ]);
        
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
    private function finalizarSesion()
    {
        // el resto de la session no se borra para mantener el token autenticacion
        session()->forget('partida');
        session()->forget('hora_inicio_juego');    
    }
    private function setCookieUltimaPartida(string $estadoPartida)
    {
        $valorCookie = $estadoPartida.";". date("d/m/Y");
        $fechaExpiracion = mktime(date('H'), date('i'), date('s'), date('n'), date('j') + 7, date('Y')); // 7 dias desde fecha actual
        setcookie(Auth::user()->username); // delete cookie antigua
        setcookie(Auth::user()->username, $valorCookie, $fechaExpiracion);
    }
}
