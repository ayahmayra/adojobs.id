#!/bin/bash

# Script to fix ALL storage and public permissions/ownership

set -e

echo "🔧 Fixing ALL Storage and Public Permissions..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/7] Fixing /app/public ownership..."
docker exec adojobs_app chown -R www-data:www-data /app/public
echo "✅ /app/public ownership fixed"
echo ""

echo "[2/7] Fixing /app/storage ownership..."
docker exec adojobs_app chown -R www-data:www-data /app/storage
echo "✅ /app/storage ownership fixed"
echo ""

echo "[3/7] Fixing /app/public permissions..."
docker exec adojobs_app chmod -R 755 /app/public
echo "✅ /app/public permissions fixed"
echo ""

echo "[4/7] Fixing /app/storage permissions..."
docker exec adojobs_app chmod -R 775 /app/storage
echo "✅ /app/storage permissions fixed"
echo ""

echo "[5/7] Recreating storage link (ensure correct ownership)..."
docker exec adojobs_app rm -f /app/public/storage
docker exec adojobs_app php artisan storage:link
echo "✅ Storage link recreated"
echo ""

echo "[6/7] Verifying ownership..."
echo "=== /app/public/storage symlink ==="
docker exec adojobs_app ls -la /app/public/ | grep storage
echo ""
echo "=== /app/storage/app/public directory ==="
docker exec adojobs_app ls -ld /app/storage/app/public
echo ""
echo "=== Files in settings ==="
docker exec adojobs_app ls -lah /app/storage/app/public/settings/ | head -5
echo ""

echo "[7/7] Testing HTTP access..."
sleep 2
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png)
echo "HTTP Status Code: $HTTP_CODE"
if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ SUCCESS! File accessible via HTTP"
    echo ""
    echo "🎉 Storage permissions fixed!"
    echo ""
    echo "📝 Next: Purge Cloudflare cache:"
    echo "   https://dash.cloudflare.com/ > adojobs.id > Caching > Purge Everything"
elif [ "$HTTP_CODE" = "403" ]; then
    echo "❌ Still 403 - checking Caddyfile configuration..."
    docker exec adojobs_app cat /etc/caddy/Caddyfile | grep -A 3 "file_server"
elif [ "$HTTP_CODE" = "404" ]; then
    echo "❌ 404 Not Found - symlink might be broken"
    docker exec adojobs_app ls -la /app/public/storage
else
    echo "⚠️  Unexpected HTTP status: $HTTP_CODE"
fi
echo ""

