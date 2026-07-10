FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    libpq-dev \
    postgresql-client \
    git \
    curl \
    zip \
    unzip \
    nodejs \
    npm && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    bcmath \
    mbstring

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .

RUN composer install --prefer-dist --no-interaction --no-dev
RUN npm ci
RUN npm run build
RUN php artisan storage:link

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
