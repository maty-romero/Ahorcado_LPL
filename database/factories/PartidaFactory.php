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
    public function definition(): array
    {
        static $estados = ['victoria', 'interrumpida', 'derrota'];   

        return [
            'estado' => $estados[array_rand($estados)],
            'oportunidades_restantes' => rand(0, 10),
            'tiempo_jugado' => sprintf('%02d:%02d', mt_rand(0, 59), mt_rand(0, 59)),
            'palabra_id' => rand(1, 30),
        ];
    }
}
