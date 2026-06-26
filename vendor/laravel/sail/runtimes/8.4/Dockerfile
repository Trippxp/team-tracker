FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    curl zip unzip git \
    && docker-php-ext-install pdo pdo_sqlite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p /var/data && \
    php artisan config:cache && \
    php artisan route:cache

EXPOSE 8080

CMD php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan serve --host=0.0.0.0 --port=8080