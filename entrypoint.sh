#!/usr/bin/env bash
set -e

# (Option) attendre que la base soit joignable (simple boucle)
echo "Waiting for DB..."
RETRY=0
MAX_RETRIES=30
SLEEP=3
while ! php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" >/dev/null 2>&1; do
  ((RETRY++))
  if [ "$RETRY" -ge "$MAX_RETRIES" ]; then
    echo "DB not available after $MAX_RETRIES attempts"
    break
  fi
  echo "DB not ready, retry $RETRY/$MAX_RETRIES - sleeping $SLEEP"
  sleep $SLEEP
done

# Install composer deps (if vendor absent)
if [ ! -d vendor ]; then
  composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
fi

# permissions
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R 775 storage bootstrap/cache || true

# Set app key if not set (only for first run; better: set APP_KEY in Render env)
if [ -z "$APP_KEY" ]; then
  echo "APP_KEY not set in env — you should set APP_KEY in Render settings"
fi

# Caches & migrations (migrations forcées pour prod; tu peux les commenter si tu préfères faire à la main)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Migration (ignore failure)
php artisan migrate --force || echo "Migrate failed or no DB seeding needed"

# Finally start apache (or whatever server your Dockerfile uses)
exec "$@"
