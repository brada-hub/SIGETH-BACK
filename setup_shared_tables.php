<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Adding missing columns to sso_db.users ===\n";

// Add rol_id column
try {
    \DB::statement("ALTER TABLE users ADD COLUMN rol_id BIGINT UNSIGNED NULL AFTER sede_id");
    echo "Added rol_id column\n";
} catch (\Exception $e) {
    echo "rol_id: " . (str_contains($e->getMessage(), 'Duplicate') ? 'already exists' : $e->getMessage()) . "\n";
}

// Add activo column
try {
    \DB::statement("ALTER TABLE users ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1 AFTER rol_id");
    echo "Added activo column\n";
} catch (\Exception $e) {
    echo "activo: " . (str_contains($e->getMessage(), 'Duplicate') ? 'already exists' : $e->getMessage()) . "\n";
}

// Add must_change_password column
try {
    \DB::statement("ALTER TABLE users ADD COLUMN must_change_password TINYINT(1) NOT NULL DEFAULT 0 AFTER activo");
    echo "Added must_change_password column\n";
} catch (\Exception $e) {
    echo "must_change_password: " . (str_contains($e->getMessage(), 'Duplicate') ? 'already exists' : $e->getMessage()) . "\n";
}

// Create roles table
echo "\n=== Creating roles table in sso_db ===\n";
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS roles (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(191) NOT NULL,
        descripcion VARCHAR(500) NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");
    echo "Roles table created\n";
} catch (\Exception $e) {
    echo "Roles: " . $e->getMessage() . "\n";
}

// Create permissions table
echo "\n=== Creating permissions table in sso_db ===\n";
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS permissions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(191) NOT NULL,
        guard_name VARCHAR(191) NOT NULL DEFAULT 'api',
        system_id BIGINT UNSIGNED NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");
    echo "Permissions table created\n";
} catch (\Exception $e) {
    echo "Permissions: " . $e->getMessage() . "\n";
}

// Create role_has_permissions table
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS role_has_permissions (
        permission_id BIGINT UNSIGNED NOT NULL,
        role_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (permission_id, role_id)
    )");
    echo "role_has_permissions table created\n";
} catch (\Exception $e) {
    echo "role_has_permissions: " . $e->getMessage() . "\n";
}

// Create model_has_permissions table
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS model_has_permissions (
        permission_id BIGINT UNSIGNED NOT NULL,
        model_type VARCHAR(191) NOT NULL,
        model_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (permission_id, model_id, model_type)
    )");
    echo "model_has_permissions table created\n";
} catch (\Exception $e) {
    echo "model_has_permissions: " . $e->getMessage() . "\n";
}

// Create model_has_roles table
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS model_has_roles (
        role_id BIGINT UNSIGNED NOT NULL,
        model_type VARCHAR(191) NOT NULL,
        model_id BIGINT UNSIGNED NOT NULL,
        PRIMARY KEY (role_id, model_id, model_type)
    )");
    echo "model_has_roles table created\n";
} catch (\Exception $e) {
    echo "model_has_roles: " . $e->getMessage() . "\n";
}

// Create systems table
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS systems (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(191) NOT NULL,
        slug VARCHAR(191) NOT NULL,
        url VARCHAR(500) NULL,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");
    echo "systems table created\n";
} catch (\Exception $e) {
    echo "systems: " . $e->getMessage() . "\n";
}

// Create user_systems pivot table
try {
    \DB::statement("CREATE TABLE IF NOT EXISTS user_systems (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        system_id BIGINT UNSIGNED NOT NULL,
        role_id BIGINT UNSIGNED NULL,
        activo TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    )");
    echo "user_systems table created\n";
} catch (\Exception $e) {
    echo "user_systems: " . $e->getMessage() . "\n";
}

// Now SEED default roles
echo "\n=== Seeding roles ===\n";
$roles = ['Administrador', 'RRHH', 'Evaluador', 'Operador'];
foreach ($roles as $r) {
    \DB::table('roles')->updateOrInsert(['nombre' => $r], ['nombre' => $r, 'created_at' => now(), 'updated_at' => now()]);
    echo "  Role: $r\n";
}

// Set all users to Administrador role
$adminRole = \DB::table('roles')->where('nombre', 'Administrador')->first();
if ($adminRole) {
    \DB::table('users')->whereNull('rol_id')->update(['rol_id' => $adminRole->id]);
    echo "\nAll users without role set to Administrador (ID: {$adminRole->id})\n";
}

// Seed permissions for both systems
echo "\n=== Seeding permissions ===\n";
$sispoPerms = ['dashboard', 'postulaciones', 'convocatorias', 'evaluaciones', 'sedes', 'cargos', 'requisitos', 'usuarios', 'roles', 'ver_mi_legajo', 'ver_todo_personal'];
$sigvaPerms = ['solicitudes', 'vacaciones_dashboard', 'calendario', 'reportes', 'documentacion', 'feriados', 'empleados'];

// Insert systems
\DB::table('systems')->updateOrInsert(['slug' => 'sispo'], ['name' => 'SISPO', 'slug' => 'sispo', 'url' => 'http://localhost:9001', 'created_at' => now(), 'updated_at' => now()]);
\DB::table('systems')->updateOrInsert(['slug' => 'sigva'], ['name' => 'SIGVA', 'slug' => 'sigva', 'url' => 'http://localhost:9002', 'created_at' => now(), 'updated_at' => now()]);
$sispoSystem = \DB::table('systems')->where('slug', 'sispo')->first();
$sigvaSystem = \DB::table('systems')->where('slug', 'sigva')->first();

foreach ($sispoPerms as $p) {
    \DB::table('permissions')->updateOrInsert(
        ['name' => $p, 'guard_name' => 'api'],
        ['name' => $p, 'guard_name' => 'api', 'system_id' => $sispoSystem->id, 'created_at' => now(), 'updated_at' => now()]
    );
    echo "  Perm: $p (SISPO)\n";
}
foreach ($sigvaPerms as $p) {
    \DB::table('permissions')->updateOrInsert(
        ['name' => $p, 'guard_name' => 'api'],
        ['name' => $p, 'guard_name' => 'api', 'system_id' => $sigvaSystem->id, 'created_at' => now(), 'updated_at' => now()]
    );
    echo "  Perm: $p (SIGVA)\n";
}

// Assign ALL permissions to Administrador role
$allPerms = \DB::table('permissions')->pluck('id');
foreach ($allPerms as $permId) {
    \DB::table('role_has_permissions')->updateOrInsert(
        ['permission_id' => $permId, 'role_id' => $adminRole->id],
        ['permission_id' => $permId, 'role_id' => $adminRole->id]
    );
}
echo "\nAll permissions assigned to Administrador role\n";

// Assign all users to both systems
$users = \DB::table('users')->get();
foreach ($users as $u) {
    \DB::table('user_systems')->updateOrInsert(
        ['user_id' => $u->id, 'system_id' => $sispoSystem->id],
        ['user_id' => $u->id, 'system_id' => $sispoSystem->id, 'role_id' => $adminRole->id, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()]
    );
    \DB::table('user_systems')->updateOrInsert(
        ['user_id' => $u->id, 'system_id' => $sigvaSystem->id],
        ['user_id' => $u->id, 'system_id' => $sigvaSystem->id, 'role_id' => $adminRole->id, 'activo' => 1, 'created_at' => now(), 'updated_at' => now()]
    );
}
echo "All users assigned to both systems\n";

echo "\n=== DONE! SSO Database now has all shared tables ===\n";
