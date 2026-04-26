# Stage 1: Build frontend assets
FROM node:20-alpine AS frontend
WORKDIR /app
COPY . .
RUN npm ci && npm run build

# Stage 2: PHP app
FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
        git zip unzip \
        libpq-dev libzip-dev libonig-dev libicu-dev \
    && docker-php-ext-install \
        pdo pdo_pgsql pdo_mysql \
        zip mbstring exif pcntl bcmath opcache intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
COPY --from=frontend /app/public/build ./public/build

# --no-scripts to avoid running php artisan during build (no .env available)
RUN composer dump-autoload --no-scripts --optimize

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80
CMD ["/usr/local/bin/start.sh"]
