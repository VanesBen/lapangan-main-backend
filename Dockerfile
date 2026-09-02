FROM php:8.2-fpm-alpine

# Install ekstensi & dependencies sistem
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    ca-certificates

# Install ekstensi database (MySQL & PDO)
RUN docker-php-ext-install pdo pdo_mysql bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Salin source code
COPY . .

# Install dependency laravel tanpa dev
RUN composer install --no-dev --optimize-autoloader

# Atur permission storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Port web service
EXPOSE 8080

# Jalankan server bawaan
CMD php artisan config:cache && php artisan route:cache && php artisan serve --host=0.0.0.0 --port=8080