@extends('layouts.app')

@section('content')

@vite('resources/js/ingresoLetra.js')

{{dump(session('partida')->letras_ingresadas)}}

@php
    if (!session()->has('partida')) {
        session()->put('partida', $partida);
    }

    if (!session()->has('aciertos')) {
        session()->put('aciertos', 0);
    }
@endphp


<div class="container mt-5">
    @php
        echo $partida->palabra->palabra;   
    @endphp
    
    <input type="hidden" id="palabraJuego" value="{{ $partida->palabra->palabra }}">


    <div class="d-flex justify-content-between mb-4">
        <div>
            <span class="font-weight-bold text-dark" style="font-size: 1.8em;">Tiempo jugado:</span> 
            <span id="tiempo-jugado" class="font-weight-bold text-dark" style="font-size: 1.8em;">{{session('partida')->tiempo_jugado}}</span>
        </div>
        <div class="font-weight-bold text-dark" style="font-size: 1.8em;">
            Oportunidades restantes: {{session('partida')->oportunidades_restantes}}
        </div>
    </div>

    <div class="d-flex align-items-start justify-content-center mb-4">
        <div class="mr-md-4 mb-3 mb-md-0" style="margin-right: 60px;">
            <img src="https://play-lh.googleusercontent.com/MDGMJHCm7qwtOw9o8M00ZXVpL-sCTS5z-nVKwveVDsriFmtbSV8eaKYZOfejDyiQJk4=w526-h296-rw" alt="Hangman Image" style="max-width: 100%;">
        </div>
        
        <div class="text-center border border-secondary p-3" style="width: 550px;">
            <div class="border-info mb-3">
                <span class="font-weight-bold text-info" style="font-size: 1.8em;">Información de la partida:</span> 
                <p class="font-weight-bold text-info" style="font-size: 1.7em;" id="idMsjPartida"></p>
            </div>
            <div class="border-top border-warning">
                <span class="font-weight-bold text-warning" style="font-size: 1.8em;">Letras de la palabra:</span>
                <p class="font-weight-bold text-warning mt-2" style="font-size: 3.0em;" id="idLetrasPalabra">
                    @for ($i = 0; $i < strlen(session('partida')->palabra->palabra); $i++)
                        _
                    @endfor
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-3 mb-4 text-center pt-3 border border-info">
        <span class="font-weight-bold text-dark" style="font-size: 1.8em;">Letras ingresadas:</span> 
        <span id="spanLetrasIngresadas" class="text-success" style="font-size: 1.8em;">{{session('partida')->letras_ingresadas}}</span>
    </div>

    <div class="text-center pt-3">
        <a id="ingresarLetra" class="btn btn-primary mr-2" style="font-size: 1.8em;">Ingresar una letra</a>
        <a href="#" class="btn btn-primary mr-2" style="font-size: 1.8em;">Arriesgar una palabra</a>
        <a href="#" class="btn btn-danger mr-2" style="font-size: 1.8em;">Rendirse</a>
        <a href="#" class="btn btn-warning" style="font-size: 1.8em;">Interrumpir juego</a>
    </div>
</div>







@endsection
