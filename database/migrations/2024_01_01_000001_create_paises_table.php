<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paises', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('codigo', 5)->unique()->comment('BOL, PER, ARG...');
            $table->tinyInteger('activo')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paises');
    }
};
