<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Checking users table structure...\n";
    $columns = \DB::select('DESCRIBE users');
    echo "Users table columns:\n";
    foreach($columns as $col) {
        echo "- {$col->Field} ({$col->Type})\n";
    }
    
    echo "\nChecking restrackself_reg table structure...\n";
    $columns = \DB::select('DESCRIBE restrackself_reg');
    echo "restrackself_reg table columns:\n";
    foreach($columns as $col) {
        echo "- {$col->Field} ({$col->Type})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 