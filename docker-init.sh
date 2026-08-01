#!/bin/bash

set -e

echo "=== Preparando almacenamiento ==="
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache public
chmod -R 775 storage bootstrap/cache public
php artisan storage:link --quiet || true

echo "=== Iniciando base de datos ==="
php artisan db:seed-once

echo "=== Aplicación lista ==="
