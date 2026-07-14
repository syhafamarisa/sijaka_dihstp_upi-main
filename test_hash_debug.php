<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;

$plainPassword = 'testpass123';

// Test Hash::make dan Hash::check
$hash1 = Hash::make($plainPassword);
echo "Hash created with Hash::make(): " . $hash1 . "\n";

$check1 = Hash::check($plainPassword, $hash1);
echo "Hash::check() immediately after Hash::make(): " . ($check1 ? 'Yes' : 'No') . "\n";

// Manual test
$hash2 = password_hash($plainPassword, PASSWORD_BCRYPT);
echo "\nHash created with password_hash(): " . $hash2 . "\n";

$check2 = password_verify($plainPassword, $hash2);
echo "password_verify() immediately after password_hash(): " . ($check2 ? 'Yes' : 'No') . "\n";

$check3 = Hash::check($plainPassword, $hash2);
echo "Hash::check() on password_hash() result: " . ($check3 ? 'Yes' : 'No') . "\n";
