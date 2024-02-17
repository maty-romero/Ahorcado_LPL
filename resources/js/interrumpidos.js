import axios from 'axios';

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var botonesFinalizar = document.querySelectorAll('.btn-finalizar');

// Agregado de listeners btn Finalizar
botonesFinalizar.forEach(function (boton) {
    boton.addEventListener('click', function () {
        var partidaId = this.dataset.partidaId; 
        concluirPartida(partidaId); 
    });
});


function concluirPartida(partidaId) {

    // Solicitud AJAX para eliminar la partida
    axios.delete('/eliminarPartida', {
        data: { partidaId: partidaId },
        headers: {
            'X-CSRF-TOKEN': token
        }
    })
        .then(function (response) {
            // Manejar la respuesta de la solicitud exitosa

            // Eliminar la fila de la tabla
            var fila = document.querySelector('tr[data-partida-id="' + partidaId + '"]');
            if (fila) {
                fila.remove();
            }
        })
        .catch(function (error) {
            console.error('Error al eliminar la partida:', error);
        });
}

var btnDeleteAll = document.getElementById('btnEliminarAll');

btnDeleteAll.addEventListener('click', function () {
    // Realizar la solicitud DELETE
    axios.delete('/eliminarInterrumpidas', {
        headers: {
            'X-CSRF-TOKEN': token
        }
    })
        .then(function (response) {
            // Manejar la respuesta de la solicitud exitosa

            var tablaPartidas = document.getElementById('tablaPartidas');
            if (tablaPartidas) {
                var filas = tablaPartidas.querySelectorAll('tbody tr');
                filas.forEach(function(fila) {
                    fila.remove();
                });
            }
        })
        .catch(function (error) {
            // Manejar errores de la solicitud
            console.error('Error al realizar la solicitud DELETE:', error);
        });
});


