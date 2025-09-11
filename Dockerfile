# ============================
# Étape 1 : PHP-FPM avec extensions
# ============================
FROM php:8.2-fpm

# Installer dépendances système et PHP
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git curl nginx supervisor \
    && docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code de l'application
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Copier la config Nginx
COPY ./nginx.conf /etc/nginx/sites-enabled/default

# Exposer le port 80
EXPOSE 80

# Commande pour lancer Nginx + PHP-FPM via supervisor
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
