<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Palabra;

class PalabraController extends Controller
{
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
            $resultado['letras_incorrectas'] = session('partida')->palabra->getLetrasNoAcertadas($resultado['letras_ingresadas']);
        }

        $resultado['estadoPartida'] = session('partida')->estado; 
        return response()->json($resultado);
    }

    public function evaluarPalabra(Request $request)
    {
        $palabraIngreso = $request->input('palabraIngreso'); 
        $response = session('partida')->palabra->verificarPalabra($palabraIngreso);
        return response()->json($response);
    }
}
