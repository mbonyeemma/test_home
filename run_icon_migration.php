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
    
    // Update existing forms with random icons if they don't have icons
    $forms = \App\Forms::where(function($query) {
        $query->whereNull('icon')
              ->orWhere('icon', '')
              ->orWhere('icon', 'file');
    })->get();
    
    echo "Found " . $forms->count() . " forms to update with icons...\n";
    
    foreach ($forms as $form) {
        // Use smart icon selection based on form name
        $icon = getSmartIconForForm($form->name);
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

/**
 * Smart icon selection based on form name
 */
function getSmartIconForForm($formName) {
    $name = strtolower($formName);
    
    // Medical/Health related
    if (strpos($name, 'health') !== false || strpos($name, 'medical') !== false || 
        strpos($name, 'patient') !== false || strpos($name, 'clinic') !== false) {
        return getRandomIconByCategory('medical');
    }
    
    // Data/Analytics related
    if (strpos($name, 'data') !== false || strpos($name, 'report') !== false || 
        strpos($name, 'analytics') !== false || strpos($name, 'statistics') !== false) {
        return getRandomIconByCategory('data');
    }
    
    // Document related
    if (strpos($name, 'form') !== false || strpos($name, 'document') !== false || 
        strpos($name, 'record') !== false || strpos($name, 'file') !== false) {
        return getRandomIconByCategory('document');
    }
    
    // Communication related
    if (strpos($name, 'message') !== false || strpos($name, 'notification') !== false || 
        strpos($name, 'alert') !== false || strpos($name, 'contact') !== false) {
        return getRandomIconByCategory('communication');
    }
    
    // Security related
    if (strpos($name, 'security') !== false || strpos($name, 'access') !== false || 
        strpos($name, 'permission') !== false || strpos($name, 'auth') !== false) {
        return getRandomIconByCategory('security');
    }
    
    // Technology related
    if (strpos($name, 'system') !== false || strpos($name, 'tech') !== false || 
        strpos($name, 'digital') !== false || strpos($name, 'app') !== false) {
        return getRandomIconByCategory('technology');
    }
    
    // Business related
    if (strpos($name, 'business') !== false || strpos($name, 'finance') !== false || 
        strpos($name, 'payment') !== false || strpos($name, 'transaction') !== false) {
        return getRandomIconByCategory('business');
    }
    
    // Education related
    if (strpos($name, 'education') !== false || strpos($name, 'training') !== false || 
        strpos($name, 'course') !== false || strpos($name, 'learning') !== false) {
        return getRandomIconByCategory('education');
    }
    
    // Time related
    if (strpos($name, 'schedule') !== false || strpos($name, 'time') !== false || 
        strpos($name, 'calendar') !== false || strpos($name, 'appointment') !== false) {
        return getRandomIconByCategory('time');
    }
    
    // Location related
    if (strpos($name, 'location') !== false || strpos($name, 'address') !== false || 
        strpos($name, 'map') !== false || strpos($name, 'place') !== false) {
        return getRandomIconByCategory('location');
    }
    
    // Default to general icons
    return getRandomIconByCategory('general');
}
