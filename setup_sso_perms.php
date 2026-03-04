<?php

$sso = \App\Models\Application::firstOrCreate(
    ['nombre' => 'SSO'],
    ['url' => 'http://localhost:9000', 'icono' => 'admin_panel_settings', 'color' => 'blue', 'descripcion' => 'Panel Central Administrador']
);

\DB::table('permissions')->insertOrIgnore([
    ['name' => 'sistemas', 'guard_name' => 'api', 'system_id' => $sso->id]
]);

\DB::table('permissions')->whereIn('name', ['usuarios', 'roles', 'sistemas', 'sedes'])
    ->update(['system_id' => $sso->id]);

echo "SSO application and permissions updated.\n";
