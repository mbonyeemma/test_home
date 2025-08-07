<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Checking registration password...\n";
    
    // Check the registration record
    $registration = \DB::table('restrackself_reg')->where('username', '+256701234569')->first();
    if ($registration) {
        echo "Registration password hash: {$registration->password}\n";
    }
    
    // Check the users table
    $user = \DB::table('users')->where('username', '+256701234569')->first();
    if ($user) {
        echo "Users table password hash: {$user->password}\n";
    }
    
    // Test if they match
    if ($registration && $user) {
        echo "Passwords match: " . ($registration->password === $user->password ? "YES" : "NO") . "\n";
    }
    
    // Test with the actual password we used
    $raw_password = "password123";
    echo "\nTesting with raw password: {$raw_password}\n";
    
    if ($user && password_verify($raw_password, $user->password)) {
        echo "Raw password verification: SUCCESS\n";
    } else {
        echo "Raw password verification: FAILED\n";
    }
    
    // Test with base64 decoded password
    $base64_password = "cGFzc3dvcmQxMjM=";
    $decoded_password = base64_decode($base64_password);
    echo "Testing with base64 decoded password: {$decoded_password}\n";
    
    if ($user && password_verify($decoded_password, $user->password)) {
        echo "Base64 decoded password verification: SUCCESS\n";
    } else {
        echo "Base64 decoded password verification: FAILED\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 