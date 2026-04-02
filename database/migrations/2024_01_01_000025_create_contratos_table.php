<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');
            $table->foreignId('tipo_contrato_id')->constrained('tipos_contrato')->onDelete('cascade');
            $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete();

            $table->string('nro_contrato', 100)->unique()->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable()->comment('NULL = indefinido');
            $table->decimal('salario', 10, 2)->nullable();
            $table->enum('estado', ['Vigente', 'Finalizado', 'Rescindido'])->default('Vigente');
            $table->text('observaciones')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('empleado_id');
            $table->index('estado');
            $table->index(['empleado_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
