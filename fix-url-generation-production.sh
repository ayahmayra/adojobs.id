#!/bin/bash

# Script to fix URL generation issues in production
# Ensures all URLs use APP_URL instead of request-based URLs

set -e

echo "🔧 Fixing URL Generation in Production..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    exit 1
fi

echo "[1/6] Checking APP_URL in .env.production..."
APP_URL_ENV=$(grep "^APP_URL=" .env.production | cut -d '=' -f2 | tr -d '"' || echo "")
if [ "$APP_URL_ENV" = "https://adojobs.id" ]; then
    echo "✅ APP_URL is correctly set in .env.production: $APP_URL_ENV"
else
    echo "❌ APP_URL is NOT set correctly!"
    echo "   Current value: $APP_URL_ENV"
    echo "   Expected: https://adojobs.id"
    echo ""
    echo "   Please edit .env.production and set:"
    echo "   APP_URL=https://adojobs.id"
    echo ""
    read -p "Press Enter to continue after fixing APP_URL, or Ctrl+C to abort..."
fi
echo ""

echo "[2/6] Clearing ALL caches..."
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
docker exec adojobs_app php artisan optimize:clear
echo "✅ All caches cleared"
echo ""

echo "[3/6] Verifying APP_URL in container (before cache rebuild)..."
BEFORE_URL=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.url');" 2>&1 | tail -1 | xargs)
echo "   APP_URL: $BEFORE_URL"
echo ""

echo "[4/6] Rebuilding caches..."
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache
echo "✅ Caches rebuilt"
echo ""

echo "[5/6] Verifying APP_URL after cache rebuild..."
AFTER_URL=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.url');" 2>&1 | tail -1 | xargs)
echo "   APP_URL: $AFTER_URL"
if [[ "$AFTER_URL" == *"https://adojobs.id"* ]]; then
    echo "✅ APP_URL is correct!"
else
    echo "❌ APP_URL is still incorrect!"
    echo "   This might require container restart"
fi
echo ""

echo "[6/6] Testing route URL generation..."
echo "   Testing route('home'):"
ROUTE_HOME=$(docker exec adojobs_app php artisan tinker --execute="echo route('home');" 2>&1 | tail -1 | xargs)
echo "   Result: $ROUTE_HOME"
if [[ "$ROUTE_HOME" == *"https://adojobs.id"* ]]; then
    echo "✅ Route URLs are correct!"
else
    echo "❌ Route URLs are still incorrect!"
    echo "   Restarting container..."
    docker-compose -f docker-compose.prod.yml --env-file .env.production restart app
    sleep 5
    echo "   Rebuilding cache after restart..."
    docker exec adojobs_app php artisan config:cache
    docker exec adojobs_app php artisan route:cache
    echo "   Testing again..."
    ROUTE_HOME2=$(docker exec adojobs_app php artisan tinker --execute="echo route('home');" 2>&1 | tail -1 | xargs)
    echo "   Result: $ROUTE_HOME2"
    if [[ "$ROUTE_HOME2" == *"https://adojobs.id"* ]]; then
        echo "✅ Route URLs are now correct after restart!"
    else
        echo "❌ Route URLs still incorrect after restart"
        echo "   Please check:"
        echo "   1. .env.production has APP_URL=https://adojobs.id"
        echo "   2. Container is using .env.production file"
        echo "   3. X-Forwarded headers are being passed correctly"
    fi
fi
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Next steps:"
echo "   1. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)"
echo "   2. Check browser console - URLs should be https://adojobs.id"
echo "   3. Test a few links to verify they work correctly"

