<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== model_has_permissions exists? ===\n";
$exists = \Schema::hasTable('model_has_permissions');
echo $exists ? "YES\n" : "NO\n";

if ($exists) {
    $cols = \DB::select('SHOW COLUMNS FROM model_has_permissions');
    foreach ($cols as $c) echo "  {$c->Field} ({$c->Type})\n";
    echo "Data count: " . \DB::table('model_has_permissions')->count() . "\n";
}
