<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== APPLICATIONS ===\n";
$apps = \App\Models\Application::all();
foreach ($apps as $a) {
    echo "ID: {$a->id} | {$a->nombre} | URL: {$a->url}\n";
}

echo "\n=== USERS WITH APPS ===\n";
$users = \App\Models\User::with('applications')->take(5)->get();
foreach ($users as $u) {
    $appNames = $u->applications->pluck('nombre')->join(', ');
    echo "CI: {$u->ci} | {$u->nombres} {$u->apellidos} | Apps: [{$appNames}]\n";
}
