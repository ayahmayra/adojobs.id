#!/bin/bash

# Script to force fix URL generation by rebuilding container and ensuring APP_URL is used

set -e

echo "🔧 Force Fixing URL Generation - Full Rebuild..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if .env.production exists
if [ ! -f .env.production ]; then
    echo "❌ .env.production not found!"
    exit 1
fi

echo "[1/8] Checking APP_URL in .env.production..."
APP_URL_ENV=$(grep "^APP_URL=" .env.production | cut -d '=' -f2 | tr -d '"' || echo "")
if [ -z "$APP_URL_ENV" ]; then
    echo "❌ APP_URL not found in .env.production!"
    echo "   Adding APP_URL=https://adojobs.id..."
    echo "APP_URL=https://adojobs.id" >> .env.production
    echo "✅ APP_URL added"
elif [ "$APP_URL_ENV" != "https://adojobs.id" ]; then
    echo "⚠️  APP_URL is set to: $APP_URL_ENV"
    echo "   Updating to https://adojobs.id..."
    sed -i 's|^APP_URL=.*|APP_URL=https://adojobs.id|' .env.production
    echo "✅ APP_URL updated"
else
    echo "✅ APP_URL is correctly set: $APP_URL_ENV"
fi
echo ""

echo "[2/8] Verifying APP_URL in file..."
grep "^APP_URL=" .env.production
echo ""

echo "[3/8] Stopping containers..."
docker-compose -f docker-compose.prod.yml --env-file .env.production down
echo "✅ Containers stopped"
echo ""

echo "[4/8] Removing old config cache (if exists)..."
docker-compose -f docker-compose.prod.yml --env-file .env.production run --rm app rm -f /app/bootstrap/cache/config.php || true
docker-compose -f docker-compose.prod.yml --env-file .env.production run --rm app rm -f /app/bootstrap/cache/routes-v7.php || true
echo "✅ Old cache files removed"
echo ""

echo "[5/8] Starting containers with fresh environment..."
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d
echo "✅ Containers started"
echo ""

echo "[6/8] Waiting for containers to be ready..."
sleep 15
echo "✅ Containers ready"
echo ""

echo "[7/8] Verifying APP_URL in running container..."
CONTAINER_APP_URL=$(docker exec adojobs_app env | grep "^APP_URL=" | cut -d '=' -f2 || echo "")
if [ -z "$CONTAINER_APP_URL" ]; then
    echo "⚠️  APP_URL not found in container environment!"
    echo "   Checking config instead..."
    CONTAINER_APP_URL=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.url');" 2>&1 | tail -1 | xargs)
fi
echo "   APP_URL in container: $CONTAINER_APP_URL"
echo ""

echo "[8/8] Clearing and rebuilding ALL caches..."
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
docker exec adojobs_app php artisan optimize:clear
echo "✅ All caches cleared"
echo ""

echo "[9/9] Rebuilding caches..."
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache
echo "✅ Caches rebuilt"
echo ""

echo "[10/10] Final verification..."
echo "   Testing config('app.url'):"
FINAL_CONFIG_URL=$(docker exec adojobs_app php artisan tinker --execute="echo config('app.url');" 2>&1 | tail -1 | xargs)
echo "   Result: $FINAL_CONFIG_URL"
echo ""

echo "   Testing route('home'):"
FINAL_ROUTE_URL=$(docker exec adojobs_app php artisan tinker --execute="echo route('home');" 2>&1 | tail -1 | xargs)
echo "   Result: $FINAL_ROUTE_URL"
echo ""

if [[ "$FINAL_ROUTE_URL" == *"https://adojobs.id"* ]]; then
    echo "✅✅✅ SUCCESS! Route URLs are now correct!"
    echo ""
    echo "   Test kategori route:"
    docker exec adojobs_app php artisan tinker --execute="echo route('categories.show', ['category' => 'transportasi-logistik']);" 2>&1 | tail -1
else
    echo "❌ Route URLs still incorrect!"
    echo ""
    echo "   Troubleshooting steps:"
    echo "   1. Check if .env.production is being loaded:"
    echo "      docker exec adojobs_app env | grep APP_URL"
    echo ""
    echo "   2. Check docker-compose.prod.yml env_file configuration"
    echo ""
    echo "   3. Manually set APP_URL in docker-compose.prod.yml environment section"
    echo ""
    echo "   4. Check Laravel URL generation:"
    echo "      docker exec adojobs_app php artisan tinker"
    echo "      >>> config('app.url')"
    echo "      >>> route('home')"
fi
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Next steps:"
echo "   1. Hard refresh browser (Ctrl+Shift+R or Cmd+Shift+R)"
echo "   2. Check browser console - URLs should be https://adojobs.id"
echo "   3. Test a few links to verify"

