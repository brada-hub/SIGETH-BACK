<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();

            // Identidad civil
            $table->string('primer_apellido', 100);
            $table->string('segundo_apellido', 100)->nullable();
            $table->string('nombres', 255);
            $table->string('ci', 20)->unique();
            $table->string('ci_expedicion', 10)->nullable()->comment('CB, LP, SC, OR, PT, BN, TJ, PD');

            // Datos demográficos
            $table->date('fecha_nacimiento')->nullable();
            $table->foreignId('genero_id')->nullable()->constrained('generos')->nullOnDelete();
            $table->foreignId('estado_civil_id')->nullable()->constrained('estados_civiles')->nullOnDelete();
            $table->foreignId('nacionalidad_id')->nullable()->constrained('paises')->nullOnDelete();

            // Domicilio
            $table->foreignId('pais_residencia_id')->nullable()->constrained('paises')->nullOnDelete();
            $table->string('departamento_residencia', 100)->nullable();
            $table->string('ciudad_residencia', 100)->nullable();
            $table->text('direccion')->nullable();
            $table->string('calle_avenida', 255)->nullable();
            $table->string('numero_domicilio', 20)->nullable();

            // Contacto
            $table->string('correo_personal', 255)->nullable();
            $table->string('celular_personal', 20)->nullable();
            $table->string('telefono_particular', 20)->nullable();
            $table->string('fax', 20)->nullable();

            // Archivos
            $table->string('foto', 255)->nullable();
            $table->text('resumen')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('primer_apellido');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
