FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --optimize-autoloader --no-dev --no-interaction --no-scripts

COPY package*.json ./
RUN npm ci

COPY . .
RUN composer dump-autoload --optimize && php artisan package:discover --ansi
RUN npm run build

RUN mkdir -p storage/logs storage/framework/sessions \
        storage/framework/views storage/framework/cache/data bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN sed -i 's/\r$//' /app/start.sh && chmod +x /app/start.sh

EXPOSE 8080

ENTRYPOINT ["/app/start.sh"]
