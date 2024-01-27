<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dificultad extends Model
{
    use HasFactory;

    /*
            $table->id();
            $table->string('nombre_dificultad');
            $table->integer('longitud_minima');
            $table->integer('longitud_maxima');
            $table->timestamps();
    */
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
