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

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
