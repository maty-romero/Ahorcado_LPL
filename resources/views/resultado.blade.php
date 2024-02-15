@extends('layouts.app')

@section('content')

    <div class="container">
        @if ($partida->estado == 'victoria')
            <div class="alert alert-success text-center" role="alert">
                <h4 class="fw-bold">¡Felicidades! Has ganado.</h4>
            </div>
        @else
            <div class="alert alert-warning text-center" role="alert">
                <h4 class="fw-bold">¡Derrota!</h4>
                <h4>¡Ánimo! Sigue intentándolo.</h4>
                <p class="lead">La palabra a adivinar era: <strong class="fw-bold">{{ $partida->palabra->palabra }}</strong></p>
            </div>

            @if (!empty($partidasRanking))
                <div class="container">
                    <h4>Ranking de jugadores más rápidos - Dificultad: {{$partida->palabra->dificultad->nombre_dificultad}}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th class="text-center">Posición</th>
                                <th class="text-center">Nombre jugador</th>
                                <th class="text-center">Tiempo insumido</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partidasRanking as $partida)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $partida->usuarios->first()->name }}</td>
                                    <td class="text-center">{{ $partida->tiempo_jugado }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endif

        <x-modal>
            <x-slot name="idModal">modalDificultad</x-slot>
            <x-slot name="titulo">Elecci&oacute;n dificultad</x-slot>
            
            <div class="form-group" id="arriesgaConteiner">
                <label for="txtPalabra" class="font-weight-bold" style="font-size: 1.3em;">Seleccione una dificultad:</label>
                <select class="form-control" id="cmbDificultad" name="dificultad">
                    <option value="1">Baja</option>
                    <option value="2">Media</option>
                    <option value="3">Alta</option>
                </select>
            </div>
    
            <x-slot name="botonFooter">
                <button id="btnDificultad" type="button" class="btn btn-warning">Jugar</button>
            </x-slot>
        </x-modal>
    
        <script>
            document.getElementById('btnDificultad').addEventListener('click', function() {
                document.getElementById('idDificultadForm').value = document.getElementById("cmbDificultad").value;;
                document.getElementById('formEleccionDificultad').submit();
            });
        </script>

        <div class="text-center pt-3">
            <button type="button" class="btn btn-primary btn-lg mr-4" data-bs-toggle="modal" data-bs-target="#modalDificultad">Juego nuevo</button>
            <a href="{{route('home')}}" class="btn btn-secondary mr-2" style="font-size: 1.4em;">Volver al Inicio</a>

        </div>
    </div>

    <form id="formEleccionDificultad" method="POST" action="{{ route('inicioPartida') }}">
        <input type="hidden" name="dificultad" id="idDificultadForm">
    </form>

@endsection
