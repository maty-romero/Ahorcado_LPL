<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dificultad extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'dificultad';
    protected $fillable = [
        'nombre_dificultad',
        'longitud_minima',
        'longitud_maxima',
    ];

    public function palabras(): HasMany
    {
        return $this->hasMany(Palabra::class, 'dificultad_id');
    }
}
