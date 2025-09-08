<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Run the migration to add icon column
    \Artisan::call('migrate', ['--path' => 'database/migrations/2024_01_15_000000_add_icon_to_forms_table.php']);
    echo "Migration completed successfully!\n";
    
    // Update existing forms with random icons
    $forms = \App\Forms::whereNull('icon')->orWhere('icon', '')->get();
    echo "Found " . $forms->count() . " forms without icons. Updating...\n";
    
    foreach ($forms as $form) {
        $form->icon = getRandomFormIcon();
        $form->save();
        echo "Updated form '{$form->name}' with icon '{$form->icon}'\n";
    }
    
    echo "All forms updated with icons!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
