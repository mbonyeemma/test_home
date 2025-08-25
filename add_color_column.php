<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::statement('ALTER TABLE forms ADD COLUMN color VARCHAR(7) DEFAULT "#3498db" AFTER form_submission_url');
    echo "Color column added successfully to forms table!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
