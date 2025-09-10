FROM php:8.2-apache

# Installer les extensions PHP nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Copier tout ton projet Laravel
WORKDIR /var/www/html
COPY . .

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Donner les bonnes permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Exposer le port
EXPOSE 80

CMD ["apache2-foreground"]
