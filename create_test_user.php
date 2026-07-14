<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::updateOrCreate(
    ['email' => 'direct_test@example.com'],
    [
        'name' => 'Direct Test',
        'email' => 'direct_test@example.com',
        'password' => Hash::make('testpass123'),
        'role' => 'user',
        'no_telepon' => '08123456789',
        'status' => 'active'
    ]
);

echo "User created/updated: " . $user->email . "\n";
echo "Password hash: " . $user->password . "\n";

// Test password verify
$plainPassword = 'testpass123';
$verify = password_verify($plainPassword, $user->password);
echo "Password 'testpass123' matches: " . ($verify ? 'Yes' : 'No') . "\n";
