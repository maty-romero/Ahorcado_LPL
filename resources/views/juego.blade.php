@extends('layouts.app')

@section('content')

@vite('resources/js/cronometro.js')
@vite('resources/js/partida.js')

<div class="container">
    
    <div class="d-flex justify-content-between mb-4">
        <div>
            <span class="fw-bold text-dark" style="font-size: 1.8em;">Tiempo jugado:</span> 
            <span id="tiempoJugado" class="font-weight-bold text-dark" style="font-size: 1.8em;">
                {{session('partida')->tiempo_jugado}}
            </span>
        </div>
        <div>
            <span class="fw-bold text-dark" style="font-size: 1.8em;">Oportunidades restantes:</span> 
            <span id="idOportunidadesRestantes" class="font-weight-bold text-dark" style="font-size: 1.8em;">
                {{session('partida')->oportunidades_restantes}}
            </span>
             
        </div>
    </div>

    <div class="d-flex align-items-start justify-content-center mb-4 ">
        <div class="mr-md-4 mb-3 mb-md-0" style="margin-right: 60px;">
            <img src="https://play-lh.googleusercontent.com/MDGMJHCm7qwtOw9o8M00ZXVpL-sCTS5z-nVKwveVDsriFmtbSV8eaKYZOfejDyiQJk4=w526-h296-rw" alt="Hangman Image" style="max-width: 100%; height: 350px">
        </div>
        
        <div class="text-center border border-secondary p-3 rounded bg-dark bg-gradient" style="width: 800px; height: 350px">
            <div class="border-info mb-3 mh-40">
                <span class="fw-bold text-info" style="font-size: 1.8em;">Informaci&oacute;n de la partida:</span> 
                <p class="text-white" style="font-size: 1.7em;" id="idMsjPartida"></p>
            </div>
            <div class="border-top pt-2 border-danger mh-60">
                <span class="fw-bold text-warning" style="font-size: 1.8em;">Letras de la palabra:</span>
                <p id='idPalabraEnmascarada' class="text-warning mt-4 " style="font-size: 3.0em;" id="idLetrasPalabra"></p>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="mt-4 mb-4 col-md-6 text-center pt-2 pb-2 border border-danger rounded" style="width: 1210px">
            <span class="fw-bold text-dark" style="font-size: 1.8em;">Letras no acertadas:</span> 
            @php
                $letrasNoAcertadas = session('partida')->palabra->getLetrasNoAcertadas(session('partida')->letras_ingresadas);
            @endphp
            <span id="spanLetrasNoAcertadas" class="text-success" style="font-size: 1.8em;">
                {{$letrasNoAcertadas}}
            </span>
        </div>
    </div>
    

    <input type="hidden" id="palabraJuego" value="{{ $partida->palabra->palabra }}">
    <input type="hidden" id="tiempoJugadoInicial" value="{{ $partida->tiempo_jugado }}">
    <input type="hidden" id="letrasIngresadasInicial" value="{{ $partida->letras_ingresadas }}">

    {{--Modal Rendirse--}}
    <x-modal>
        <x-slot name="idModal">modalRendirse</x-slot>
        <x-slot name="titulo">Rendirse</x-slot>
        
        <p class="font-weight-bold" style="font-size: 1.5em;">¿Estas seguro de que deseas rendirte?</p>
    
        <x-slot name="botonFooter">
            <button id="btnRendirse" type="button" class="btn btn-warning">Rendirse</button>
        </x-slot>
    </x-modal>
    
    <script>
        document.getElementById('btnRendirse').addEventListener('click', function() {
            document.getElementById('nuevoEstado').value = 'derrota';
            document.getElementById('formFinalizarPartida').submit();
        });
    </script>

    {{--Modal Interrumpir--}}
    <x-modal>
        <x-slot name="idModal">modalInterrumpir</x-slot>
        <x-slot name="titulo">Interrumpir Partida</x-slot>
        
        <p class="font-weight-bold" style="font-size: 1.5em;">¿Estas seguro de que deseas interrumpir la partida?</p>
        <p class="font-weight-bold" style="font-size: 1.5em;">No te preocupes! Podras continuar en otro momento</p>
    
        <x-slot name="botonFooter">
            <button id="btnInterrumpir" type="button" class="btn btn-warning">Interrumpir</button>
        </x-slot>
    </x-modal>

    <script>
        document.getElementById('btnInterrumpir').addEventListener('click', function() {
            document.getElementById('nuevoEstado').value = 'interrumpida';
            document.getElementById('formFinalizarPartida').submit();
        });
    </script>

    {{--Modal Arriesgar palabra--}}
    <x-modal>
        <x-slot name="idModal">modalArriesgar</x-slot>
        <x-slot name="titulo">Arriesgar una palabra</x-slot>
        
        <div class="form-group" id="arriesgaConteiner">
            <label for="txtPalabra" class="font-weight-bold" style="font-size: 1.3em;">Ingrese una palabra:</label>
            <input id="txtPalabraArriesgar" type="text" name="palabraIngreso" class="form-control">
        </div>

        <x-slot name="botonFooter">
            <button id="btnArriesgar" type="button" class="btn btn-warning">Arriesgar</button>
        </x-slot>
    </x-modal>

    <div class="text-center pt-3">
        <button id="btnIngresarLetra" type="button" class="btn btn-primary mr-2" style="font-size: 1.8em;">Ingresar una letra</button>
        <button id="btnArriesgar" type="button" class="btn btn-info mr-2" data-bs-toggle="modal" data-bs-target="#modalArriesgar" style="font-size: 1.8em;">Arriesgar una palabra</button>
        <button id="btnRendirse" type="button" class="btn btn-danger mr-2" data-bs-toggle="modal" data-bs-target="#modalRendirse" style="font-size: 1.8em;">Rendirse</button>
        <button id="btnInterrumpir" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalInterrumpir" style="font-size: 1.8em;">Interrumpir juego</button>
    </div>
</div>

<form id="formFinalizarPartida" method="POST" action="{{ route('finPartida') }}">
    <input type="hidden" name="nuevoEstado" id="nuevoEstado">
</form>


@endsection
