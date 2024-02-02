@extends('layouts.app')

@section('content')

@vite('resources/js/ingresoLetra.js')


@php
    if (!session()->has('partida')) {
        session()->put('partida', $partida);
    }

    if (!session()->has('aciertos')) {
        session()->put('aciertos', 0);
    }

    dump(session('partida')); 
@endphp

<div class="container mt-5">
    
    
    <input type="hidden" id="palabraJuego" value="{{ $partida->palabra->palabra }}">


    <div class="d-flex justify-content-between mb-4">
        <div>
            <span class="font-weight-bold text-dark" style="font-size: 1.8em;">Tiempo jugado:</span> 
            <span id="tiempo-jugado" class="font-weight-bold text-dark" style="font-size: 1.8em;">{{session('partida')->tiempo_jugado}}</span>
        </div>
        <div id="idOportunidadesRestantes" class="font-weight-bold text-dark" style="font-size: 1.8em;">
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
                
                @php
                    $palabra = session('partida')->palabra->palabra;
                    $letrasIngresadas = explode(',', session('partida')->letras_ingresadas);
                @endphp

                <p id='idPalabraEnmascarada' class="font-weight-bold text-warning mt-2" style="font-size: 3.0em;" id="idLetrasPalabra">
                    @foreach (str_split($palabra) as $letra)
                        @if (in_array($letra, $letrasIngresadas))
                            {{ $letra }}
                        @else
                            _
                        @endif
                    @endforeach
                </p>
            </div>
        </div>
    </div>
    
    <div class="mt-3 mb-4 text-center pt-3 border border-info">
        <span class="font-weight-bold text-dark" style="font-size: 1.8em;">Letras no acertadas:</span> 
        @php
            $letrasNoAcertadas = session('partida')->palabra->getLetrasNoAcertadas($palabra, session('partida')->letras_ingresadas);
        @endphp

        <span id="spanLetrasNoAcertadas" class="text-success" style="font-size: 1.8em;">{{$letrasNoAcertadas}}</span>
    </div>

    <div class="text-center pt-3">
        <button id="btnIngresarLetra" class="btn btn-primary mr-2" style="font-size: 1.8em;">Ingresar una letra</button>
        <button id="btnArriesgar" class="btn btn-primary mr-2" style="font-size: 1.8em;">Arriesgar una palabra</button>
        <button id="btnRendirse" class="btn btn-danger mr-2" style="font-size: 1.8em;">Rendirse</button>
        <button id="btnInterrumpir" class="btn btn-warning" style="font-size: 1.8em;">Interrumpir juego</button>
    </div>
</div>







@endsection
