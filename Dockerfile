FROM composer:2.8-bookworm AS build

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y --no-install-recommends libexif-dev && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install exif
RUN composer install --prefer-dist --no-interaction --no-dev
RUN npm ci
RUN npm run build
RUN php artisan storage:link

FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    libzip-dev \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql bcmath gd exif zip mbstring

COPY --from=build /app /app

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
