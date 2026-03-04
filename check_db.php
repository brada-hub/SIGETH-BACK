<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Tables in sso_db ===\n";
$tables = \DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $arr = (array)$t;
    echo array_values($arr)[0] . "\n";
}

echo "\n=== personal_access_tokens count ===\n";
$count = \DB::table('personal_access_tokens')->count();
echo "Tokens: $count\n";

echo "\n=== Recent tokens ===\n";
$tokens = \DB::table('personal_access_tokens')->orderBy('id', 'desc')->take(3)->get();
foreach ($tokens as $t) {
    echo "ID: {$t->id} | tokenable_id: {$t->tokenable_id} | name: {$t->name} | created: {$t->created_at}\n";
}

echo "\n=== Users table columns ===\n";
$cols = \DB::select('SHOW COLUMNS FROM users');
foreach ($cols as $c) {
    echo "{$c->Field} ({$c->Type})\n";
}

echo "\n=== Check if roles table exists ===\n";
try {
    $roles = \DB::table('roles')->get();
    echo "Roles found: " . count($roles) . "\n";
    foreach ($roles as $r) {
        echo "  ID: {$r->id} | " . ($r->nombre ?? $r->name ?? 'unknown') . "\n";
    }
} catch (\Exception $e) {
    echo "No roles table: " . $e->getMessage() . "\n";
}
