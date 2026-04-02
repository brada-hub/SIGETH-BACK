<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_participacion_evento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Ponente, Asistente, Organizador, Moderador');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_participacion_evento');
    }
};
