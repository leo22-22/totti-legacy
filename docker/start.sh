#!/bin/bash
set -e

php artisan storage:link --force 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
