<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = \App\Models\User::where('email', 'testuser123@example.com')->first();

if ($user) {
    echo "Email: " . $user->email . "\n";
    echo "Password Hash: " . $user->password . "\n";
    echo "Is BCrypt Hash: " . (str_starts_with($user->password, '$2') ? 'Yes' : 'No') . "\n";
    
    // Test password
    $plainPassword = 'password123';
    $verify = password_verify($plainPassword, $user->password);
    echo "Password 'password123' matches: " . ($verify ? 'Yes' : 'No') . "\n";
} else {
    echo "User not found\n";
}
