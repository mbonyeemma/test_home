# Multi-stage build for smaller final image
FROM php:7.4-fpm-alpine AS base

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    freetype-dev \
    oniguruma-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mbstring pdo pdo_mysql zip \
    && apk del --no-cache libpng-dev libjpeg-dev freetype-dev oniguruma-dev libzip-dev

# Install Composer
COPY --from=composer:2.5-alpine /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy composer files first for better layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies with optimized options
RUN composer install --prefer-dist --no-interaction --no-plugins --no-scripts --no-dev --optimize-autoloader

# Copy project files
COPY . .

# Create Laravel cache directories and set permissions
RUN mkdir -p bootstrap/cache \
    && mkdir -p storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data bootstrap/cache storage \
    && chmod -R 775 bootstrap/cache storage

# Optimize PHP settings for production (realistic limits)
RUN echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/memory-limit.ini \
    && echo "max_execution_time = 60" >> /usr/local/etc/php/conf.d/execution-time.ini \
    && echo "upload_max_filesize = 128M" >> /usr/local/etc/php/conf.d/upload-limit.ini \
    && echo "post_max_size = 128M" >> /usr/local/etc/php/conf.d/post-limit.ini

# Expose PHP-FPM port
EXPOSE 9000

# Start FPM server
CMD ["php-fpm"]

