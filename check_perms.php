<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$perms = DB::table('permissions')->get();
foreach ($perms as $p) {
    echo "ID:{$p->id} | {$p->name} | system_id={$p->system_id}\n";
}

echo "\n=== Systems ===\n";
$systems = DB::table('systems')->get();
foreach ($systems as $s) {
    echo "ID:{$s->id} | {$s->name}\n";
}
