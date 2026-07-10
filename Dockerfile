FROM composer:2.8 AS build

WORKDIR /app

COPY . .

RUN apk add --no-cache libexif-dev
RUN docker-php-ext-install exif
RUN composer install --prefer-dist --no-interaction --no-dev
RUN npm ci
RUN npm run build
RUN php artisan storage:link

FROM serversideup/php:8.2-fpm-nginx

WORKDIR /app

RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    jpeg-dev \
    freetype-dev \
    libzip-dev \
    exif-dev

RUN docker-php-ext-install pdo pdo_pgsql bcmath gd exif zip

COPY --from=build /app /app

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public

EXPOSE 80

CMD ["php-fpm"]
