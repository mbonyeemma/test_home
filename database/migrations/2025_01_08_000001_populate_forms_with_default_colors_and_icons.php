<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PopulateFormsWithDefaultColorsAndIcons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Define default colors
        $defaultColors = [
            '#3498db', // Blue
            '#e74c3c', // Red
            '#2ecc71', // Green
            '#f39c12', // Orange
            '#9b59b6', // Purple
            '#1abc9c', // Turquoise
            '#34495e', // Dark Blue
            '#e67e22', // Carrot
            '#95a5a6', // Silver
            '#f1c40f'  // Yellow
        ];
        
        // Define basic icons for different form types
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
        
        // Get all existing forms
        $forms = DB::table('forms')->get();
        
        foreach ($forms as $index => $form) {
            $updates = [];
            
            // Set default color if not set or is null
            if (empty($form->color) || is_null($form->color)) {
                $updates['color'] = $defaultColors[$index % count($defaultColors)];
            }
            
            // Set default icon if not set or is null
            if (empty($form->icon) || is_null($form->icon)) {
                $icon = 'file'; // default
                
                $name = strtolower($form->name);
                
                // Simple keyword matching for smart icon assignment
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
                
                $updates['icon'] = $icon;
            }
            
            // Update the form if there are changes
            if (!empty($updates)) {
                $updates['updated_at'] = now();
                DB::table('forms')->where('id', $form->id)->update($updates);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration only populates data, so we don't need to reverse it
        // The columns will be dropped by the previous migration
    }
}
