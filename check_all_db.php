<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== SSO_DB tables ===\n";
$tables = \DB::select('SHOW TABLES FROM sso_db');
foreach ($tables as $t) { $arr = (array)$t; echo "  " . array_values($arr)[0] . "\n"; }

echo "\n=== SIGVA_DB tables ===\n";
$tables = \DB::select('SHOW TABLES FROM sigva_db');
foreach ($tables as $t) { $arr = (array)$t; echo "  " . array_values($arr)[0] . "\n"; }

echo "\n=== SISPO_DB tables ===\n";
$tables = \DB::select('SHOW TABLES FROM sispo_db');
foreach ($tables as $t) { $arr = (array)$t; echo "  " . array_values($arr)[0] . "\n"; }

echo "\n=== Users columns in sso_db ===\n";
$cols = \DB::select('SHOW COLUMNS FROM sso_db.users');
foreach ($cols as $c) { echo "  {$c->Field}\n"; }
