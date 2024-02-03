<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Partida extends Model
{
    use HasFactory;

    /*
            $table->id();
            $table->enum('estado', ['victoria', 'interrumpida', 'derrota']);  
            $table->integer('oportunidades_restantes')->default(10);  
            $table->string('letras_ingresadas'); //acertadas y falladas
            $table->timestamp('tiempo_jugado'); // modificar formato en Model
            $table->unsignedBigInteger('palabra'); 
            $table->timestamps(); //fecha creacion
            $table->foreign('palabra')->references('id')->on('palabra'); 
    */
    protected $table = 'partida';
    protected $fillable = [
        'estado',
        'oportunidades_restantes',
        'letras_ingresadas',
        'tiempo_jugado',
        'palabra_id'
    ];

    public function palabra(): BelongsTo
    {
        return $this->belongsTo(Palabra::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'user_partida', 'partida_id', 'user_id');
    }

    // transforma el tiempo en segundos para utilizar el cronometro 
    public static function getTiempoPartidaCronometro(string $tiempoJugado) : int
    {
        list($horas, $minutos, $segundos) = explode(':', $tiempoJugado);
        $tiempoTotalSegundos = $horas * 3600 + $minutos * 60 + $segundos; 
        return $tiempoTotalSegundos; 
    }
    public static function getNuevoTiempoJugado($tiempoInicio, $tiempoFin, $tiempoTotalAnterior = 0) {
        $diferenciaTiempo = $tiempoFin - $tiempoInicio; // tiempo en segundos 
        $tiempoTotalNuevo = $tiempoTotalAnterior + $diferenciaTiempo;
        $tiempoTotalFormateado = gmdate('H:i:s', $tiempoTotalNuevo);
        return $tiempoTotalFormateado;
    }

    public function actualizarPartida(Partida $partida)
    {
        $this->estado = $partida->estado;
        $this->oportunidades_restantes = $partida->oportunidades_restantes;
        $this->letras_ingresadas = $partida->letras_ingresadas;
        $this->tiempo_jugado = $partida->tiempo_jugado;
        $this->palabra_id = $partida->palabra_id;

        return $this->save();
    }

}
