@extends('layouts.app')

@section('content')

@vite('resources/js/cronometro.js')
@vite('resources/js/ingresoLetra.js')


@php
    if (!session()->has('partida')) {
        session()->put('partida', $partida);
    }
    if (!session()->has('hora_inicio_juego')) {
        $tiempoInicio = time();
        session()->put('hora_inicio_juego', $tiempoInicio);
    }

    dump(session('partida')); 
@endphp

<div class="container mt-5">
    
    

    <div class="text-center pt-3">
        <button id="btnIngresarLetra" class="btn btn-primary mr-2" style="font-size: 1.8em;">Ingresar una letra</button>
        <button id="btnArriesgar" class="btn btn-primary mr-2" style="font-size: 1.8em;">Arriesgar una palabra</button>
        <button id="btnRendirse" class="btn btn-danger mr-2" style="font-size: 1.8em;">Rendirse</button>
        <button id="btnInterrumpir" class="btn btn-warning" style="font-size: 1.8em;">Interrumpir juego</button>
    </div>
</div>

@endsection
