<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tablas = [
        'legajo_bachillerato',
        'legajo_formacion_academica',
        'legajo_posgrado',
        'legajo_experiencia_docencia',
        'legajo_experiencia_profesional',
        'legajo_capacitacion',
        'legajo_eventos',
        'legajo_reconocimiento',
        'legajo_produccion_intelectual',
        'legajo_membresias',
        'legajo_idiomas'
    ];

    public function up(): void
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) use ($tabla) {
                if (Schema::hasColumn($tabla, 'estado')) {
                    $table->dropColumn('estado');
                }
                if (Schema::hasColumn($tabla, 'observacion')) {
                    $table->dropColumn('observacion');
                }
                if (Schema::hasColumn($tabla, 'validado_por')) {
                    // Primero quitar la FK si existe
                    try {
                        $table->dropForeign("{$tabla}_validado_por_foreign");
                    } catch (\Exception $e) {}
                    $table->dropColumn('validado_por');
                }
                if (Schema::hasColumn($tabla, 'validado_at')) {
                    $table->dropColumn('validado_at');
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tablas as $tabla) {
            Schema::table($tabla, function (Blueprint $table) {
                $table->string('estado', 20)->default('pendiente')->after('id');
                $table->text('observacion')->nullable()->after('estado');
                $table->unsignedBigInteger('validado_por')->nullable()->after('observacion');
                $table->timestamp('validado_at')->nullable()->after('validado_por');
                
                $table->foreign('validado_por')->references('id')->on('users')->onDelete('set null');
            });
        }
    }
};
