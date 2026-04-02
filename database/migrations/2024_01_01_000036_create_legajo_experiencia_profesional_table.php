<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legajo_experiencia_profesional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->string('empresa_institucion', 255);
            $table->string('cargo', 255);
            $table->text('actividades')->nullable();
            $table->string('dedicacion', 50)->nullable();
            $table->string('ciudad', 150)->nullable();
            $table->foreignId('pais_id')->default(1)->constrained('paises')->onDelete('restrict');
            
            $table->string('fecha_inicio', 20);
            $table->string('fecha_fin', 20)->nullable();
            
            $table->string('archivo_path', 255)->nullable();

            $table->enum('estado', ['pendiente', 'validado', 'observado', 'rechazado'])->default('pendiente');
            $table->text('observacion')->nullable();
            $table->foreignId('validado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validado_at')->nullable();
            
            $table->timestamps();

            $table->index('empleado_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legajo_experiencia_profesional');
    }
};
