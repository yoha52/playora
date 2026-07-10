#!/bin/sh
set -e

if [ ! -f /app/.env ]; then
  touch /app/.env
fi

php artisan key:generate --ansi --force || true
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
