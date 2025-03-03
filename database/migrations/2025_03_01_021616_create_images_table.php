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
        Schema::create('images', function (Blueprint $table) {
            $table->id();

            // Creacion de las columnas polimorficas
            $table->morphs('imageable');

            // Nombre de la imagen
            $table->string('name');

            // Ruta del archivo
            $table->string('path');

            // Disco de almacenamiento del archivo
            $table->string('disk');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
