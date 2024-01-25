<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Palabra extends Model
{
    use HasFactory;

    /*
            $table->id();
            $table->string('palabra');
            $table->unsignedBigInteger('dificultad'); 
            $table->timestamps();
            $table->foreign('dificultad')->references('id')->on('dificultad');  
    */
    protected $fillable = [
        'palabra',
        'dificultad'
    ];

    public function dificultad(): BelongsTo
    {
        return $this->belongsTo(Dificultad::class);
    }

    public function partidas(): HasMany
    {
        return $this->hasMany(Partida::class);
    }

    // validacion de la longitud de la palabra antes de que se cree el nuevo registro
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($palabra) {
            $minLength = $palabra->dificultad->longitud_minima;
            $maxLength = $palabra->dificultad->longitud_maxima;

            if (strlen($palabra->palabra) < $minLength || strlen($palabra->palabra) > $maxLength) {
                // Lanza una excepción o maneja el error según tus necesidades
                throw new \Exception('La longitud de la palabra no cumple con los requisitos de la dificultad.');
            }
        });
    }
}
