<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida; 
use Illuminate\Support\Facades\Auth; 

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

    // True: caracter presente en $palabra; False: no esta presente
    public function evaluaLetra(string $palabra, string $caracter): string
    {
        $resultado = false;
        $mensaje = '';

        if (!ctype_alpha($caracter)) {
            // caracter es una letra
            $mensaje = 'El carácter ingresado no es una letra. Reingresa nuevamente!';
        } else {
            $palabra = strtolower($palabra);
            $caracter = strtolower($caracter);

            $resultado = strpos($palabra, $caracter) !== false;
            if ($resultado) {
                $mensaje = "El carácter '$caracter' está presente en la palabra. Bien hecho!";
            } else {
                $mensaje = "El carácter '$caracter' no está presente en la palabra. Sigue participando!";
            }
        }

        $response = [
            'resultado' => $resultado,
            'mensaje' => $mensaje
        ];

        return json_encode($response);
    }

}
