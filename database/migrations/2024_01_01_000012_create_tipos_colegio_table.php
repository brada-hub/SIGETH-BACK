<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_colegio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->comment('Fiscal, Privado, Urbano, Rural');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_colegio');
    }
};
