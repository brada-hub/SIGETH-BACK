<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            if (!Schema::hasColumn('sedes', 'sigla')) {
                $table->string('sigla')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('sedes', 'abreviacion')) {
                $table->string('abreviacion')->nullable()->after('sigla');
            }
            if (!Schema::hasColumn('sedes', 'departamento')) {
                $table->string('departamento')->nullable()->after('abreviacion');
            }
            if (!Schema::hasColumn('sedes', 'activo')) {
                $table->boolean('activo')->default(true)->after('ciudad');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sedes', function (Blueprint $table) {
            $table->dropColumn(['sigla', 'abreviacion', 'departamento', 'activo']);
        });
    }
};
