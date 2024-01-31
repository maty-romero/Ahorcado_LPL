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

    public function evaluaLetra(Request $request): string
    {
        $palabra = strtolower($request->input('palabra'));
        $caracter = strtolower($request->input('caracter')); 
        $resultado = false;
        $mensaje = '';
        $letras_ingresadas_format = str_replace([' ', ','], '', session('partida')->letras_ingresadas);

        // Convertir el carácter ingresado a UTF-8
        $caracterUtf8 = mb_convert_encoding($caracter, 'UTF-8', mb_detect_encoding($caracter));

        if (strlen($caracterUtf8) > 1 || !ctype_alpha($caracterUtf8)) {
            // El carácter ingresado no es una letra alfabética o es una tecla de control
            $mensaje = 'El carácter ingresado no es válido. Reingresa nuevamente!';
        } elseif (strpos($letras_ingresadas_format, $caracter) !== false) {
            $mensaje = 'Ya has ingresado esa letra. Ingresa otra!';
        } else {
            // El carácter ingresado es una letra alfabética y no ha sido ingresado antes
            $resultado = strpos($palabra, $caracter) !== false;
            $mensaje = $resultado ? "El carácter '$caracter' está presente en la palabra. Bien hecho!" : "El carácter '$caracter' no está presente en la palabra. Sigue participando!";
            session('partida')->letras_ingresadas .= ",$caracter"; 
        }


        $response = [
            'resultado' => $resultado,
            'mensaje' => $mensaje,
            'letras_ingresadas' => session('partida')->letras_ingresadas
        ];
        return json_encode($response);
    }

}
