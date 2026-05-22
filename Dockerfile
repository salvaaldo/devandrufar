FROM php:8.2-cli

RUN apt-get update && apt-get install -y git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev && docker-php-ext-install pdo pdo_mysql mbstring zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

COPY . .
RUN composer dump-autoload --no-dev --optimize

EXPOSE 8000
CMD php artisan serve --host=0.0.0.0 --port=$PORT