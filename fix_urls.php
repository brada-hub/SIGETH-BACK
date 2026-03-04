<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Corregir URLs de las aplicaciones
\App\Models\Application::where('nombre', 'SIGVA')->update([
    'url' => 'http://localhost:9002/admin/login'
]);

\App\Models\Application::where('nombre', 'SISPO')->update([
    'url' => 'http://localhost:9001'
]);

echo "URLs actualizadas!\n";
foreach (\App\Models\Application::all() as $a) {
    echo "{$a->nombre} => {$a->url}\n";
}
