#!/bin/bash

# Script to fix storage upload issues in production

set -e

echo "🔧 Fixing Storage Upload Issues..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    exit 1
fi

echo "[1/5] Creating storage link..."
docker exec adojobs_app php artisan storage:link
echo "✅ Storage link created"
echo ""

echo "[2/5] Fixing storage permissions..."
docker exec adojobs_app chown -R www-data:www-data /app/storage /app/public/storage
docker exec adojobs_app chmod -R 775 /app/storage /app/public/storage
echo "✅ Permissions fixed"
echo ""

echo "[3/5] Verifying storage link..."
if docker exec adojobs_app ls -la /app/public/storage | grep -q "storage ->"; then
    echo "✅ Storage symbolic link exists"
else
    echo "⚠️  Storage link might not exist, checking..."
    docker exec adojobs_app ls -la /app/public/ | grep storage
fi
echo ""

echo "[4/5] Checking storage directories..."
docker exec adojobs_app ls -la /app/storage/app/public/ 2>/dev/null | head -10 || echo "⚠️  storage/app/public/ might not exist"
echo ""

echo "[5/5] Testing storage access..."
docker exec adojobs_app php artisan tinker --execute="echo 'Storage path: ' . storage_path('app/public') . PHP_EOL; echo 'Public path: ' . public_path('storage') . PHP_EOL; echo 'Link exists: ' . (is_link(public_path('storage')) ? 'YES' : 'NO') . PHP_EOL;"
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Test upload:"
echo "   1. Try uploading an image from admin dashboard"
echo "   2. Check if file appears in: /app/storage/app/public/"
echo "   3. Check if accessible via: https://adojobs.id/storage/..."

