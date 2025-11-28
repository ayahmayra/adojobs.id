#!/bin/bash

# Script to fix database issues in production
# Run migrations and seeders

set -e

echo "🔧 Fixing Database Issues..."
echo ""

cd /var/www/adojobs.id || exit 1

# Load environment variables
if [ -f .env.production ]; then
    echo "✅ Loading .env.production..."
    set -a
    source .env.production
    set +a
else
    echo "❌ .env.production not found!"
    exit 1
fi

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    echo "   Start containers first: docker-compose -f docker-compose.prod.yml --env-file .env.production up -d"
    exit 1
fi

echo "[1/5] Checking database connection..."
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected';" > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✅ Database connection OK"
else
    echo "❌ Database connection failed!"
    exit 1
fi

echo ""
echo "[2/5] Running migrations..."
docker exec adojobs_app php artisan migrate --force
if [ $? -eq 0 ]; then
    echo "✅ Migrations completed"
else
    echo "❌ Migrations failed!"
    exit 1
fi

echo ""
echo "[3/5] Running seeders..."
echo "   This may take a few minutes..."
docker exec adojobs_app php artisan db:seed --force
if [ $? -eq 0 ]; then
    echo "✅ Seeders completed"
else
    echo "⚠️  Some seeders may have failed (this is OK if data already exists)"
fi

echo ""
echo "[4/5] Clearing caches..."
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
echo "✅ Caches cleared"

echo ""
echo "[5/5] Optimizing for production..."
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache
echo "✅ Production optimization completed"

echo ""
echo "✅ Database fix complete!"
echo ""
echo "🧪 Testing application..."
sleep 2
if docker exec adojobs_app curl -f http://localhost:8080/ > /dev/null 2>&1; then
    echo "✅ Application is now responding!"
else
    echo "⚠️  Application may still have issues. Check logs:"
    echo "   docker logs adojobs_app --tail 50"
fi

echo ""
echo "✅ All done!"

