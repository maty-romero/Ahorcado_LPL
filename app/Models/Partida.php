<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
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

    protected $fillable = [
        'estado',
        'oportunidades_restantes',
        'letras_ingresadas',
        'tiempo_jugado',
        'palabra'
    ];

    public function palabra(): BelongsTo
    {
        return $this->belongsTo(Palabra::class);
    }
}
