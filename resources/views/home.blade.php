@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="row justify-content-center mt-4">
            <div class="col-md-4 mb-4 d-flex">
                <div class="bg-secondary text-light p-4 flex-fill text-center">
                    @if (isset($_COOKIE[Auth::user()->username]))
                        @php
                            $partes = explode(';', $_COOKIE[Auth::user()->username]);
                            $estado = $partes[0];
                            $fecha = $partes[1];
                        @endphp
                        <h1>Bienvenido/a nuevamente!</h1>
                        <p class="fs-3 mt-4">Tu última partida fue el: {{ $fecha }}</p>
                        <p class="fs-3 mt-4">Resultado del juego: {{ $estado }}</p>
                    @else
                        <h1 class="fs-3 mt-4">Bienvenido/a a AhorcadoApp!</h1>
                    @endif
                </div>
            </div>
            <div class="col-md-4 mb-4 d-flex">
                <div class="bg-info text-light p-4 flex-fill text-center">
                    <h1>Estadísticas personales</h1>

                    @if (count($estadisticas) >= 1)
                        <table id="tablaPartidas" class="table text-center">
                            <thead class="thead-dark">
                                <tr class="table-warning">
                                    <th scope="col">Dificultad</th>
                                    <th scope="col">Palabras adivinadas</th>
                                    <th scope="col">Tiempo mínimo</th>
                                    <th scope="col">Tiempo máximo</th>
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
                    @else
                        <h4>* Aun no tienes victorias * </h4>    
                    @endif
                    
                </div>
            </div>
        </div>

        {{-- Modal elegir dificultad --}}
        <x-modal>
            <x-slot name="idModal">modalDificultad</x-slot>
            <x-slot name="titulo">Elecci&oacute;n dificultad</x-slot>

            <div class="form-group" id="arriesgaConteiner">
                <label for="txtPalabra" class="font-weight-bold" style="font-size: 1.3em;">Seleccione una
                    dificultad:</label>
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
                <button type="button" class="btn btn-primary btn-lg mr-4" data-bs-toggle="modal"
                    data-bs-target="#modalDificultad">Jugar nueva partida</button>
            </div>
            <div class="col-md-4 mb-4 d-flex justify-content-center">
                <a type="button" class="btn btn-danger btn-lg" href="{{ route('index_interrumpidos') }}">
                    Retomar juego interrumpido
                </a>
            </div>
        </div>
    </div>

    <form id="formEleccionDificultad" method="POST" action="{{ route('inicioPartida') }}">
        <input type="hidden" name="dificultad" id="idDificultadForm">
    </form>

@endsection
