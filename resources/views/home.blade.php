
@extends('layouts.app')

@section('content')

@if (session()->has('loginCardShown'))
    <div class="container" id="loginCard">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('Haz iniciado sesion!') }}
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="container-fluid">
    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <div class="bg-secondary text-light p-4">
                @if (isset($_COOKIE[Auth::user()->username]))
                    @php
                        $partes = explode(';', $_COOKIE[Auth::user()->username]);
                        $estado = $partes[0]; 
                        $fecha = $partes[1]; 
                    @endphp
                    <h1>Bienvenido/a nuevamente!</h1>
                    <p>Tu ultima partida fue el: {{ $fecha }}</p>
                    <p>Resultado del juego: {{ $estado }}</p>
                @else
                    <h1>Bienvenido/a a AhorcadoApp!</h1>
                @endif
                
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="bg-info text-light p-4">
                <h1>Estadisticas personales</h1>
                <table id="tablaPartidas" class="table text-center">
                    <thead class="thead-dark">
                        <tr class="table-warning">
                            <th scope="col">Dificultad</th>
                            <th scope="col">Palabras adivinadas</th>
                            <th scope="col">Tiempo minimo</th>
                            <th scope="col">Tiempo maximo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estadisticas as $result)
                            <tr>
                                <td>{{ $result->nombre_dificultad }}</td>
                                <td>{{ $result->cantidad }}</td>
                                <td>{{ $result->tiempo_minimo }}</td>
                                <td>{{ $result->tiempo_maximo }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
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

    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <button type="button" class="btn btn-primary btn-lg mr-4" data-bs-toggle="modal" data-bs-target="#modalDificultad">Juego nuevo</button>
        </div>
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <a type="button" class="btn btn-danger btn-lg" href="{{ route('index_interrumpidos')}}">Retormar juego interrumpido</a>
        </div>
    </div>
</div>

<form id="formEleccionDificultad" method="POST" action="{{ route('inicioPartida') }}">
    <input type="hidden" name="dificultad" id="idDificultadForm">
</form>


<style>
    /* Define la clase para ocultar el card */
    .hide {
        opacity: 0;
        transition: opacity 0.5s ease-in-out; /* Añade una transición suave para una animación de desvanecimiento */
    }
</style>



@endsection

