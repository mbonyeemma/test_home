#!/bin/bash

# Wait for database to be ready
echo "Waiting for database to be ready..."
while ! mysqladmin ping -h"$DB_HOST" -P"$DB_PORT" -u"$DB_USERNAME" -p"$DB_PASSWORD" --silent; do
    echo "Database is not ready yet. Waiting..."
    sleep 2
done

echo "Database is ready!"

# Run Laravel migrations
echo "Running Laravel migrations..."
php artisan migrate --force

# Clear and cache config
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache

# Start nginx and PHP-FPM
echo "Starting nginx and PHP-FPM..."
service nginx start && php-fpm
