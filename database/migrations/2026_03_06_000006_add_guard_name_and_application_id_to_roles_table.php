<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            if (!Schema::hasColumn('roles', 'guard_name')) {
                $table->string('guard_name')->default('web')->after('nombre');
            }
            if (!Schema::hasColumn('roles', 'application_id')) {
                $table->foreignId('application_id')->nullable()->after('guard_name')->constrained('applications')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropColumn(['guard_name', 'application_id']);
        });
    }
};
