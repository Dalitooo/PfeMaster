#!/bin/bash

echo "==> PORT=${PORT:-8080}"
echo "==> APP_KEY set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"
echo "==> DB_HOST=${DB_HOST:-NOT SET}"

echo "==> Caching config..."
php artisan config:cache 2>&1 || echo "Config cache skipped"

echo "==> Caching routes..."
php artisan route:cache 2>&1 || echo "Route cache skipped"

echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration failed (check DB vars)"

php artisan storage:link 2>/dev/null || true

echo "==> Starting server on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
