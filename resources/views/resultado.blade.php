@extends('layouts.app')

@section('content')

    @php
        session()->forget('partida');
        session()->forget('hora_inicio_juego');
    @endphp

    <div class="container">
        @if ($partida->estado == 'victoria')
            <div class="alert alert-success text-center" role="alert">
                <h4>¡Felicidades! Has ganado.</h4>
            </div>
        @else
            <div class="alert alert-warning text-center" role="alert">
                <h4>¡Derrota!</h4>
                <h4>¡Ánimo! Sigue intentándolo.</h4>
                <p class="lead">La palabra a adivinar era: <strong>{{ $partida->palabra->palabra }}</strong></p>
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

        <div class="text-center pt-3">
            <a href="{{route('home')}}" class="btn btn-primary mr-2" style="font-size: 1.4em;">Jugar nueva partida</a>
            <a href="{{route('home')}}" class="btn btn-secondary mr-2" style="font-size: 1.4em;">Volver al Inicio</a>

        </div>
    </div>


@endsection
