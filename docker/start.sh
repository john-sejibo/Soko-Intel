#!/bin/sh
set -e

# Render inaipa app PORT (default 10000). Apache isikilize port hiyo.
PORT="${PORT:-10000}"
sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan config:cache
php artisan view:cache

# Salama kurudiwa kila deploy: seeder inajiruka kama admin tayari yupo.
php artisan migrate --force --seed

exec apache2-foreground
