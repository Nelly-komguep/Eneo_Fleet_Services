# Base PHP avec extensions
FROM php:8.2-cli

# Installer dépendances système
RUN apt-get update && apt-get install -y zip unzip git libzip-dev \
    && docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier le projet
WORKDIR /var/www/html
COPY . .

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Donner les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer un port générique (Render remplacera $PORT)
EXPOSE 10000

# Lancer PHP sur le port fourni par Render
CMD ["sh", "-c", "php -S 0.0.0.0:$PORT -t public"]

# Lancer Laravel via PHP intégré
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]
