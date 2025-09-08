<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Check if icon column exists
    $hasIconColumn = \Schema::hasColumn('forms', 'icon');
    
    if (!$hasIconColumn) {
        echo "Adding icon column to forms table...\n";
        
        // Add the icon column
        \Schema::table('forms', function ($table) {
            $table->string('icon', 50)->default('file')->after('color');
        });
        
        echo "Icon column added successfully!\n";
    } else {
        echo "Icon column already exists.\n";
    }
    
    // Update existing forms with default icons
    $forms = \App\Forms::where(function($query) {
        $query->whereNull('icon')
              ->orWhere('icon', '')
              ->orWhere('icon', 'file');
    })->get();
    
    echo "Found " . $forms->count() . " forms to update with default icons...\n";
    
    // Simple icon array for basic assignment
    $basicIcons = [
        'file', 'file-text-o', 'clipboard', 'list-alt', 'check-square-o',
        'database', 'bar-chart', 'pie-chart', 'line-chart', 'table',
        'stethoscope', 'heartbeat', 'user-md', 'hospital-o', 'medkit',
        'envelope', 'phone', 'comment', 'bullhorn', 'megaphone',
        'lock', 'shield', 'key', 'user-secret', 'eye',
        'laptop', 'wifi', 'cog', 'wrench', 'screwdriver',
        'briefcase', 'building', 'money', 'credit-card', 'truck',
        'graduation-cap', 'book', 'lightbulb-o', 'star', 'magic',
        'clock-o', 'calendar', 'hourglass', 'history', 'time',
        'map-marker', 'globe', 'car', 'plane', 'ship',
        'home', 'search', 'plus', 'check', 'info'
    ];
    
    foreach ($forms as $index => $form) {
        // Assign icons based on form name or use a simple rotation
        $icon = 'file'; // default
        
        $name = strtolower($form->name);
        
        // Simple keyword matching
        if (strpos($name, 'health') !== false || strpos($name, 'medical') !== false) {
            $icon = $basicIcons[array_rand(['stethoscope', 'heartbeat', 'user-md', 'hospital-o'])];
        } elseif (strpos($name, 'data') !== false || strpos($name, 'report') !== false) {
            $icon = $basicIcons[array_rand(['database', 'bar-chart', 'pie-chart', 'table'])];
        } elseif (strpos($name, 'form') !== false || strpos($name, 'document') !== false) {
            $icon = $basicIcons[array_rand(['file-text-o', 'clipboard', 'list-alt'])];
        } elseif (strpos($name, 'message') !== false || strpos($name, 'notification') !== false) {
            $icon = $basicIcons[array_rand(['envelope', 'phone', 'comment', 'bullhorn'])];
        } elseif (strpos($name, 'security') !== false || strpos($name, 'access') !== false) {
            $icon = $basicIcons[array_rand(['lock', 'shield', 'key', 'user-secret'])];
        } elseif (strpos($name, 'system') !== false || strpos($name, 'tech') !== false) {
            $icon = $basicIcons[array_rand(['laptop', 'wifi', 'cog', 'wrench'])];
        } elseif (strpos($name, 'business') !== false || strpos($name, 'finance') !== false) {
            $icon = $basicIcons[array_rand(['briefcase', 'building', 'money', 'credit-card'])];
        } elseif (strpos($name, 'education') !== false || strpos($name, 'training') !== false) {
            $icon = $basicIcons[array_rand(['graduation-cap', 'book', 'lightbulb-o', 'star'])];
        } elseif (strpos($name, 'time') !== false || strpos($name, 'schedule') !== false) {
            $icon = $basicIcons[array_rand(['clock-o', 'calendar', 'hourglass', 'history'])];
        } elseif (strpos($name, 'location') !== false || strpos($name, 'address') !== false) {
            $icon = $basicIcons[array_rand(['map-marker', 'globe', 'car', 'plane'])];
        } else {
            // Use a rotating selection for other forms
            $icon = $basicIcons[$index % count($basicIcons)];
        }
        
        $form->icon = $icon;
        $form->save();
        echo "Updated form '{$form->name}' with icon '{$icon}'\n";
    }
    
    echo "All forms updated with icons!\n";
    echo "Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
