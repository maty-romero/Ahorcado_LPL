@extends('layouts.app')

@section('content')

<h1 class="text-center">Juegos interrumpidos</h1>

<div class="container">
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

            @foreach ($partidas_interrumpidas as $partida)
                <tr>
                    <td>$partida->id</td>
                    <td>$partida->updated_at</td>
                    <td>$partida->palabra->dificultad->nombre_dificultad</td>
                    <td><button type="button" class="btn btn-primary">Reanudar</button></td>
                    <td><button type="button" class="btn btn-danger">Finalizar</button></td>
                </tr>
            @endforeach

            
        </tbody>
    </table>
</div>

{{-- 
            
        --}}

@endsection
