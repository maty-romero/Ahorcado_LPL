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
    

    public function definition(): array
    {
        //static $dificultad = [1,2,3]; 
        static $palabras = ['perro', 'alfabeto', 'botella', 'gato', 'termonuclear', 'escalofriante']; 

        return [
            'palabra' => $palabras[array_rand($palabras)],
            'dificultad_id' => Dificultad::inRandomOrder()->first()->id
        ];
    }
}
