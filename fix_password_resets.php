<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Checking password_resets table structure...\n";
    
    if (!Schema::hasColumn('password_resets', 'phone_number')) {
        echo "Adding phone_number column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN phone_number VARCHAR(20) AFTER id');
        DB::statement('ALTER TABLE password_resets ADD INDEX idx_phone_number (phone_number)');
    } else {
        echo "phone_number column already exists.\n";
    }
    
    if (!Schema::hasColumn('password_resets', 'otp')) {
        echo "Adding otp column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN otp VARCHAR(6) AFTER phone_number');
    } else {
        echo "otp column already exists.\n";
    }
    
    if (!Schema::hasColumn('password_resets', 'reset_token')) {
        echo "Adding reset_token column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN reset_token VARCHAR(100) AFTER otp');
        DB::statement('ALTER TABLE password_resets ADD INDEX idx_reset_token (reset_token)');
    } else {
        echo "reset_token column already exists.\n";
    }
    
    if (!Schema::hasColumn('password_resets', 'is_verified')) {
        echo "Adding is_verified column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN is_verified TINYINT(1) DEFAULT 0 AFTER reset_token');
    } else {
        echo "is_verified column already exists.\n";
    }
    
    if (!Schema::hasColumn('password_resets', 'expires_at')) {
        echo "Adding expires_at column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN expires_at TIMESTAMP NULL AFTER is_verified');
    } else {
        echo "expires_at column already exists.\n";
    }
    
    if (!Schema::hasColumn('password_resets', 'created_at')) {
        echo "Adding created_at column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN created_at TIMESTAMP NULL');
    } else {
        echo "created_at column already exists.\n";
    }
    
    if (!Schema::hasColumn('password_resets', 'updated_at')) {
        echo "Adding updated_at column...\n";
        DB::statement('ALTER TABLE password_resets ADD COLUMN updated_at TIMESTAMP NULL');
    } else {
        echo "updated_at column already exists.\n";
    }
    
    echo "\nPassword resets table structure updated successfully!\n";
    
    echo "\nCurrent table structure:\n";
    $columns = DB::select('DESCRIBE password_resets');
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

