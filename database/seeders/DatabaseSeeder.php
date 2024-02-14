<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Palabra;
use App\Models\Dificultad; 
use App\Models\Partida;
use App\Models\User;
use App\Models\UserPartida;
use Illuminate\Support\Facades\Hash; 
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'HolaMundo',
            'username' => 'holamundouser',
            'email' => 'hola@gmail.com',
            'email_verified_at' => now(),
            'password' => 123456789,
            'fecha_nacimiento' => now()->subYears(25)->toDateString(), // Ejemplo: establecer la fecha de nacimiento hace 25 años
            'pais_residencia' => 'Argentina'
        ]);

        Dificultad::create(['nombre_dificultad' => 'Baja', 'longitud_minima' => 1, 'longitud_maxima' => 5]);
        Dificultad::create(['nombre_dificultad' => 'Media', 'longitud_minima' => 6, 'longitud_maxima' => 8]);
        Dificultad::create(['nombre_dificultad' => 'Alta', 'longitud_minima' => 9, 'longitud_maxima' => 100]);
        
        // Palabras para la dificultad baja
        Palabra::create(['palabra' => 'casa', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'sol', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'flor', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'azul', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'luz', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'paz', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'amor', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'gato', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'mar', 'dificultad_id' => 1]);
        Palabra::create(['palabra' => 'vida', 'dificultad_id' => 1]);

        // Palabras para la dificultad media
        Palabra::create(['palabra' => 'computadora', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'telefono', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'internet', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'botella', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'musica', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'persona', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'tierra', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'ciudad', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'animal', 'dificultad_id' => 2]);
        Palabra::create(['palabra' => 'naturaleza', 'dificultad_id' => 2]);

        // Palabras para la dificultad alta
        Palabra::create(['palabra' => 'extravagante', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'institucionalizacion', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'espectacularmente', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'incomunicabilidad', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'interdisciplinariedad', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'anticonstitucionalmente', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'desproporcionadamente', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'irrepresentatividad', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'incontrovertiblemente', 'dificultad_id' => 3]);
        Palabra::create(['palabra' => 'desesperadamente', 'dificultad_id' => 3]);

        User::factory(12)->create();
        Partida::factory(10)->create();
        UserPartida::factory(10)->create();
    }
}
