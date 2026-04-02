<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_posgrado', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Diplomado, Especialidad, Maestría, Doctorado, Postdoctorado');
            $table->tinyInteger('orden')->comment('Jerarquía para baremos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_posgrado');
    }
};
