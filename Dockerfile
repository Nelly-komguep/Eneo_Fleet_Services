# ---------- base image ----------
FROM php:8.2-apache

# Install extensions and tools
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring bcmath gd zip exif \
    && a2enmod rewrite

# Composer (copy from official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set workdir
WORKDIR /var/www/html

# copy only composer files first for caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# copy app
COPY . .

# ensure storage permissions
RUN chown -R www-data:www-data storage bootstrap/cache || true
RUN chmod -R 775 storage bootstrap/cache || true

# Allow Apache to listen on $PORT if Render sets it (modify Apache config at container start)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Replace Listen 80 with environment PORT at runtime in entrypoint
# Entrypoint will run composer/migrations then exec apache in foreground
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Default command (apache foreground)
CMD ["apache2-foreground"]
