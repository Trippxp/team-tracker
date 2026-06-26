FROM php:8.2-apache

# 1. Install system dependencies and PHP extensions required for Laravel & SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite module for Laravel routing
RUN a2enmod rewrite

# 3. Configure Apache VirtualHost and enable .htaccess Overrides
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /app/public\n\
    <Directory /app/public>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# 4. Set the working directory inside the container
WORKDIR /app

# 5. Copy your application code into the container
COPY . /app

# 6. Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 7. Run composer install to pull dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Create database directory/file and set robust permissions for Apache (www-data)
RUN mkdir -p /app/database \
    && touch /app/database/database.sqlite \
    && chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage /app/bootstrap/cache /app/database

# 9. Expose port 80 for Apache
EXPOSE 80

# 10. Clear old configs, refresh migrations/seeders, and run Apache in the foreground
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate:fresh --force && php artisan db:seed --force && apache2-foreground"]