<?php
/**
 * RESET COMPLETO DE SSO
 * Este script limpia y reconfigura toda la base de datos SSO
 * para que los permisos y accesos funcionen correctamente.
 *
 * USO: php reset_sso_completo.php
 */

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "\n" . str_repeat('=', 60) . "\n";
echo "  RESET COMPLETO DE SSO - SIGETH\n";
echo str_repeat('=', 60) . "\n\n";

// =====================================================
// 1. LIMPIAR TABLAS (Excepto users y sedes)
// =====================================================
echo "PASO 1: Limpiando tablas...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Limpiar tablas de permisos y relaciones
DB::table('model_has_permissions')->truncate();
DB::table('role_has_permissions')->truncate();
DB::table('permissions')->truncate();
DB::table('application_user')->truncate();
DB::table('personal_access_tokens')->truncate();
echo "  ✓ Tablas de permisos, accesos y tokens limpiadas\n";

// =====================================================
// 2. VERIFICAR/CREAR APLICACIONES
// =====================================================
echo "\nPASO 2: Configurando aplicaciones...\n";

// Verificar la tabla applications
$appColumns = DB::select("SHOW COLUMNS FROM applications");
$columnNames = array_map(fn($c) => $c->Field, $appColumns);
echo "  Columnas de applications: " . implode(', ', $columnNames) . "\n";

// Asegurarnos de que la columna 'key' existe
if (!in_array('key', $columnNames)) {
    DB::statement("ALTER TABLE applications ADD COLUMN `key` VARCHAR(191) NULL AFTER id");
    echo "  ✓ Columna 'key' añadida a applications\n";
}

// Limpiar y recrear aplicaciones
DB::table('applications')->truncate();

$sigva = DB::table('applications')->insertGetId([
    'key' => 'sigva',
    'nombre' => 'SIGVA',
    'url' => 'https://sigva.xpertiaplus.com',
    'icono' => 'beach_access',
    'color' => 'teal',
    'descripcion' => 'Sistema de Gestión de Vacaciones y Ausencias',
    'activo' => 1,
    'created_at' => now(),
    'updated_at' => now()
]);
echo "  ✓ App SIGVA (ID: $sigva) creada\n";

$sispo = DB::table('applications')->insertGetId([
    'key' => 'sispo',
    'nombre' => 'SISPO',
    'url' => 'https://postulacionesunitepc.xpertiaplus.com',
    'icono' => 'assignment_ind',
    'color' => 'purple',
    'descripcion' => 'Sistema de Postulaciones y Selección de Personal',
    'activo' => 1,
    'created_at' => now(),
    'updated_at' => now()
]);
echo "  ✓ App SISPO (ID: $sispo) creada\n";

$sigeth = DB::table('applications')->insertGetId([
    'key' => 'sigeth',
    'nombre' => 'SIGETH',
    'url' => 'https://sigeth.xpertiaplus.com',
    'icono' => 'shield',
    'color' => 'indigo',
    'descripcion' => 'Sistema de Gestión de Talento Humano - Portal Central SSO',
    'activo' => 1,
    'created_at' => now(),
    'updated_at' => now()
]);
echo "  ✓ App SIGETH (ID: $sigeth) creada\n";

// =====================================================
// 3. VERIFICAR/CREAR ROLES
// =====================================================
echo "\nPASO 3: Configurando roles...\n";

// Verificar columnas de roles
$rolColumns = DB::select("SHOW COLUMNS FROM roles");
$rolColumnNames = array_map(fn($c) => $c->Field, $rolColumns);
echo "  Columnas de roles: " . implode(', ', $rolColumnNames) . "\n";

// Agregar columnas que falten
if (!in_array('guard_name', $rolColumnNames)) {
    DB::statement("ALTER TABLE roles ADD COLUMN guard_name VARCHAR(191) DEFAULT 'api' AFTER nombre");
    echo "  ✓ Columna guard_name añadida\n";
}
if (!in_array('application_id', $rolColumnNames)) {
    DB::statement("ALTER TABLE roles ADD COLUMN application_id BIGINT UNSIGNED NULL AFTER guard_name");
    echo "  ✓ Columna application_id añadida\n";
}
if (!in_array('activo', $rolColumnNames)) {
    DB::statement("ALTER TABLE roles ADD COLUMN activo TINYINT(1) DEFAULT 1 AFTER application_id");
    echo "  ✓ Columna activo añadida\n";
}

// Verificar roles existentes
$existingRoles = DB::table('roles')->pluck('nombre', 'id');
echo "  Roles existentes: " . $existingRoles->implode(', ') . "\n";

// Crear/actualizar rol Administrador
$adminRole = DB::table('roles')->where('nombre', 'LIKE', '%ADMIN%')->first()
          ?? DB::table('roles')->where('nombre', 'LIKE', '%Admin%')->first();

if (!$adminRole) {
    $adminRoleId = DB::table('roles')->insertGetId([
        'nombre' => 'Administrador',
        'descripcion' => 'Acceso total a todos los sistemas',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "  ✓ Rol Administrador creado (ID: $adminRoleId)\n";
} else {
    $adminRoleId = $adminRole->id;
    echo "  ✓ Rol Administrador existente (ID: $adminRoleId)\n";
}

// Crear rol RRHH si no existe
$rrhhRole = DB::table('roles')->where('nombre', 'LIKE', '%RRHH%')->first();
if (!$rrhhRole) {
    DB::table('roles')->insert(['nombre' => 'RRHH', 'descripcion' => 'Gestión de Personal', 'created_at' => now(), 'updated_at' => now()]);
    echo "  ✓ Rol RRHH creado\n";
}

$evalRole = DB::table('roles')->where('nombre', 'LIKE', '%Evaluador%')->first();
if (!$evalRole) {
    DB::table('roles')->insert(['nombre' => 'Evaluador', 'descripcion' => 'Evaluación de Postulantes', 'created_at' => now(), 'updated_at' => now()]);
    echo "  ✓ Rol Evaluador creado\n";
}

$userRole = DB::table('roles')->where('nombre', 'LIKE', '%USUARIO%')
          ->orWhere('nombre', 'LIKE', '%Usuario%')
          ->first();
if (!$userRole) {
    DB::table('roles')->insert(['nombre' => 'Usuario', 'descripcion' => 'Acceso limitado', 'created_at' => now(), 'updated_at' => now()]);
    echo "  ✓ Rol Usuario creado\n";
}

// =====================================================
// 4. CREAR PERMISOS CORRECTOS
// =====================================================
echo "\nPASO 4: Creando permisos con application_id correcto...\n";

// Verificar columnas de permissions
$permColumns = DB::select("SHOW COLUMNS FROM permissions");
$permColumnNames = array_map(fn($c) => $c->Field, $permColumns);
echo "  Columnas de permissions: " . implode(', ', $permColumnNames) . "\n";

if (!in_array('application_id', $permColumnNames)) {
    DB::statement("ALTER TABLE permissions ADD COLUMN application_id BIGINT UNSIGNED NULL AFTER guard_name");
    echo "  ✓ Columna application_id añadida a permissions\n";
}

// Permisos de SIGVA (vacaciones)
$sigvaPerms = ['solicitudes', 'vacaciones_dashboard', 'calendario', 'reportes', 'documentacion', 'feriados', 'empleados'];
foreach ($sigvaPerms as $p) {
    DB::table('permissions')->insert([
        'name' => $p,
        'guard_name' => 'api',
        'application_id' => $sigva,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "  ✓ Permiso '$p' → SIGVA (ID: $sigva)\n";
}

// Permisos de SISPO (postulaciones)
$sispoPerms = ['dashboard', 'postulaciones', 'convocatorias', 'evaluaciones', 'sedes', 'cargos', 'requisitos', 'usuarios', 'roles', 'ver_mi_legajo', 'ver_todo_personal'];
foreach ($sispoPerms as $p) {
    DB::table('permissions')->insert([
        'name' => $p,
        'guard_name' => 'api',
        'application_id' => $sispo,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "  ✓ Permiso '$p' → SISPO (ID: $sispo)\n";
}

// Permisos de SIGETH (SSO admin)
$sigethPerms = ['gestionar_usuarios', 'gestionar_roles', 'gestionar_aplicaciones', 'gestionar_sedes'];
foreach ($sigethPerms as $p) {
    DB::table('permissions')->insert([
        'name' => $p,
        'guard_name' => 'api',
        'application_id' => $sigeth,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "  ✓ Permiso '$p' → SIGETH (ID: $sigeth)\n";
}

// Permiso especial 'all' (no pertenece a ninguna app específica)
DB::table('permissions')->insert([
    'name' => 'all',
    'guard_name' => 'api',
    'application_id' => null,
    'created_at' => now(),
    'updated_at' => now()
]);
echo "  ✓ Permiso 'all' (global) creado\n";

// =====================================================
// 5. ASIGNAR TODOS LOS PERMISOS AL ROL ADMINISTRADOR
// =====================================================
echo "\nPASO 5: Asignando permisos al rol Administrador...\n";

$allPermIds = DB::table('permissions')->pluck('id');
foreach ($allPermIds as $permId) {
    DB::table('role_has_permissions')->insert([
        'permission_id' => $permId,
        'role_id' => $adminRoleId
    ]);
}
echo "  ✓ " . $allPermIds->count() . " permisos asignados al rol Administrador\n";

// =====================================================
// 6. ASIGNAR PERMISOS ESPECÍFICOS AL ROL RRHH (ambos sistemas)
// =====================================================
$rrhhRole = DB::table('roles')->where('nombre', 'LIKE', '%RRHH%')->first();
if ($rrhhRole) {
    $rrhhPermNames = ['solicitudes', 'vacaciones_dashboard', 'calendario', 'reportes', 'empleados', 'dashboard', 'postulaciones', 'convocatorias'];
    $rrhhPermIds = DB::table('permissions')->whereIn('name', $rrhhPermNames)->pluck('id');
    foreach ($rrhhPermIds as $pid) {
        DB::table('role_has_permissions')->insert([
            'permission_id' => $pid,
            'role_id' => $rrhhRole->id
        ]);
    }
    echo "  ✓ Permisos RRHH asignados\n";
}

// =====================================================
// 7. VINCULAR USUARIOS CON APLICACIONES
// =====================================================
echo "\nPASO 6: Vinculando usuarios con aplicaciones...\n";

$users = DB::table('users')->get();
echo "  Total usuarios: " . $users->count() . "\n";

foreach ($users as $user) {
    // Asegurar que todos los usuarios admin están en rol admin
    if (!$user->rol_id) {
        DB::table('users')->where('id', $user->id)->update(['rol_id' => $adminRoleId]);
        echo "  ⚠ Usuario #{$user->id} ({$user->nombres}) sin rol, asignado a Administrador\n";
    }

    // Vincular a las 3 aplicaciones
    DB::table('application_user')->insert([
        ['user_id' => $user->id, 'application_id' => $sigva, 'created_at' => now(), 'updated_at' => now()],
        ['user_id' => $user->id, 'application_id' => $sispo, 'created_at' => now(), 'updated_at' => now()],
        ['user_id' => $user->id, 'application_id' => $sigeth, 'created_at' => now(), 'updated_at' => now()],
    ]);
    echo "  ✓ Usuario #{$user->id} ({$user->nombres}) vinculado a SIGVA, SISPO y SIGETH\n";
}

// =====================================================
// 8. ASIGNAR PERMISOS DIRECTOS A ADMINS
// =====================================================
echo "\nPASO 7: Asignando permisos directos a administradores...\n";

$admins = DB::table('users')->where('rol_id', $adminRoleId)->get();
foreach ($admins as $admin) {
    foreach ($allPermIds as $pid) {
        DB::table('model_has_permissions')->insert([
            'permission_id' => $pid,
            'model_type' => 'App\\Models\\User',
            'model_id' => $admin->id
        ]);
    }
    echo "  ✓ Admin #{$admin->id} ({$admin->nombres}) → todos los permisos directos\n";
}

DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// =====================================================
// 9. VERIFICACIÓN FINAL
// =====================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  VERIFICACIÓN FINAL\n";
echo str_repeat('=', 60) . "\n\n";

$apps = DB::table('applications')->get();
echo "Aplicaciones (" . $apps->count() . "):\n";
foreach ($apps as $a) {
    $permsCount = DB::table('permissions')->where('application_id', $a->id)->count();
    $usersCount = DB::table('application_user')->where('application_id', $a->id)->count();
    echo "  [{$a->id}] {$a->nombre} (key: {$a->key}) → {$permsCount} permisos, {$usersCount} usuarios\n";
}

echo "\nPermisos por aplicación:\n";
$perms = DB::table('permissions')
    ->leftJoin('applications', 'permissions.application_id', '=', 'applications.id')
    ->select('permissions.name', 'applications.nombre as app_nombre')
    ->get();
foreach ($perms as $p) {
    echo "  {$p->name} → " . ($p->app_nombre ?? 'GLOBAL') . "\n";
}

echo "\nUsuarios y sus accesos:\n";
$users = DB::table('users')->get();
foreach ($users as $u) {
    $role = DB::table('roles')->where('id', $u->rol_id)->first();
    $appNames = DB::table('application_user')
        ->join('applications', 'application_user.application_id', '=', 'applications.id')
        ->where('application_user.user_id', $u->id)
        ->pluck('applications.nombre');
    $directPerms = DB::table('model_has_permissions')
        ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
        ->where('model_has_permissions.model_id', $u->id)
        ->pluck('permissions.name');
    echo "  [{$u->id}] {$u->nombres} | Rol: " . ($role->nombre ?? 'SIN ROL') . " | Apps: " . $appNames->implode(', ') . " | Permisos directos: " . $directPerms->count() . "\n";
}

echo "\n" . str_repeat('=', 60) . "\n";
echo "  ✅ RESET COMPLETO TERMINADO\n";
echo "  Ahora debes:\n";
echo "  1. Subir los cambios de SISPO-FRONT al servidor\n";
echo "  2. Subir los cambios de SISPO-BACK al servidor\n";
echo "  3. Ejecutar este script en el servidor de producción\n";
echo "  4. Limpiar cache del navegador\n";
echo str_repeat('=', 60) . "\n\n";
