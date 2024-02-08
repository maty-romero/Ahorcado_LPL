<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\SoftDeletes;
class Palabra extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'palabra';
    protected $fillable = [
        'palabra',
        'dificultad_id'
    ];

    public function dificultad(): BelongsTo
    {
        return $this->belongsTo(Dificultad::class);
    }

    public function partidas(): HasMany
    {
        return $this->hasMany(Partida::class);
    }

    public static function evaluarLetra($palabra, $caracter)
    {
        $mensaje = '';

        $letrasIngresadas = session('partida')->letras_ingresadas; // sino tiene valor ''
        
        $caracterUtf8 = mb_convert_encoding($caracter, 'UTF-8', mb_detect_encoding($caracter));

        if (strlen($caracterUtf8) > 1 || !ctype_alpha($caracterUtf8)) {
            $mensaje = 'El carácter ingresado no es válido, debe ser una letra. Reingresa nuevamente!';
        } elseif (strpos($letrasIngresadas, $caracter) !== false) {
            $mensaje = 'Ya has ingresado esa letra. Ingresa otra!';
        } else {
            $estaEnPalabra = strpos($palabra, $caracter) !== false;
            if ($estaEnPalabra) {
                $mensaje = "El carácter '$caracter' está presente en la palabra. Bien hecho!";
            } else {
                $mensaje = "El carácter '$caracter' no está presente en la palabra. Sigue participando!";
                // Decrementar las oportunidades restantes
                session('partida')->oportunidades_restantes--; 
            }
            // Agregar el carácter ingresado a las letras ingresadas en la sesión
            $letrasIngresadas .= ",".$caracter;
            session('partida')->letras_ingresadas = $letrasIngresadas; 
        }

        return [   
            'mensaje' => $mensaje,
            'letras_ingresadas' => session('partida')->letras_ingresadas,
            'oportunidades' => session('partida')->oportunidades_restantes
        ];
    }

    // True: letras ingresadas suficientes para completar la palabra 
    public static function verificarVictoria($palabra, $letrasIngresadas)
    {
        // hash con las letras únicas como claves
        $letrasNecesarias = array_flip(array_unique(str_split($palabra))); 

        // Verificacion ingreso palabras
        foreach (str_split($letrasIngresadas) as $letra) {
            if (isset($letrasNecesarias[$letra])) {
                unset($letrasNecesarias[$letra]); // Eliminar la letra del hash si está presente
            }
        }
        return empty($letrasNecesarias); // hash vacio: se ha completado la palabra 

    }

    public static function getLetrasNoAcertadas($palabra, $letrasIngresadas)
    {
        $letrasIngresadasArray = explode(',', $letrasIngresadas);
        $letrasIngresadasArray = array_map('trim', $letrasIngresadasArray);
        $palabraArray = str_split($palabra);
        $letrasNoPresentes = array_diff($letrasIngresadasArray, $palabraArray);
        $resultado = implode(',', $letrasNoPresentes);
        return $resultado;
    }
    
    public function verificarPalabra(string $palabraIngreso){ 
        $format = false; 
        $coincidencia = false; 

        $palabraFormat = preg_replace('/\s+/', '', $palabraIngreso); // sacar espacios en blanco 

        if(!preg_match('/[^a-zA-Z]/', $palabraFormat))
        {
            $format = true; // formato adecuado
            $palabraFormat = strtolower($palabraIngreso);
            if($this->palabra == $palabraFormat)
            {
                $coincidencia = true;    
            }
        }
        
        return [
            'format' => $format,
            'coincidencia' => $coincidencia
        ];
    }
    public static function getPalabraRandomificultad(string $idDificultad)
    {
        $palabras = Palabra::where('dificultad_id', $idDificultad)->get();

        // Seleccionar una palabra aleatoria de la lista
        $palabraAleatoria = $palabras->random();

        // Devolver el ID de la palabra seleccionada
        return $palabraAleatoria->id;
    }
}
