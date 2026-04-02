<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('sistema_id')->nullable()->constrained('sistemas')->onDelete('cascade')->comment('NULL = aplica a todos los sistemas');
            $table->tinyInteger('activo')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'rol_id', 'sistema_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
