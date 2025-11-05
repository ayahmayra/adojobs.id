#!/bin/bash

# Script to fix asset URLs in production
# Ensures APP_URL is correctly set and caches are cleared

set -e

echo "🔧 Fixing Asset URLs in Production..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    exit 1
fi

echo "[1/4] Checking APP_URL in .env.production..."
if grep -q "APP_URL=https://adojobs.id" .env.production; then
    echo "✅ APP_URL is correctly set to https://adojobs.id"
else
    echo "⚠️  APP_URL might not be set correctly"
    echo "   Current APP_URL:"
    grep "APP_URL" .env.production || echo "   APP_URL not found in .env.production"
    echo ""
    echo "   Please ensure APP_URL=https://adojobs.id in .env.production"
fi
echo ""

echo "[2/4] Checking APP_URL in running container..."
CONTAINER_APP_URL=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.url');" 2>&1 | tail -1)
echo "   Current APP_URL in container: $CONTAINER_APP_URL"
if [[ "$CONTAINER_APP_URL" == *"https://adojobs.id"* ]]; then
    echo "✅ APP_URL is correct in container"
else
    echo "⚠️  APP_URL needs to be updated"
fi
echo ""

echo "[3/4] Clearing all caches..."
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
echo "✅ Caches cleared"
echo ""

echo "[4/4] Rebuilding caches with correct APP_URL..."
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache
echo "✅ Caches rebuilt"
echo ""

echo "[5/5] Verifying APP_URL after cache rebuild..."
NEW_APP_URL=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.url');" 2>&1 | tail -1)
echo "   APP_URL after rebuild: $NEW_APP_URL"
if [[ "$NEW_APP_URL" == *"https://adojobs.id"* ]]; then
    echo "✅ APP_URL is now correct!"
else
    echo "⚠️  APP_URL still needs to be fixed"
    echo "   Please check .env.production and ensure APP_URL=https://adojobs.id"
    echo "   Then restart the app container:"
    echo "   docker-compose -f docker-compose.prod.yml --env-file .env.production restart app"
fi
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Next steps:"
echo "   1. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)"
echo "   2. Check browser console for any remaining errors"
echo "   3. Verify assets are loading: https://adojobs.id/build/assets/"

