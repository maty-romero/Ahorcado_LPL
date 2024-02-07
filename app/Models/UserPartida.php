<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPartida extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_partida';
    protected $fillable = [
        'user_id',
        'partida_id'
    ];
}
