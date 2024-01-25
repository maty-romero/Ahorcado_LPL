<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Palabra;
use App\Models\Dificultad; 
use App\Models\Partida;
use App\Models\User; 
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Dificultad::factory(4)->create();
        
        // Crear dificultades con reglas específicas
        Dificultad::create(['nombre_dificultad' => 'Baja', 'longitud_minima' => 1, 'longitud_maxima' => 5]);
        Dificultad::create(['nombre_dificultad' => 'Media', 'longitud_minima' => 6, 'longitud_maxima' => 8]);
        Dificultad::create(['nombre_dificultad' => 'Alta', 'longitud_minima' => 9, 'longitud_maxima' => 100]);
        
        Palabra::factory(10)->create();
        Partida::factory(5)->create();
        User::factory(3)->create();



        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
