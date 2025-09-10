# -----------------------------
# Étape 1 : Base PHP avec Apache
# -----------------------------
FROM php:8.2-apache

# Installer extensions PHP et utilitaires
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip unzip git curl \
    nodejs npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier le projet
COPY . .

# -----------------------------
# Étape 2 : Installer Composer
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# -----------------------------
# Étape 3 : Installer Node (si nécessaire pour Vite/Tailwind)
# -----------------------------
RUN if [ -f package.json ]; then \
        npm install && npm run build; \
    else \
        echo "Pas de build Node nécessaire"; \
    fi

# -----------------------------
# Étape 4 : Configurer Apache pour Laravel
# -----------------------------
# DocumentRoot vers public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Permissions correctes pour storage et cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# -----------------------------
# Étape 5 : Exposer le port
# -----------------------------
EXPOSE 80

# Commande pour lancer Apache en avant-plan
CMD ["apache2-foreground"]
