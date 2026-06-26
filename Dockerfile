FROM php:8.2-apache

# 1. Install system dependencies and PHP extensions for Laravel & SQLite
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Enable Apache rewrite module for Laravel routing
RUN a2enmod rewrite

# 3. Change Apache's Document Root to point to Laravel's public folder natively
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Set working directory to the standard Apache web root
WORKDIR /var/www/html

# 5. Copy your application code into the web root
COPY . /var/www/html

# 6. Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 7. Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Set up SQLite database and correct folder permissions for Apache (www-data)
RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 9. Expose port 80
EXPOSE 80

# 10. Clear configs, run fresh migrations/seeders, and start Apache
CMD ["sh", "-c", "php artisan config:clear && php artisan migrate:fresh --force && php artisan db:seed --force && apache2-foreground"]