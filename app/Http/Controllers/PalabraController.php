<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Palabra;

class PalabraController extends Controller
{
    public function evaluaLetra(Request $request)
    {
        $palabra = session('partida')->palabra; 
        $caracter = strtolower($request->input('caracter'));

        $resultado = $palabra->evaluarLetra(
            $caracter, 
            session('partida')->letras_ingresadas, 
            session('partida')->oportunidades_restantes
        );
        session('partida')->letras_ingresadas = $resultado['letras_ingresadas']; 
        session('partida')->oportunidades_restantes = $resultado['oportunidades']; 

        $resultado['juegoTerminado'] = false;
        
        if (session('partida')->oportunidades_restantes <= 0) {
            $resultado['juegoTerminado'] = true;  
            session('partida')->estado = 'derrota'; 
        }

        if ($palabra->comprobarPalabraCompleta(session('partida')->letras_ingresadas)) {
            $resultado['juegoTerminado'] = true; 
            session('partida')->estado = 'victoria'; 
        } else {
            $resultado['letras_incorrectas'] = session('partida')->palabra->getLetrasNoAcertadas($resultado['letras_ingresadas']);
        }

        $resultado['estadoPartida'] = session('partida')->estado;
        $resultado['palabraJuego'] = $palabra->palabra;  
        return response()->json($resultado);
    }

    public function evaluarPalabra(Request $request)
    {
        $palabraIngreso = $request->input('palabraIngreso'); 
        $response = session('partida')->palabra->verificarPalabra($palabraIngreso);
        return response()->json($response);
    }
}
