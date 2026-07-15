#!/bin/sh
set -e

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan storage:link || true

php artisan migrate --force

php artisan optimize

apache2-foreground