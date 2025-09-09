# -------- Stage 1 : Composer dependencies --------
FROM composer:2.7 AS vendor

WORKDIR /app

# Copier composer.json + composer.lock + artisan pour éviter l’erreur
COPY composer.json composer.lock artisan ./

# Installer les dépendances PHP
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# -------- Stage 2 : Application --------
FROM php:8.2-fpm

# Installer extensions PHP nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copier dépendances PHP depuis le stage vendor
COPY --from=vendor /app/vendor ./vendor

# Copier tout le projet Laravel
COPY . .

# Donner les bons droits à Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer port
EXPOSE 8000

# Commande de démarrage (PHP built-in server pour Render)
CMD php artisan serve --host=0.0.0.0 --port=8000

