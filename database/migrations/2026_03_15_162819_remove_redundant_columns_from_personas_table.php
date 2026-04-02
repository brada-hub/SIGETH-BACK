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
        Schema::table('personas', function (Blueprint $table) {
            $table->dropForeign(['pais_residencia_id']);
            $table->dropColumn(['pais_residencia_id', 'telefono_particular', 'fax']);
        });
    }

    public function down(): void
    {
        Schema::table('personas', function (Blueprint $table) {
            $table->foreignId('pais_residencia_id')->nullable()->constrained('paises')->nullOnDelete();
            $table->string('telefono_particular', 20)->nullable();
            $table->string('fax', 20)->nullable();
        });
    }
};
