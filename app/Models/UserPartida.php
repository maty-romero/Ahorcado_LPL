<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPartida extends Model
{
    use HasFactory;
    protected $table = 'user_partida';
    protected $fillable = [
        'user_id',
        'partida_id'
    ];
}
