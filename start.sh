#!/bin/bash

rm -f /app/public/hot

echo "==> DB_HOST=${DB_HOST:-NOT SET}"
echo "==> DB_PORT=${DB_PORT:-NOT SET}"
echo "==> DB_DATABASE=${DB_DATABASE:-NOT SET}"
echo "==> APP_KEY set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

echo "==> Caching config..."
php artisan config:cache 2>&1 || echo "Config cache failed"

echo "==> Caching routes..."
php artisan route:cache 2>&1 || echo "Route cache failed"

echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration failed"

php artisan storage:link 2>/dev/null || true

echo "==> Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
