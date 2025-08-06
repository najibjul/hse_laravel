FROM dunglas/frankenphp

WORKDIR /app

# Install dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip libpng-dev libzip-dev curl libonig-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Copy project files
COPY . .

# Set permission for Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Optimize Laravel (optional)
RUN php artisan config:cache && php artisan route:cache || true

# Run FrankenPHP with TLS
CMD ["frankenphp", "--document-root", "public", "--tls", "--cert", "/app/docker/ssl/cert.pem", "--key", "/app/docker/ssl/key.pem"]
