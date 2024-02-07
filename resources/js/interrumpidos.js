import axios from 'axios';

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

var botonesFinalizar = document.querySelectorAll('.btn-finalizar');

    // Iterar sobre cada botón y agregar un event listener
    botonesFinalizar.forEach(function(boton) {
        boton.addEventListener('click', function() {
            var partidaId = this.dataset.partidaId; // Obtener el ID de la partida del atributo data-partida-id
            concluirPartida(partidaId); // Llamar a la función concluirPartida con el ID de la partida
        });
    });


function concluirPartida(partidaId) {
    
    // Realizar la solicitud AJAX para eliminar la partida
    axios.delete('/eliminarPartida', {
            data: { partidaId: partidaId },
            headers: {
                'X-CSRF-TOKEN': token
            }
        })
        .then(function (response) {
            // Manejar la respuesta de la solicitud exitosa
            console.log(response.data);
            
            // Eliminar la fila de la tabla
            var fila = document.querySelector('tr[data-partida-id="' + partidaId + '"]');
            if (fila) {
                fila.remove();
            }
        })
        .catch(function (error) {
            // Manejar errores de la solicitud
            console.error('Error al eliminar la partida:', error);
        });
}


