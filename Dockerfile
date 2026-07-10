FROM php:8.2-cli

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        zip \
        unzip \
        ca-certificates \
        gnupg \
        libpq-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        zlib1g-dev \
        libzip-dev \
        libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_pgsql bcmath gd exif zip mbstring

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --prefer-dist --no-interaction --no-dev
RUN npm ci
RUN npm run build
RUN mkdir -p storage/app/public storage/app/private storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && if [ ! -f .env ]; then cp .env.example .env; fi
RUN php artisan key:generate --ansi --force || true
RUN php artisan storage:link || true

EXPOSE 8000

CMD ["sh", "-c", "php artisan config:clear || true; php artisan route:clear || true; php artisan view:clear || true; php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
