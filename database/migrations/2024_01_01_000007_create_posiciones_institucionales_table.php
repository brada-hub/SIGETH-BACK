<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posiciones_institucionales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Administrativo, Autoridad, Consultor en Línea, Docente, Técnico, Directorio');
            $table->tinyInteger('activo')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posiciones_institucionales');
    }
};
