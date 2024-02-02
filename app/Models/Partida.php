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
}
