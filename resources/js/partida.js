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

// Peticion asincronica: Ingreso letra
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
        
        // Manejar la respuesta
        console.log(respuesta.data);
        
        msjPartida.textContent = ""; 
        msjPartida.textContent = respuesta.data.mensaje;

        document.getElementById('spanLetrasNoAcertadas').textContent = respuesta.data.letras_incorrectas;
        document.getElementById('idOportunidadesRestantes').textContent = respuesta.data.oportunidades; 

        if(respuesta.data.juegoTerminado)
        {
            console.log("Ha terminado la partida!!! -- Accionar Modal resultado");
            let nuevoEstado = respuesta.data.estadoPartida;
            document.getElementById('nuevoEstado').value = nuevoEstado;
            document.getElementById('formFinalizarPartida').submit(); 
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

// export para incluir dentro de blade 
function actualizarPalabraEnmascarada(palabra, letrasIngresadas) {
    let contenedorPalabra = document.getElementById('idPalabraEnmascarada');
    contenedorPalabra.textContent = ""; 

    let palabraEnmascarada = '';
    for (let i = 0; i < palabra.length; i++) {
        if (letrasIngresadas.includes(palabra[i])) {
            palabraEnmascarada += palabra[i];
        } else {
            palabraEnmascarada += ' _ ';
        }
    }
    
    contenedorPalabra.textContent = palabraEnmascarada;
}


// Peticion asincronica: Ingreso Palabra
btnArriesgar.addEventListener('click', async () => {
    
    try {
        let palabraIngresada = document.getElementById('txtPalabraArriesgar').value;
        //const palabraJuego = document.getElementById('palabraJuego').value;
        
        // Peticion Ajax con Axios, incluyendo el token CSRF en los datos
         
        const respuesta = await axios.post('/evaluarPalabra', {
            palabraIngreso: palabraIngresada
        }, {
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        
        // Manejar la respuesta
        console.log(respuesta.data);

        if(!respuesta.data.format)
        {
            let contenedor = document.getElementById('arriesgaConteiner');

            let mensajeError = document.createElement('p');
            mensajeError.textContent = "No se deben ingresar caracteres especiales y/o numeros.";
            mensajeError.classList.add('text-danger');

            let inputElement = contenedor.querySelector('input');
            contenedor.insertBefore(mensajeError, inputElement.nextSibling);

        }else{
            if(respuesta.data.coincidencia){
                document.getElementById('nuevoEstado').value = 'victoria';
            }else{
                document.getElementById('nuevoEstado').value = 'derrota';
            }

            document.getElementById('formFinalizarPartida').submit();
        }
        
    } catch (error) {
        console.error('Error al realizar la petición:', error);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Tu código aquí se ejecutará cuando se cargue el DOM
    const palabraJuego = document.getElementById('palabraJuego').value;
    const letrasIngresadas = document.getElementById('letrasIngresadasInicial').value;

    actualizarPalabraEnmascarada(palabraJuego, letrasIngresadas);
});
