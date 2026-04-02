<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_produccion_intelectual', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Artículo científico, Libro, Capítulo de libro, Ponencia, Tesis dirigida, Patente');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_produccion_intelectual');
    }
};
