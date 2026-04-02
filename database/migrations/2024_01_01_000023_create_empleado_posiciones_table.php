<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleado_posiciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('posicion_id')->constrained('posiciones_institucionales')->onDelete('cascade');
            $table->tinyInteger('orden')->comment('1 = principal, 2 = secundaria (máx 2)');
            $table->tinyInteger('activo')->default(1);
            $table->timestamps();

            $table->unique(['empleado_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empleado_posiciones');
    }
};
