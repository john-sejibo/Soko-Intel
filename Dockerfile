FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev libicu-dev \
    nodejs npm \
    && docker-php-ext-install pdo_pgsql mbstring bcmath intl zip opcache \
    && a2enmod rewrite \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/000-default.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm install --ignore-scripts \
    && npm run build \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php artisan config:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force && apache2-foreground"]
