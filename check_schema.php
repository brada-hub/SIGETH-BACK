<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SEDES columns ===\n";
$cols = \DB::select('SHOW COLUMNS FROM sedes');
foreach ($cols as $c) echo "  {$c->Field} ({$c->Type}) {$c->Null} default={$c->Default}\n";

echo "\n=== USERS columns ===\n";
$cols = \DB::select('SHOW COLUMNS FROM users');
foreach ($cols as $c) echo "  {$c->Field} ({$c->Type}) {$c->Null} default={$c->Default}\n";

echo "\n=== ROLES columns ===\n";
$cols = \DB::select('SHOW COLUMNS FROM roles');
foreach ($cols as $c) echo "  {$c->Field} ({$c->Type}) {$c->Null} default={$c->Default}\n";

echo "\n=== ROLES data ===\n";
$roles = \DB::table('roles')->get();
foreach ($roles as $r) echo "  ID:{$r->id} | {$r->nombre}\n";

echo "\n=== Sample user ===\n";
$u = \DB::table('users')->where('ci', '13260003')->first();
print_r($u);
