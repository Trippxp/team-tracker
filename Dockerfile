FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    curl zip unzip git sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p /app/database \
    && touch /app/database/database.sqlite \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache \
    && php artisan config:clear

EXPOSE 8080

CMD ["sh", "-c", "php artisan config:clear && php artisan migrate:fresh --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8080"]