<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Columnas de identidad extendida
            $table->string('apellido_paterno')->nullable()->after('nombres');
            $table->string('apellido_materno')->nullable()->after('apellido_paterno');

            // Hacer email nullable (algunos usuarios no tienen email)
            $table->string('email')->nullable()->change();

            // Rol (FK a roles)
            $table->foreignId('rol_id')->nullable()->after('avatar')
                  ->constrained('roles')->onDelete('set null');

            // Google Auth
            $table->string('google_id')->nullable()->unique()->after('rol_id');

            // Jurisdiccion (JSON array de IDs de sedes permitidas)
            $table->json('jurisdiccion')->nullable()->after('google_id');

            // Estado y seguridad
            $table->boolean('activo')->default(true)->after('jurisdiccion');
            $table->boolean('must_change_password')->default(false)->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn([
                'apellido_paterno', 'apellido_materno',
                'rol_id', 'google_id', 'jurisdiccion',
                'activo', 'must_change_password'
            ]);
        });
    }
};
