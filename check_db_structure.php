<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Check users table structure
$result = DB::select("DESCRIBE users");

echo "Users table structure:\n";
foreach ($result as $col) {
    if ($col->Field === 'password') {
        echo "Password column: " . json_encode($col) . "\n";
    }
}

// Check password value
$user = DB::table('users')->where('email', 'direct_test@example.com')->first();
if ($user) {
    echo "\nPassword value for direct_test@example.com:\n";
    echo "  Length: " . strlen($user->password) . "\n";
    echo "  Value: " . $user->password . "\n";
}
