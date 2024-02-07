@extends('layouts.app')

@section('content')
    @vite('resources/js/interrumpidos.js')

    <h1 class="text-center">Juegos interrumpidos</h1>

    <div class="container">
        @if ($partidas)

            <x-modal>
                <x-slot name="idModal">modalEliminarInterrumpidas</x-slot>
                <x-slot name="titulo">Eliminar todas las partidas interrumpidas</x-slot>
                
                <div class="form-group" id="arriesgaConteiner">
                    <label for="txtPalabra" class="font-weight-bold" style="font-size: 1.3em;">
                        ¿Estas seguro que deseas eliminar todas las partidas interrumpidas hasta el momento?
                    </label>
                </div>
        
                <x-slot name="botonFooter">
                    <button id="btnEliminarAll" type="button" class="btn btn-warning" data-bs-dismiss="modal">
                        Confirmar
                    </button>
                </x-slot>
            </x-modal>

            <div class="d-flex justify-content-end mb-3">
                <button id="btnModalDeleteAll" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEliminarInterrumpidas">
                    Eliminar juegos interrumpidos
                </button>
            </div>

            <table id="tablaPartidas" class="table text-center">
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
                        <tr data-partida-id="{{ $partida->id }}">
                            <td>{{ $partida->id }}</td>
                            <td>{{ $partida->updated_at->format('d/m/Y') }}</td>
                            <td>{{ $partida->palabra->dificultad->nombre_dificultad }}</td>
                            <td><a type="button" class="btn btn-primary" href="{{route('show', $partida->id)}}">Reanudar</a></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-finalizar" data-partida-id="{{ $partida->id }}">
                                    Finalizar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h4 class="mt-4 bold">No hay partidas sin concluir</h4>
        @endif
    </div>

@endsection
