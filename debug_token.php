<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Create a fresh token
$user = \App\Models\User::where('ci', '13260003')->first();
$token = $user->createToken('test-token')->plainTextToken;
echo "Fresh token: $token\n";

// Verify it exists in DB
$parts = explode('|', $token);
$tokenId = $parts[0];
$dbToken = \DB::table('personal_access_tokens')->where('id', $tokenId)->first();
echo "Token in DB: " . ($dbToken ? 'YES' : 'NO') . "\n";
echo "Tokenable type: " . ($dbToken->tokenable_type ?? 'N/A') . "\n";
echo "Tokenable id: " . ($dbToken->tokenable_id ?? 'N/A') . "\n";
