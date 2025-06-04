FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    nginx supervisor nano zip unzip curl \
    libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel app files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copy configs
COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./ssl /etc/nginx/ssl

# Expose ports
EXPOSE 80 443

# Start all services
CMD ["/usr/bin/supervisord", "-n"]
