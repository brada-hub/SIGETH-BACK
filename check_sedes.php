<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Sedes columns ===\n";
$cols = \DB::select('SHOW COLUMNS FROM sedes');
foreach ($cols as $c) { echo "  {$c->Field} ({$c->Type})\n"; }

echo "\n=== Sedes data ===\n";
$sedes = \DB::table('sedes')->get();
foreach ($sedes as $s) {
    echo "  ID:{$s->id} | {$s->nombre}\n";
}
