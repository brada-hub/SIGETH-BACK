<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_herederos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('persona_id')->constrained('personas')->onDelete('cascade');
            $table->foreignId('parentesco_id')->constrained('parentescos')->onDelete('cascade');
            $table->decimal('porcentaje', 5, 2)->comment('Suma debe ser 100 entre todos los herederos');
            $table->tinyInteger('orden')->comment('1 o 2 — máximo 2 herederos');
            $table->tinyInteger('activo')->default(1);
            $table->timestamps();

            $table->unique(['empleado_id', 'orden']);
            $table->unique(['empleado_id', 'persona_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_herederos');
    }
};
