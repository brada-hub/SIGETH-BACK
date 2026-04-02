<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legajo_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('legajo_tabla', 60)->comment('Nombre exacto de la tabla origen');
            $table->bigInteger('legajo_id')->comment('ID del registro en la tabla origen');
            $table->string('tipo_archivo', 60)->comment('Identificador del documento');

            $table->string('archivo_path', 500);
            $table->string('nombre_original', 255);
            $table->string('mime_type', 100);
            $table->integer('tamano_bytes');

            $table->timestamps();

            $table->index(['legajo_tabla', 'legajo_id'], 'idx_arch_tabla_id');
            $table->index(['legajo_tabla', 'legajo_id', 'tipo_archivo'], 'idx_arch_tabla_id_tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legajo_archivos');
    }
};
