<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Partida>
 */
class PartidaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

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
    static $estados = ['victoria', 'interrumpida', 'derrota'];  
    static $palabras = [1,2,3,4,5,6,7]; 
    public function definition(): array
    {
        return [
            'estado' => substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 4)), 0, 5),
            'oportunidades_restantes' => rand(0, 10),
            'tiempo_jugado' => sprintf('%02d:%02d', mt_rand(0, 59), mt_rand(0, 59)),
            'palabra' => $this->palabras[array_rand($this->palabras)],
        ];
    }
}
