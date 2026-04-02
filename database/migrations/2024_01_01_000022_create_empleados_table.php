<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('persona_id')->unique()->constrained('personas')->onDelete('cascade');

            // Contacto institucional
            $table->string('correo_institucional', 255)->unique()->nullable();
            $table->string('celular_institucional', 20)->nullable();
            $table->string('telefono_trabajo', 20)->nullable();
            $table->string('celular_corporativo', 20)->nullable();

            // Ubicación laboral
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->foreignId('area_trabajo_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->foreignId('area_dependencia_id')->nullable()->constrained('areas')->nullOnDelete();

            // Seguridad social
            $table->string('num_seguro_cns', 50)->nullable();
            $table->string('num_seguro_afp', 50)->nullable();
            $table->foreignId('tipo_seguro_id')->nullable()->constrained('tipos_seguro')->nullOnDelete();

            // Planilla
            $table->foreignId('tipo_planilla_id')->nullable()->constrained('tipos_planilla')->nullOnDelete();

            $table->date('fecha_ingreso')->nullable();
            $table->tinyInteger('activo')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index('activo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
