<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "Running forms table migrations...\n";
    
    // Run the specific migrations
    \Artisan::call('migrate', [
        '--path' => 'database/migrations/2025_01_08_000000_add_color_and_icon_to_forms_table.php'
    ]);
    
    echo "Column migration completed!\n";
    
    \Artisan::call('migrate', [
        '--path' => 'database/migrations/2025_01_08_000001_populate_forms_with_default_colors_and_icons.php'
    ]);
    
    echo "Data population completed!\n";
    echo "All migrations completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
