#!/bin/bash

echo "==> PHP: $(php -v | head -1)"
echo "==> DB_HOST: ${DB_HOST:-NOT SET}"
echo "==> DB_DATABASE: ${DB_DATABASE:-NOT SET}"
echo "==> APP_KEY set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"

echo "==> Running migrations..."
php artisan migrate --force 2>&1 && echo "Migrations OK" || echo "Migration failed (see above)"

echo "==> Caching config..."
php artisan config:cache 2>&1 || echo "Config cache failed"

echo "==> Caching routes..."
php artisan route:cache 2>&1 || echo "Route cache failed"

echo "==> Caching views..."
php artisan view:cache 2>&1 || echo "View cache failed"

php artisan storage:link 2>/dev/null || true

echo "==> Starting server on port ${PORT:-8080}"
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
