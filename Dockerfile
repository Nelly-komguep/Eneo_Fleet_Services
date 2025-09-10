# Image de base PHP avec Apache
FROM php:8.2-apache

# Installer dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le code Laravel
COPY . .

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Définir public comme racine Apache
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 755 /var/www/html/public

# Exposer le port HTTP
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
