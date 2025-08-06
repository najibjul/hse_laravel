FROM dunglas/frankenphp:1.9-builder-php8.3.24-bookworm

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y 

RUN install-php-extensions \
    pdo_mysql 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chown -R www-data:www-data storage bootstrap/cache