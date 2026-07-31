#!/bin/bash

# Script para inicializar la base de datos en Docker
# Ejecuta seeders solo la primera vez, luego solo migraciones

set -e

echo "=== Iniciando base de datos ==="

# Ejecutar el comando personalizado
php artisan db:seed-once

echo "=== Base de datos lista ==="
