<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = \DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $arr = (array)$t;
    echo array_values($arr)[0] . "\n";
}
