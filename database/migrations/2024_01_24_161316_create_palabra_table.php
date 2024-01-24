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
        Schema::create('palabra', function (Blueprint $table) {
            $table->id();
            $table->string('palabra');
            $table->unsignedBigInteger('dificultad'); 
            $table->timestamps();
            $table->foreign('dificultad')->references('id')->on('dificultad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('palabra');
    }
};
