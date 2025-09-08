<?php

/**
 * Form Icons Management
 * Provides a comprehensive array of FontAwesome icons for different form types
 */

/**
 * Get all available form icons
 * @return array
 */
function getFormIcons()
{
    return [
        // Medical & Health Icons
        'stethoscope', 'heartbeat', 'user-md', 'hospital-o', 'medkit', 'ambulance',
        'heart', 'plus-square', 'user-plus', 'wheelchair', 'bed', 'thermometer-half',
        
        // Data & Analytics Icons
        'database', 'bar-chart', 'pie-chart', 'line-chart', 'area-chart', 'table',
        'calculator', 'percent', 'trending-up', 'trending-down', 'signal',
        
        // Document & Form Icons
        'file-text-o', 'clipboard', 'list-alt', 'check-square-o', 'file-o', 'file-pdf-o',
        'file-word-o', 'file-excel-o', 'edit', 'pencil', 'pencil-square-o',
        
        // Communication Icons
        'envelope', 'envelope-o', 'phone', 'mobile', 'comment', 'comments', 'bullhorn',
        'megaphone', 'volume-up', 'volume-down', 'microphone',
        
        // Security & Access Icons
        'lock', 'unlock', 'key', 'shield', 'user-secret', 'eye', 'eye-slash',
        'fingerprint', 'id-card', 'id-card-o', 'vcard', 'vcard-o',
        
        // Technology Icons
        'laptop', 'desktop', 'tablet', 'mobile', 'wifi', 'bluetooth', 'usb',
        'plug', 'power-off', 'cog', 'cogs', 'wrench', 'screwdriver',
        
        // Business & Finance Icons
        'briefcase', 'building', 'building-o', 'bank', 'money', 'dollar', 'credit-card',
        'shopping-cart', 'shopping-bag', 'truck', 'shipping-fast',
        
        // Education & Learning Icons
        'graduation-cap', 'book', 'bookmark', 'bookmark-o', 'lightbulb-o', 'mortar-board',
        'pencil-square', 'eraser', 'magic', 'star', 'star-o',
        
        // Time & Calendar Icons
        'clock-o', 'calendar', 'calendar-o', 'calendar-check-o', 'calendar-times-o',
        'hourglass-start', 'hourglass-half', 'hourglass-end', 'history',
        
        // Location & Navigation Icons
        'map-marker', 'map', 'globe', 'compass', 'road', 'car', 'plane', 'ship',
        'bicycle', 'motorcycle', 'walking', 'running',
        
        // General Purpose Icons
        'home', 'search', 'filter', 'sort', 'refresh', 'undo', 'redo', 'save',
        'download', 'upload', 'share', 'link', 'external-link', 'expand', 'compress',
        'plus', 'minus', 'times', 'check', 'question', 'exclamation', 'info',
        'warning', 'ban', 'trash', 'recycle', 'leaf', 'tree', 'sun-o', 'moon-o'
    ];
}

/**
 * Get a random icon from the available icons
 * @return string
 */
function getRandomFormIcon()
{
    $icons = getFormIcons();
    return $icons[array_rand($icons)];
}

/**
 * Get icons by category
 * @param string $category
 * @return array
 */
function getIconsByCategory($category = 'all')
{
    $allIcons = getFormIcons();
    
    $categories = [
        'medical' => ['stethoscope', 'heartbeat', 'user-md', 'hospital-o', 'medkit', 'ambulance', 'heart', 'plus-square', 'user-plus', 'wheelchair', 'bed', 'thermometer-half'],
        'data' => ['database', 'bar-chart', 'pie-chart', 'line-chart', 'area-chart', 'table', 'calculator', 'percent', 'trending-up', 'trending-down', 'signal'],
        'document' => ['file-text-o', 'clipboard', 'list-alt', 'check-square-o', 'file-o', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'edit', 'pencil', 'pencil-square-o'],
        'communication' => ['envelope', 'envelope-o', 'phone', 'mobile', 'comment', 'comments', 'bullhorn', 'megaphone', 'volume-up', 'volume-down', 'microphone'],
        'security' => ['lock', 'unlock', 'key', 'shield', 'user-secret', 'eye', 'eye-slash', 'fingerprint', 'id-card', 'id-card-o', 'vcard', 'vcard-o'],
        'technology' => ['laptop', 'desktop', 'tablet', 'mobile', 'wifi', 'bluetooth', 'usb', 'plug', 'power-off', 'cog', 'cogs', 'wrench', 'screwdriver'],
        'business' => ['briefcase', 'building', 'building-o', 'bank', 'money', 'dollar', 'credit-card', 'shopping-cart', 'shopping-bag', 'truck', 'shipping-fast'],
        'education' => ['graduation-cap', 'book', 'bookmark', 'bookmark-o', 'lightbulb-o', 'mortar-board', 'pencil-square', 'eraser', 'magic', 'star', 'star-o'],
        'time' => ['clock-o', 'calendar', 'calendar-o', 'calendar-check-o', 'calendar-times-o', 'hourglass-start', 'hourglass-half', 'hourglass-end', 'history'],
        'location' => ['map-marker', 'map', 'globe', 'compass', 'road', 'car', 'plane', 'ship', 'bicycle', 'motorcycle', 'walking', 'running'],
        'general' => ['home', 'search', 'filter', 'sort', 'refresh', 'undo', 'redo', 'save', 'download', 'upload', 'share', 'link', 'external-link', 'expand', 'compress', 'plus', 'minus', 'times', 'check', 'question', 'exclamation', 'info', 'warning', 'ban', 'trash', 'recycle', 'leaf', 'tree', 'sun-o', 'moon-o']
    ];
    
    if ($category === 'all') {
        return $allIcons;
    }
    
    return isset($categories[$category]) ? $categories[$category] : $allIcons;
}

/**
 * Get a random icon from a specific category
 * @param string $category
 * @return string
 */
function getRandomIconByCategory($category = 'all')
{
    $icons = getIconsByCategory($category);
    return $icons[array_rand($icons)];
}

/**
 * Validate if an icon exists in our available icons
 * @param string $icon
 * @return bool
 */
function isValidFormIcon($icon)
{
    return in_array($icon, getFormIcons());
}

/**
 * Get icon with fallback
 * @param string $icon
 * @param string $fallback
 * @return string
 */
function getFormIconWithFallback($icon, $fallback = 'file')
{
    return isValidFormIcon($icon) ? $icon : $fallback;
}
