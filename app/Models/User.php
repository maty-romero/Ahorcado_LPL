<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'fecha_nacimiento',
        'pais_residencia'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function partidas()
    {
        return $this->belongsToMany(Partida::class, 'user_partida', 'user_id', 'partida_id');
    }

    public function getEstadisticas()
    {
        // Cantidad palabras adivinadas y tiempos min y max por dificultad
        $estadisticas = DB::table('partida as p')
        ->select('d.nombre_dificultad', 
            DB::raw('COUNT(p.id) as cantidad'), 
            DB::raw('MIN(p.tiempo_jugado) as tiempo_minimo'), 
            DB::raw('MAX(p.tiempo_jugado) as tiempo_maximo'))
        ->join('user_partida as up', 'p.id', '=', 'up.partida_id')
        ->join('users as u', 'up.user_id', '=', 'u.id')
        ->join('palabra as pal', 'p.palabra_id', '=', 'pal.id')
        ->join('dificultad as d', 'pal.dificultad_id', '=', 'd.id')
        ->where('p.estado', '=', 'victoria')
        ->where('u.id', '=', $this->id) // Filtro por el ID del usuario autenticado
        ->groupBy('d.nombre_dificultad')
        ->get();

        return $estadisticas;
    }

    
}
