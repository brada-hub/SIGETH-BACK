<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legajo_reconocimiento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->string('titulo', 255);
            $table->string('institucion_otorgante', 255);
            $table->string('año', 4);
            $table->text('descripcion')->nullable()->comment('Detalle del mérito');
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
        Schema::dropIfExists('legajo_reconocimiento');
    }
};
