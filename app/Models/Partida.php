<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
class Partida extends Model
{
    use HasFactory, SoftDeletes;
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
 
    public static function getTiempoPartidaCronometro(string $tiempoJugado) : int
    {
        list($horas, $minutos, $segundos) = explode(':', $tiempoJugado);
        $tiempoTotalSegundos = $horas * 3600 + $minutos * 60 + $segundos; 
        return $tiempoTotalSegundos; 
    }
    public static function getNuevoTiempoJugado($tiempoInicio, $tiempoFin, $tiempoTotalAnterior = 0) {
        $diferenciaTiempo = $tiempoFin - $tiempoInicio; // tiempo en segundos 
        $tiempoTotalNuevo = $tiempoTotalAnterior + $diferenciaTiempo;
        $tiempoTotalFormateado = gmdate('H:i:s', $tiempoTotalNuevo);
        return $tiempoTotalFormateado;
    }

    public function guardarPartida($tiempoInicio, $tiempoFin)
    {
        DB::transaction(function () use ($tiempoInicio, $tiempoFin) {
            //$partida = Partida::find($idPartida);
            $tiempoTotalAnterior = Partida::getTiempoPartidaCronometro($this->tiempo_jugado); // tiempo anterior jugado
            // tiempo jugado hasta el momento
            $tiempoTotalNuevo = Partida::getNuevoTiempoJugado($tiempoInicio, $tiempoFin, $tiempoTotalAnterior);

            $this->update([
                'estado' => $this->estado,
                'oportunidades_restantes' => $this->oportunidades_restantes,
                'letras_ingresadas' => $this->letras_ingresadas,
                'tiempo_jugado' => $tiempoTotalNuevo
            ]);

            // No es necesario llamar a $partida->save() 
            // Laravel deshace automáticamente los cambios en caso de excepción durante la transacción.
        });
    }

    /*
        Ranking jugadores con tiempos insumidos en acertar palabras
        Discriminado por dificultad elegida
    */
    public static function obtenerRankingPartidas($dificultad)
    {
        $idDificultad = DB::table('dificultad')->where('nombre_dificultad', $dificultad)->value('id');

        $rankingPartidas = Partida::with(['usuarios' => function ($query) {
                $query->select('users.name');
            }])
            ->select('id', 'tiempo_jugado') 
            ->where('estado', 'victoria')
            ->whereHas('palabra', function ($query) use ($idDificultad) {
                $query->where('dificultad_id', $idDificultad);
            })
            ->orderBy('tiempo_jugado')
            ->limit(5)
            ->get();

        return $rankingPartidas;
    }

    public static function crearPartida(string $idPalabraDificultad, string $idJugador)
    {
        return DB::transaction(function () use ($idPalabraDificultad, $idJugador) {
            $partida = new Partida();
            $partida->estado = 'iniciada'; 
            $partida->oportunidades_restantes = 10; 
            $partida->letras_ingresadas = '';
            $partida->tiempo_jugado = '00:00:00'; 
            $partida->palabra_id = $idPalabraDificultad; // dificultad elegida
            $partida->save();
    
            $userPartida = new UserPartida();
            $userPartida->user_id = $idJugador; 
            $userPartida->partida_id = $partida->id;
            $userPartida->save();
            
            return $partida; 
        });
        // Laravel deshace automáticamente los cambios en caso de excepción durante la transacción.
    }
}
