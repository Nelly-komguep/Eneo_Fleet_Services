# Étape 1 : Image PHP avec Apache
FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip curl gnupg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Copier les fichiers de l'application
WORKDIR /var/www/html
COPY . .

# Étape 2 : Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installer les dépendances PHP (Laravel)
RUN composer install --no-dev --optimize-autoloader

# Étape 3 : Installer Node.js 20 et compiler les assets avec Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install \
    && npm run build

# Définir les permissions (important pour Laravel storage et cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port
EXPOSE 80

# Lancer Apache
CMD ["apache2-foreground"]
