<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Check password column info
$columns = Schema::getColumns('users');

foreach ($columns as $column) {
    if ($column['name'] === 'password') {
        echo "Password column info:\n";
        echo "  Name: " . $column['name'] . "\n";
        echo "  Type: " . $column['type'] . "\n";
        echo "  Length: " . ($column['length'] ?? 'N/A') . "\n";
        echo "  Nullable: " . ($column['nullable'] ? 'Yes' : 'No') . "\n";
    }
}

// Get raw SQL
$userTable = DB::getSchemaBuilder()->getColumnListing('users');
echo "\nAll columns in users table:\n";
print_r($userTable);
