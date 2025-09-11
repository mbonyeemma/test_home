#!/bin/bash

echo "Running Laravel migrations..."

# Run the standard migrations
php artisan migrate --force

# Run our custom ensure command
php artisan migrate:ensure

echo "Migrations completed!"
