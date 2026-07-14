<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Get raw SQL to check column info
$result = DB::select("PRAGMA table_info(users)");

echo "Users table columns:\n";
foreach ($result as $col) {
    echo "  Name: " . $col->name . "\n";
    echo "  Type: " . $col->type . "\n";
    echo "  Nullable: " . ($col->notnull ? 'No' : 'Yes') . "\n";
    echo "\n";
}

// Check specific password value
$user = DB::table('users')->where('email', 'direct_test@example.com')->first();
if ($user) {
    echo "Password column actual value:\n";
    echo "  Length: " . strlen($user->password) . "\n";
    echo "  Value: " . $user->password . "\n";
    echo "  First 10 chars: " . substr($user->password, 0, 10) . "\n";
}
