<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('ci', '13260003')->first();
$token = $user->createToken('sso-token')->plainTextToken;
echo "Token format: $token\n";
echo "Has pipe: " . (str_contains($token, '|') ? 'YES' : 'NO') . "\n";
