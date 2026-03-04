<?php
$u = \App\Models\User::where('nombres', 'Admin')->first();
if ($u) {
    $ssoId = \App\Models\Application::where('nombre', 'SSO')->value('id');
    $u->applications()->syncWithoutDetaching([$ssoId => ['role' => 'admin', 'permissions' => json_encode(['all'])]]);

    foreach (['usuarios', 'roles', 'sistemas', 'sedes'] as $perm) {
        $pid = \DB::table('permissions')->where('name', $perm)->value('id');
        if ($pid) {
            \DB::table('model_has_permissions')->insertOrIgnore([
                'permission_id' => $pid,
                'model_type' => 'App\\Models\\User',
                'model_id' => $u->id
            ]);
        }
    }
}
echo "Admin updated\n";
