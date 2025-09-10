# Étape 1 : Image de base PHP avec Apache
FROM php:8.2-apache

# Installer dépendances système et extensions PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Installer Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers Laravel
COPY . .

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Compiler les assets Node/Vite si package.json existe
RUN if [ -f package.json ]; then \
        npm install && \
        npm run build; \
    else \
        echo "Pas de build Node nécessaire"; \
    fi

# Définir public comme racine Apache
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public \
    && chmod -R 755 /var/www/html/public

# Exposer le port
EXPOSE 80

# Commande pour démarrer Apache en premier plan
CMD ["apache2-foreground"]
