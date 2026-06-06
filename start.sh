#!/bin/bash

echo "==> Running migrations..."
php artisan migrate --force || echo "Migration warning (continuing)"

echo "==> Caching..."
php artisan config:cache || true
php artisan route:cache  || true
php artisan view:cache   || true
php artisan storage:link 2>/dev/null || true

echo "==> Starting server on port ${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
