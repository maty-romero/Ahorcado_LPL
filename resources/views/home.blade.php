@extends('layouts.app')

@section('content')

<!-- Coloca esta etiqueta antes de la etiqueta de cierre </body> -->
<script src="resources/js/home.js"></script>

{{--
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
--}}

<div class="container-fluid">
    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4">
            <div class="bg-secondary text-light p-4">
                <!-- Contenido del primer div -->
                <h1>Bienvenido!</h1>
                <p>Ultimo Juego: </p>
                <p>Resultado del juego: </p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="bg-info text-light p-4">
                <!-- Contenido del segundo div -->
                <h1>Estadisticas personales</h1>
                <p>Contenido del segundo div.</p>
            </div>
        </div>
    </div>
    <!-- Botones alineados con respecto a los divs -->
    <div class="row justify-content-center mt-4">
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <button type="button" class="btn btn-primary btn-lg mr-4">Juego nuevo</button>
        </div>
        <div class="col-md-4 mb-4 d-flex justify-content-center">
            <a type="button" class="btn btn-danger btn-lg" href="{{ route('index_interrumpidos')}}">Retormar juego interrumpido</a>
        </div>
    </div>
</div>


<style>
    /* Define la clase para ocultar el card */
    .hide {
        opacity: 0;
        transition: opacity 0.5s ease-in-out; /* Añade una transición suave para una animación de desvanecimiento */
    }
</style>



@endsection
