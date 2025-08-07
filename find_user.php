<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Finding user with username: +256701234569\n";
    $user = \DB::table('restrackself_reg')->where('username', '+256701234569')->first();
    
    if ($user) {
        echo "Found user:\n";
        echo "ID: {$user->id}\n";
        echo "Username: {$user->username}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
        echo "Is Active: {$user->isactive}\n";
    } else {
        echo "User not found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 