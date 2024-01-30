import axios from 'axios';

// Obtener el botón y agregar un event listener
const botonIngresarLetra = document.getElementById('ingresarLetra');
botonIngresarLetra.addEventListener('click', async () => {
    try {
        // Esperar la pulsación de una tecla
        const teclaPresionada = await esperarTecla();
        const palabraJuego = "{{ $partida->palabra->palabra }}";
        // Peticion Ajax con Axios 
        const respuesta = await axios.post('/evaluarLetra', {
            palabra: palabraJuego, 
            tecla: teclaPresionada
        });

        // Manejar la respuesta
        console.log(respuesta);
    } catch (error) {
        console.error('Error al realizar la petición:', error);
    }
});

// Función para esperar la pulsación de una tecla
function esperarTecla() {
    return new Promise(resolve => {
        
        document.addEventListener('keydown', function(event) {
            const tecla = event.key;  // Obtener el carácter de la tecla presionada
            // Resolver la promesa con la tecla presionada
            resolve(tecla);
        }, { once: true }); // El tercer argumento {once: true} garantiza que el event listener se eliminará después de la primera pulsación
    });
}
