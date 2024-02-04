import axios from 'axios';

const btnIngresarLetra = document.getElementById('btnIngresarLetra');
const btnArriesgar = document.getElementById('btnArriesgar');
const btnRendirse = document.getElementById('btnRendirse');
const btnInterrumpir = document.getElementById('btnInterrumpir');
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function deshabilitarBotones() {
    btnIngresarLetra.disabled = true;
    btnArriesgar.disabled = true;
    btnRendirse.disabled = true;
    btnInterrumpir.disabled = true;
}

function habilitarBotones() {
    btnIngresarLetra.disabled = false;
    btnArriesgar.disabled = false;
    btnRendirse.disabled = false;
    btnInterrumpir.disabled = false;
}

// Evento Btn Ingreso de letra 
btnIngresarLetra.addEventListener('click', async () => {

    deshabilitarBotones();
    let msjPartida = document.getElementById('idMsjPartida'); 
    msjPartida.textContent = ""; 
    msjPartida.textContent = "Presiona una letra!";
    
    try {
        // Esperar la pulsación de una tecla
        const teclaPresionada = await esperarTecla();
        const palabraJuego = document.getElementById('palabraJuego').value;
        console.log("Palabra: " + palabraJuego +"\nCaracter: " + teclaPresionada);
        
        // Peticion Ajax con Axios, incluyendo el token CSRF en los datos
         
        const respuesta = await axios.post('/evaluarLetra', {
            palabra: palabraJuego, 
            caracter: teclaPresionada
        }, {
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        /*
       // Peticion Ajax con Axios sin incluir el token CSRF en los datos
        const respuesta = await axios.post('/evaluarLetra', {
            palabra: palabraJuego, 
            caracter: teclaPresionada
        });
        */
        // Manejar la respuesta
        console.log(respuesta.data);
        
        msjPartida.textContent = ""; 
        msjPartida.textContent = respuesta.data.mensaje;

        document.getElementById('spanLetrasNoAcertadas').textContent = respuesta.data.letras_incorrectas;
        document.getElementById('idOportunidadesRestantes').textContent = respuesta.data.oportunidades; 

        if(respuesta.data.juegoTerminado)
        {
            console.log("Ha terminado la partida!!! -- Accionar Modal resultado");
            window.location.replace("/finalizarPartida");
        }

        actualizarPalabraEnmascarada(palabraJuego, respuesta.data.letras_ingresadas)
        habilitarBotones();

    } catch (error) {
        console.error('Error al realizar la petición:', error);
        habilitarBotones();
    }
});

// Función para esperar la pulsación de una tecla
function esperarTecla() {
    return new Promise(resolve => {
        document.addEventListener('keydown', function(event) {
            const tecla = event.key;  // Obtener carácter de la tecla presionada
            // Resolver la promesa con la tecla presionada
            resolve(tecla);
        }, { once: true }); // El tercer argumento {once: true} garantiza que el event listener se eliminará después de la primera pulsación
    });
}

function actualizarPalabraEnmascarada(palabra, letrasIngresadas) {
    let palabraEnmascarada = '';
    for (let i = 0; i < palabra.length; i++) {
        if (letrasIngresadas.includes(palabra[i])) {
            palabraEnmascarada += palabra[i];
        } else {
            palabraEnmascarada += ' _ ';
        }
    }
    document.getElementById('idPalabraEnmascarada').textContent = palabraEnmascarada;
}
