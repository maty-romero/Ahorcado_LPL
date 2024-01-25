<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dificultad; 
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Palabra>
 */
class PalabraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    /*
$table->id();
            $table->string('palabra');
            $table->unsignedBigInteger('dificultad'); 
            $table->timestamps();
            $table->foreign('dificultad')->references('id')->on('dificultad');
    */
    static $dificultad = [1,2,3]; 
    static $palabras = ['perro', 'alfabeto', 'botella', 'gato', 'termonuclear', 'escalofriante']; 

    public function definition(): array
    {
        return [
            'palabra' => $this->palabras[array_rand($this->palabras)],
            'dificultad' => Dificultad::inRandomOrder()->first()->id
        ];
    }
}
