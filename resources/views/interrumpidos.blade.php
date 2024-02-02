@extends('layouts.app')

@section('content')
    @php
        session()->flush();
    @endphp
    <h1 class="text-center">Juegos interrumpidos</h1>

    <div class="container">
        @if ($partidas)
            <div class="d-flex justify-content-end mb-3">
                <a href="#" class="btn btn-warning">Eliminar juegos interrumpidos</a>
            </div>

            <table class="table text-center">
                <thead class="thead-dark">
                    <tr class="table-warning">
                        <th scope="col">Nro. de Partida</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Dificultad</th>
                        <th scope="col">Reanudar juego</th>
                        <th scope="col">Finalizar juego</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partidas as $partida)
                        <tr>
                            <td>{{ $partida->id }}</td>
                            <td>{{ $partida->updated_at->format('d/m/Y') }}</td>
                            <td>{{ $partida->palabra->dificultad->nombre_dificultad }}</td>
                            <td><a type="button" class="btn btn-primary" href="{{route('show', $partida->id)}}">Reanudar</a></td>
                            <td><a type="button" class="btn btn-danger">Finalizar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h4 class="mt-4 bold">No hay partidas sin concluir</h4>
        @endif
    </div>

@endsection
