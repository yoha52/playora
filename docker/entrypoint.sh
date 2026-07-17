#!/bin/sh
set -e

if [ ! -f /app/.env ]; then
  touch /app/.env
fi

if [ -n "$APP_KEY" ]; then
  if grep -q '^APP_KEY=' /app/.env; then
    sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" /app/.env
  else
    echo "APP_KEY=$APP_KEY" >> /app/.env
  fi
fi

if ! grep -q '^APP_KEY=' /app/.env || [ -z "$(grep '^APP_KEY=' /app/.env | cut -d= -f2-)" ]; then
  php artisan key:generate --ansi --force || true
fi

# Export important env vars from .env into the process environment so PHP/getenv can see them
if [ -f /app/.env ]; then
  for var in APP_KEY DB_CONNECTION DB_HOST DB_PORT DB_DATABASE DB_USERNAME DB_PASSWORD; do
    val=$(grep -m1 "^${var}=" /app/.env | cut -d= -f2- | sed 's/^"\(.*\)"$/\1/' | sed "s/^'\(.*\)'$/\1/") || val=""
    if [ -n "$val" ]; then
      export $var="$val"
    fi
  done
fi

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Running migrations..."
php artisan migrate --force

php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
