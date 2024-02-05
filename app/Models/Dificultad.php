<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dificultad extends Model
{
    use HasFactory;
    protected $table = 'dificultad';
    protected $fillable = [
        'nombre_dificultad',
        'longitud_minima',
        'longitud_maxima',
    ];

    public function palabras(): HasMany
    {
        return $this->hasMany(Palabra::class);
    }
}
