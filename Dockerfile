FROM php:8.2

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD php artisan migrate && php artisan l5-swagger:generate && php artisan serve --host=0.0.0.0 --port=8000