<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->unique()->constrained('empleados')->onDelete('cascade');

            $table->string('username', 100)->unique();
            $table->string('password', 255);

            $table->tinyInteger('activo')->default(1);
            $table->tinyInteger('must_change_password')->default(0);
            $table->timestamp('ultimo_login')->nullable();
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
