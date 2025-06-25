<<<<<<< HEAD
=======
# Use PHP 7.4 FPM base image
>>>>>>> 46b1ade07fe04348bc94a8d9fe1f588a8818506d
FROM php:7.4-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libzip-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd mbstring pdo pdo_mysql zip

<<<<<<< HEAD
=======
# Install Composer
>>>>>>> 46b1ade07fe04348bc94a8d9fe1f588a8818506d
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

<<<<<<< HEAD
RUN composer install && \
    mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache && \
    chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache
=======
# Create Laravel cache directories and set permissions
RUN mkdir -p bootstrap/cache \
    && mkdir -p storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data bootstrap/cache storage \
    && chmod -R 775 bootstrap/cache storage

# Set Composer environment settings
ENV COMPOSER_MEMORY_LIMIT=-1
ENV COMPOSER_PROCESS_TIMEOUT=900

# Install PHP dependencies with optimized options
RUN composer install --prefer-dist --no-interaction --no-plugins --no-scripts || \
    composer install --prefer-dist --no-interaction --no-plugins --no-scripts

# Expose PHP-FPM port
EXPOSE 9000

# Start FPM server
CMD ["php-fpm"]
>>>>>>> 46b1ade07fe04348bc94a8d9fe1f588a8818506d
