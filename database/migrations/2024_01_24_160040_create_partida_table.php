<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partida', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['victoria', 'interrumpida', 'derrota']);  
            $table->integer('oportunidades_restantes')->default(10);  
            $table->string('letras_ingresadas'); //acertadas y falladas
            $table->timestamp('tiempo_jugado'); // modificar formato en Model
            $table->unsignedBigInteger('palabra'); 
            $table->timestamps(); //fecha creacion
            $table->foreign('palabra')->references('id')->on('palabra'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partida');
    }
};
