# Étape 1 : Image de base PHP avec Apache
FROM php:8.2-apache

# Installer extensions PHP nécessaires à Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite pour Laravel
RUN a2enmod rewrite

# Copier les fichiers du projet
COPY . /var/www/html

# Définir le répertoire de travail
WORKDIR /var/www/html

# Donner les permissions correctes
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exposer le port 80
EXPOSE 80

# Commande de démarrage
CMD ["apache2-foreground"]
