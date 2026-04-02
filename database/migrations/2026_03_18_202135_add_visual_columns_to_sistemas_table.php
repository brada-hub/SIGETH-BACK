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
        Schema::table('sistemas', function (Blueprint $table) {
            if (!Schema::hasColumn('sistemas', 'icono')) {
                $table->string('icono', 50)->nullable()->after('url');
            }
            if (!Schema::hasColumn('sistemas', 'color')) {
                $table->string('color', 50)->nullable()->after('icono');
            }
            if (!Schema::hasColumn('sistemas', 'descripcion')) {
                $table->string('descripcion', 255)->nullable()->after('color');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sistemas', function (Blueprint $table) {
            $table->dropColumn(['icono', 'color', 'descripcion']);
        });
    }
};
