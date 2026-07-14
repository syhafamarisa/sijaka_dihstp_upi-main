<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::where('email', 'direct_test@example.com')->first();

if ($user) {
    echo "Email: " . $user->email . "\n";
    echo "Password hash: " . $user->password . "\n";
    
    // Test with Laravel Hash::check()
    $plainPassword = 'testpass123';
    $verify = Hash::check($plainPassword, $user->password);
    echo "Password 'testpass123' matches (using Hash::check()): " . ($verify ? 'Yes' : 'No') . "\n";
    
    // Also test with password_verify
    $verify2 = password_verify($plainPassword, $user->password);
    echo "Password 'testpass123' matches (using password_verify()): " . ($verify2 ? 'Yes' : 'No') . "\n";
} else {
    echo "User not found\n";
}
