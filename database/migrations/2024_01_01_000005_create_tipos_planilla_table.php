<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_planilla', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Administrativos, Docentes, Directorio, Autoridades, Jefes de Carrera');
            $table->tinyInteger('activo')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_planilla');
    }
};
