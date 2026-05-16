FROM php:8.3-fpm
RUN apt-get update && apt-get install -y libpng-dev libonig-dev libxml2-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
COPY . .
RUN composer install --no-dev --optimize-autoloader
RUN echo "max_execution_time=300" > /usr/local/etc/php/conf.d/custom.ini

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data