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
    
    // Test various edge cases
    $test_passwords = ['', ' ', 'null', null];
    
    foreach ($test_passwords as $pwd) {
        $result = Hash::check($pwd === null ? '' : (string)$pwd, $password_hash);
        echo "Verify '" . ($pwd === null ? 'null' : $pwd) . "': " . ($result ? 'Yes' : 'No') . "\n";
    }
    
    // Also create a fresh hash and compare
    echo "\nCreating fresh hash of 'testpass123':\n";
    $fresh_hash = Hash::make('testpass123');
    echo "Fresh hash: " . $fresh_hash . "\n";
    
    $check_fresh = Hash::check('testpass123', $fresh_hash);
    echo "Fresh hash verifies testpass123: " . ($check_fresh ? 'Yes' : 'No') . "\n";
    
    // Check if both hashes are same
    echo "Stored == Fresh: " . ($password_hash === $fresh_hash ? 'Yes' : 'No') . "\n";
}
