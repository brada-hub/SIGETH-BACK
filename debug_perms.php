<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = App\Models\User::where('ci', '1326')->first();
if ($u) {
    echo "User: {$u->nombres} {$u->apellido_paterno}\n";
    echo "Rol: " . ($u->rol ? $u->rol->nombre : 'sin rol') . "\n";
    echo "Permisos (computed): " . json_encode($u->permisos) . "\n";

    $directPerms = DB::table('model_has_permissions')
        ->where('model_id', $u->id)
        ->where('model_type', 'App\Models\User')
        ->pluck('permission_id')->toArray();
    echo "Direct perm IDs: " . json_encode($directPerms) . "\n";

    $rolePerms = DB::table('role_has_permissions')
        ->where('role_id', $u->rol_id)
        ->pluck('permission_id')->toArray();
    echo "Role perm IDs: " . json_encode($rolePerms) . "\n";
} else {
    echo "User 1326 not found\n";
    $all = DB::table('users')->select('ci','nombres')->get();
    foreach ($all as $x) echo "  {$x->ci} | {$x->nombres}\n";
}
