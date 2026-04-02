<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('sigla', 20)->nullable();
            $table->foreignId('area_padre_id')->nullable()->constrained('areas')->nullOnDelete();
            $table->tinyInteger('activo')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
