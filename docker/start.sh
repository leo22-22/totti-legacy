#!/bin/bash
set -e

php artisan package:discover --ansi 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
