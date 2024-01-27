<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Palabra;
use App\Models\Dificultad; 
use App\Models\Partida;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 
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
        
        User::create([
            'name' => 'HolaMundo',
            'email' => 'hola@gmail.com',
            'email_verified_at' => now(),
            'password' => 123456789,
            'fecha_nacimiento' => now()->subYears(25)->toDateString(), // Ejemplo: establecer la fecha de nacimiento hace 25 años
            'pais_recidencia' => 'Argentina'
        ]);

        User::factory(3)->create();
        Palabra::factory(10)->create();
        Partida::factory(5)->create();
        
    }
}
