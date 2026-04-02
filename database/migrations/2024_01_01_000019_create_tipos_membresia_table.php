<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_membresia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Colegio Profesional, Sociedad Científica, Red Académica, Asociación');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_membresia');
    }
};
