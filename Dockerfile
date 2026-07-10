FROM composer:2.8 AS build

WORKDIR /app

COPY . .

RUN composer install --prefer-dist --no-interaction --no-dev
RUN npm ci
RUN npm run build
RUN php artisan storage:link

FROM serversideup/php:8.2-fpm-nginx

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql bcmath gd

COPY --from=build /app /app

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public

EXPOSE 80

CMD ["php-fpm"]
