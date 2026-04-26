#!/bin/bash
set -e

# Create storage symlink (public/storage -> storage/app/public)
php artisan storage:link --force 2>/dev/null || true

# Cache config, routes and views for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
