#!/bin/bash

# Quick fix for 500 error after rebuild

set -e

echo "🔧 Quick Fix for 500 Error..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    echo "   Starting containers..."
    docker-compose -f docker-compose.prod.yml --env-file .env.production up -d
    sleep 10
fi

echo "[1/5] Checking Laravel logs for errors..."
echo "   Last 50 lines of error log:"
docker exec adojobs_app tail -50 /app/storage/logs/laravel.log 2>/dev/null | grep -i "error\|exception\|fatal" | tail -10 || echo "   No errors found in recent logs"
echo ""

echo "[2/5] Checking database connection..."
DB_CHECK=$(docker exec adojobs_app php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'OK'; } catch (Exception \$e) { echo 'FAIL: ' . \$e->getMessage(); }" 2>&1 | tail -1)
if [[ "$DB_CHECK" == *"OK"* ]]; then
    echo "✅ Database connection OK"
else
    echo "❌ Database connection failed: $DB_CHECK"
    echo "   This might be the cause of 500 error"
fi
echo ""

echo "[3/5] Checking APP_KEY..."
APP_KEY_CHECK=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.key') ? 'SET' : 'NOT SET';" 2>&1 | tail -1)
if [[ "$APP_KEY_CHECK" == *"SET"* ]]; then
    echo "✅ APP_KEY is set"
else
    echo "❌ APP_KEY is NOT SET - this will cause 500 error!"
    echo "   Generating APP_KEY..."
    docker exec adojobs_app php artisan key:generate --force
    echo "✅ APP_KEY generated"
fi
echo ""

echo "[4/5] Checking storage permissions..."
docker exec adojobs_app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker exec adojobs_app chmod -R 775 /app/storage /app/bootstrap/cache
echo "✅ Storage permissions fixed"
echo ""

echo "[5/5] Clearing and rebuilding cache..."
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
echo "✅ Cache cleared"
echo ""

echo "[6/6] Rebuilding cache..."
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache
echo "✅ Cache rebuilt"
echo ""

echo "✅ Quick fix complete!"
echo ""
echo "📝 Next steps:"
echo "   1. Test the application: curl http://10.10.10.33/"
echo "   2. Check logs if still error: docker logs adojobs_app --tail 50"
echo "   3. Check Laravel logs: docker exec adojobs_app tail -50 /app/storage/logs/laravel.log"

