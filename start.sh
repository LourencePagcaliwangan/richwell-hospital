#!/usr/bin/env bash
set -e

mkdir -p storage/app/data storage/framework/sessions storage/framework/views storage/framework/cache bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground