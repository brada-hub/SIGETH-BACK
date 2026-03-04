<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('ci', '13260003')->first();
echo "User: {$user->nombres} {$user->apellidos}\n";
echo "Password hash: {$user->password}\n";
echo "Test '12345678': " . (\Hash::check('12345678', $user->password) ? 'MATCH' : 'NO MATCH') . "\n";
echo "Test 'password': " . (\Hash::check('password', $user->password) ? 'MATCH' : 'NO MATCH') . "\n";

// Reset password
$user->password = \Hash::make('12345678');
$user->save();
echo "\nPassword reset to 12345678\n";
echo "Verify: " . (\Hash::check('12345678', $user->fresh()->password) ? 'MATCH' : 'NO MATCH') . "\n";
