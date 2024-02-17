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
         
        const respuesta = await axios.post('/evaluarLetra', { 
            caracter: teclaPresionada
        }, {
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        
        // Respuesta exitosa
        
        msjPartida.textContent = ""; 
        msjPartida.textContent = respuesta.data.mensaje;

        document.getElementById('spanLetrasNoAcertadas').textContent = respuesta.data.letras_incorrectas;
        document.getElementById('idOportunidadesRestantes').textContent = respuesta.data.oportunidades; 

        if(respuesta.data.juegoTerminado)
        {
            let nuevoEstado = respuesta.data.estadoPartida;
            document.getElementById('nuevoEstado').value = nuevoEstado;
            document.getElementById('formFinalizarPartida').submit(); 
        }
        
        actualizarPalabraEnmascarada(respuesta.data.palabraJuego, respuesta.data.letras_ingresadas)
        habilitarBotones();

    } catch (error) {
        console.error('Error al realizar la petición:', error);
        habilitarBotones();
    }
});

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


// Peticion asincronica: Arriesgar Palabra
btnArriesgar.addEventListener('click', async () => {
    
    try {
        let palabraIngresada = document.getElementById('txtPalabraArriesgar').value;
        
        // Peticion Ajax con Axios, incluyendo el token CSRF en los datos
         
        const respuesta = await axios.post('/evaluarPalabra', {
            palabraIngreso: palabraIngresada
        }, {
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        
        // Respuesta exitosa

        if(!respuesta.data.format)
        {
            let contenedor = document.getElementById('arriesgaConteiner');
            let mensajeError = contenedor.querySelector('.text-danger');
            
            if (!mensajeError) {
                mensajeError = document.createElement('p');
                mensajeError.classList.add('text-danger');
                contenedor.appendChild(mensajeError);
            }
            
            mensajeError.textContent = "No se deben ingresar caracteres especiales y/o numeros.";
        }
        else
        {
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
    const palabraJuego = document.getElementById('palabraJuego').value;
    const letrasIngresadas = document.getElementById('letrasIngresadasInicial').value;

    actualizarPalabraEnmascarada(palabraJuego, letrasIngresadas);
});
