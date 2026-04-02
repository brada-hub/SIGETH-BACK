<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar relación con empleado si existe (para migrar a persona)
            if (Schema::hasColumn('users', 'empleado_id')) {
                // Intentar borrar la FK si existe
                try {
                    $table->dropForeign(['empleado_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('empleado_id');
            }

            // Agregar relación con persona
            if (!Schema::hasColumn('users', 'persona_id')) {
                $table->foreignId('persona_id')->nullable()->unique()->constrained('personas')->onDelete('cascade');
            }

            // Agregar columnas necesarias para el frontend y caché de datos
            if (!Schema::hasColumn('users', 'ci')) {
                $table->string('ci', 20)->nullable()->unique()->after('persona_id');
            }
            if (!Schema::hasColumn('users', 'nombres')) {
                $table->string('nombres', 100)->nullable()->after('ci');
            }
            if (!Schema::hasColumn('users', 'apellidos')) {
                $table->string('apellidos', 150)->nullable()->after('nombres');
            }
            if (!Schema::hasColumn('users', 'apellido_paterno')) {
                $table->string('apellido_paterno', 100)->nullable()->after('apellidos');
            }
            if (!Schema::hasColumn('users', 'apellido_materno')) {
                $table->string('apellido_materno', 100)->nullable()->after('apellido_paterno');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email', 100)->nullable()->after('username');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'sede_id')) {
                $table->foreignId('sede_id')->nullable()->constrained('sedes')->nullOnDelete()->after('phone');
            }
            if (!Schema::hasColumn('users', 'jurisdiccion')) {
                $table->json('jurisdiccion')->nullable()->after('sede_id');
            }
            if (!Schema::hasColumn('users', 'rol_id')) {
                $table->foreignId('rol_id')->nullable()->constrained('roles')->nullOnDelete()->after('jurisdiccion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropForeign(['rol_id']); } catch (\Exception $e) {}
            try { $table->dropForeign(['sede_id']); } catch (\Exception $e) {}
            try { $table->dropForeign(['persona_id']); } catch (\Exception $e) {}
            
            $table->dropColumn([
                'persona_id', 'ci', 'nombres', 'apellidos', 'apellido_paterno',
                'apellido_materno', 'email', 'phone', 'sede_id', 'jurisdiccion', 'rol_id'
            ]);
            
            if (!Schema::hasColumn('users', 'empleado_id')) {
                $table->foreignId('empleado_id')->nullable()->unique()->constrained('empleados')->onDelete('cascade');
            }
        });
    }
};
