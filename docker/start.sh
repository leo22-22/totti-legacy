#!/bin/bash

php artisan package:discover --ansi 2>&1 || true
php artisan storage:link --force 2>&1 || true
php artisan migrate --force 2>&1 || echo "[WARN] migrate failed"
php artisan config:cache 2>&1 || echo "[WARN] config:cache failed"

exec apache2-foreground
