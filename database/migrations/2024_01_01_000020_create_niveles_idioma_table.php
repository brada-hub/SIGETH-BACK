<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles_idioma', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20)->comment('B=Básico, R=Regular, N=Nativo/Bien');
            $table->tinyInteger('orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles_idioma');
    }
};
