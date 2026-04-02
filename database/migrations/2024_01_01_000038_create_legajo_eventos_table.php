<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legajo_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');

            $table->string('nombre_evento', 255);
            $table->string('organizador', 255);
            $table->string('ciudad', 100)->nullable();
            $table->foreignId('pais_id')->default(1)->constrained('paises')->onDelete('restrict');
            $table->string('año', 4);
            $table->string('tipo_participacion', 100)->comment('Asistente, Expositor, Ponente...');
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
        Schema::dropIfExists('legajo_eventos');
    }
};
