function iniciarCronometro(tiempoInicial) {
    // Obtener las partes del tiempo inicial
    const partesTiempo = tiempoInicial.split(':');
    let horas = parseInt(partesTiempo[0]);
    let minutos = parseInt(partesTiempo[1]);
    let segundos = parseInt(partesTiempo[2]);

    // Actualizar el cronómetro cada segundo
    const intervalo = setInterval(() => {
        // Incrementar los segundos
        segundos++;

        // Si los segundos alcanzan los 60, aumentar los minutos y restablecer los segundos a cero
        if (segundos === 60) {
            segundos = 0;
            minutos++;

            // Si los minutos alcanzan los 60, aumentar las horas y restablecer los minutos a cero
            if (minutos === 60) {
                minutos = 0;
                horas++;
            }
        }

        // Formatear los valores de horas, minutos y segundos
        const horasMostrar = horas < 10 ? '0' + horas : horas;
        const minutosMostrar = minutos < 10 ? '0' + minutos : minutos;
        const segundosMostrar = segundos < 10 ? '0' + segundos : segundos;

        // Construir el tiempo para mostrar
        const tiempoMostrar = `${horasMostrar}:${minutosMostrar}:${segundosMostrar}`;

        // Mostrar el tiempo 
        document.getElementById('tiempoJugado').textContent = "";
        document.getElementById('tiempoJugado').textContent = tiempoMostrar;
        


    }, 1000); // Actualizar cada segundo (1000 milisegundos)
}

document.addEventListener('DOMContentLoaded', function() {
    let tiempoInicial = document.getElementById('tiempoJugadoInicial').value; 
    iniciarCronometro(tiempoInicial);
});

