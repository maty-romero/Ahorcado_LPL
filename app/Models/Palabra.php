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

    
    
}
