<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Support\Facades\DB;
class Partida extends Model
{
    use HasFactory;

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

    // transforma el tiempo en segundos para utilizar el cronometro 
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
        $tiempoTotalAnterior = Partida::getTiempoPartidaCronometro($this->tiempo_jugado); // tiempo anterior jugado
        // tiempo jugado hasta el momento
        $tiempoTotalNuevo = Partida::getNuevoTiempoJugado($tiempoInicio, $tiempoFin, $tiempoTotalAnterior);
        $this->tiempo_jugado = $tiempoTotalNuevo;

        $actualizado = $this->actualizarPartida($this);

        if (!$actualizado) {
            // hubo un error al actualizar 
            return;
        } 
    }

    private function actualizarPartida(Partida $partida)
    {
        $this->estado = $partida->estado;
        $this->oportunidades_restantes = $partida->oportunidades_restantes;
        $this->letras_ingresadas = $partida->letras_ingresadas;
        $this->tiempo_jugado = $partida->tiempo_jugado;
        $this->palabra_id = $partida->palabra_id;

        return $this->save();
    }

    /*
        Ranking jugadores con tiempos insumidos en acertar palabras
        Discriminado por dificultad elegida
    */
    public static function obtenerRankingPartidas($dificultad)
    {
        // Obtener el ID de la dificultad
        $idDificultad = DB::table('dificultad')->where('nombre_dificultad', $dificultad)->value('id');

        // Realizar la consulta para obtener el ranking de las partidas
        $rankingPartidas = Partida::with(['usuarios' => function ($query) {
                $query->select('users.name');
            }])
            ->select('id', 'tiempo_jugado') // Seleccionar el tiempo jugado desde el modelo Partida
            ->where('estado', 'victoria')
            ->whereHas('palabra', function ($query) use ($idDificultad) {
                $query->where('dificultad_id', $idDificultad);
            })
            ->orderBy('tiempo_jugado')
            ->limit(5)
            ->get();

        return $rankingPartidas;
    }

}
