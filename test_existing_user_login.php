<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Test login dengan user yang ada
$email = 'syhafamarisa@gmail.com';
$password = 'marisa123'; // Try this password

$credentials = ['email' => $email, 'password' => $password];

$result = Auth::attempt($credentials);

echo "Login attempt for $email with password '$password': " . ($result ? 'Success' : 'Failed') . "\n";

if (!$result) {
    // Try to find the user and check hash
    $user = User::where('email', $email)->first();
    if ($user) {
        echo "User found: " . $user->name . "\n";
        echo "Password hash: " . $user->password . "\n";
        
        // Try a few common passwords
        $common_passwords = ['password', 'password123', '123456', 'admin', 'user'];
        foreach ($common_passwords as $pwd) {
            $check = Auth::validate(['email' => $email, 'password' => $pwd]);
            echo "Try password '$pwd': " . ($check ? 'Match' : 'No match') . "\n";
        }
    }
}
