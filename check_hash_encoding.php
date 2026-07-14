<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

$user = DB::table('users')->where('email', 'direct_test@example.com')->first();
$stored_hash = $user->password;

echo "Stored hash: " . $stored_hash . "\n";
echo "Stored hash is valid BCrypt: " . (preg_match('/^\$2[aby]\$/', $stored_hash) ? 'Yes' : 'No') . "\n";

// Try to hash the stored hash and see if it matches
$double_hash = Hash::make($stored_hash);
echo "Double hash: " . $double_hash . "\n";

$check_double = Hash::check($stored_hash, $double_hash);
echo "Double hash verifies stored_hash: " . ($check_double ? 'Yes' : 'No') . "\n";

// Check if the stored hash is actually a plain text that someone tried to hash
// Get all users and print their password hashes
echo "\n\nAll users and their password hashes:\n";
$all_users = DB::table('users')->select('email', 'password')->get();
foreach ($all_users as $u) {
    echo $u->email . ": " . substr($u->password, 0, 20) . "...\n";
}
