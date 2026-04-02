<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. tipos_personal
        if (!Schema::hasTable('tipos_personal')) {
            Schema::create('tipos_personal', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100)->unique();
                $table->string('descripcion', 255)->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });

            // Seed inicial
            \Illuminate\Support\Facades\DB::table('tipos_personal')->insert([
                ['nombre' => 'Docente', 'descripcion' => 'Personal docente', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Administrativo', 'descripcion' => 'Personal administrativo', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
                ['nombre' => 'Técnico', 'descripcion' => 'Personal técnico', 'activo' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 3. settings
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });

            \Illuminate\Support\Facades\DB::table('settings')->insert([
                ['key' => 'registro_directo_enabled', 'value' => 'true', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 6. Asegurar que 'cargos' tiene tipo_personal_id si no lo tiene
        if (Schema::hasTable('cargos') && !Schema::hasColumn('cargos', 'tipo_personal_id')) {
            Schema::table('cargos', function (Blueprint $table) {
                $table->foreignId('tipo_personal_id')->nullable()->after('nombre')->constrained('tipos_personal')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        // No borramos tipos_personal ni alteramos cargos en el down por seguridad de datos existentes
    }
};
