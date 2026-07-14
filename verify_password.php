<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Get password hash from database
$user = DB::table('users')->where('email', 'direct_test@example.com')->first();

if ($user) {
    $password_hash = $user->password;
    echo "Stored hash: " . $password_hash . "\n";
    echo "Hash length: " . strlen($password_hash) . "\n";
    
    // Test with different passwords
    $test_passwords = ['testpass123', 'password123', 'test', 'direct_test'];
    
    foreach ($test_passwords as $pwd) {
        $result = Hash::check($pwd, $password_hash);
        echo "Verify '$pwd': " . ($result ? 'Yes' : 'No') . "\n";
    }
}
