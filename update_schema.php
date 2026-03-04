<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

// ============================================================
// 1. Actualizar tabla SEDES: agregar sigla, departamento, activo
// ============================================================
if (!Schema::hasColumn('sedes', 'sigla')) {
    Schema::table('sedes', function (Blueprint $table) {
        $table->string('sigla', 10)->nullable()->after('nombre');
    });
    echo "✅ Columna 'sigla' agregada a sedes\n";
}
if (!Schema::hasColumn('sedes', 'departamento')) {
    Schema::table('sedes', function (Blueprint $table) {
        $table->string('departamento')->nullable()->after('sigla');
    });
    echo "✅ Columna 'departamento' agregada a sedes\n";
}
if (!Schema::hasColumn('sedes', 'activo')) {
    Schema::table('sedes', function (Blueprint $table) {
        $table->boolean('activo')->default(true)->after('ciudad');
    });
    echo "✅ Columna 'activo' agregada a sedes\n";
}

// Actualizar sedes existentes con siglas y departamentos
$sedesData = [
    'LA PAZ'          => ['sigla' => 'LPZ', 'departamento' => 'LA PAZ'],
    'EL ALTO'         => ['sigla' => 'EAL', 'departamento' => 'LA PAZ'],
    'COCHABAMBA'       => ['sigla' => 'COC', 'departamento' => 'COCHABAMBA'],
    'IVIRGARZAMA'      => ['sigla' => 'IVI', 'departamento' => 'COCHABAMBA'],
    'GUAYARAMERIN'     => ['sigla' => 'GYA', 'departamento' => 'BENI'],
    'SANTA CRUZ'       => ['sigla' => 'SCZ', 'departamento' => 'SANTA CRUZ'],
    'PUERTO QUIJARRO'  => ['sigla' => 'PQJ', 'departamento' => 'SANTA CRUZ'],
    'COBIJA'           => ['sigla' => 'CBJ', 'departamento' => 'PANDO'],
    'NACIONAL'         => ['sigla' => 'NAC', 'departamento' => 'NACIONAL'],
];

foreach ($sedesData as $nombre => $data) {
    \DB::table('sedes')->where('nombre', $nombre)->update($data);
}
echo "✅ Sedes actualizadas con siglas y departamentos\n";

// ============================================================
// 2. Actualizar tabla USERS: apellido_paterno, apellido_materno, jurisdiccion
// ============================================================
if (!Schema::hasColumn('users', 'apellido_paterno')) {
    Schema::table('users', function (Blueprint $table) {
        $table->string('apellido_paterno')->nullable()->after('apellidos');
    });
    echo "✅ Columna 'apellido_paterno' agregada a users\n";
}
if (!Schema::hasColumn('users', 'apellido_materno')) {
    Schema::table('users', function (Blueprint $table) {
        $table->string('apellido_materno')->nullable()->after('apellido_paterno');
    });
    echo "✅ Columna 'apellido_materno' agregada a users\n";
}
if (!Schema::hasColumn('users', 'jurisdiccion')) {
    Schema::table('users', function (Blueprint $table) {
        $table->json('jurisdiccion')->nullable()->after('sede_id');
    });
    echo "✅ Columna 'jurisdiccion' (JSON) agregada a users\n";
}

// Migrar apellidos existentes a apellido_paterno
$users = \DB::table('users')->whereNotNull('apellidos')->get();
foreach ($users as $user) {
    $parts = explode(' ', $user->apellidos, 2);
    \DB::table('users')->where('id', $user->id)->update([
        'apellido_paterno' => $parts[0] ?? null,
        'apellido_materno' => $parts[1] ?? null,
    ]);
}
echo "✅ Apellidos migrados a apellido_paterno y apellido_materno\n";

echo "\n🎉 Schema actualizado correctamente!\n";
