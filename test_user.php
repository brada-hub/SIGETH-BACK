<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::with(['sede', 'applications'])->where('ci', '13260003')->first();

if ($user) {
    echo "=== User Data (what SSO sends to apps) ===\n";
    $json = $user->toArray();
    echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "User not found!\n";
}
