<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveles_academicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->comment('Técnico Superior, Licenciatura');
            $table->tinyInteger('orden')->comment('1=Técnico, 2=Licenciatura — jerarquía para baremos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niveles_academicos');
    }
};
