<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Dar TODOS los permisos directos al admin (13260003)
$admin = App\Models\User::where('ci', '13260003')->first();
if ($admin) {
    $allPerms = DB::table('permissions')->pluck('id')->toArray();

    // Limpiar permisos actuales
    DB::table('model_has_permissions')
        ->where('model_id', $admin->id)
        ->where('model_type', 'App\Models\User')
        ->delete();

    // Insertar todos
    foreach ($allPerms as $permId) {
        DB::table('model_has_permissions')->insert([
            'permission_id' => $permId,
            'model_type' => 'App\Models\User',
            'model_id' => $admin->id,
        ]);
    }

    echo "✅ Admin {$admin->nombres} ahora tiene " . count($allPerms) . " permisos directos\n";
    echo "Permisos: " . json_encode($admin->fresh()->permisos) . "\n";
}
