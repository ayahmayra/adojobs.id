#!/bin/bash

# Script to check all storage and public permissions

set -e

echo "🔍 Checking Storage and Public Permissions..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "=== [1/6] Check /app/public ownership ==="
docker exec adojobs_app ls -ld /app/public
echo ""

echo "=== [2/6] Check /app/public/storage symlink ownership ==="
docker exec adojobs_app ls -la /app/public/ | grep storage
echo ""

echo "=== [3/6] Check /app/storage/app/public ownership ==="
docker exec adojobs_app ls -ld /app/storage/app/public
echo ""

echo "=== [4/6] Check files in /app/storage/app/public/settings ==="
docker exec adojobs_app ls -lah /app/storage/app/public/settings/
echo ""

echo "=== [5/6] Test direct access to file (inside container) ==="
docker exec adojobs_app cat /app/public/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✅ File readable inside container"
else
    echo "❌ File NOT readable inside container (permission issue)"
fi
echo ""

echo "=== [6/6] Test HTTP access via direct IP (bypass Cloudflare) ==="
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png)
echo "HTTP Status Code: $HTTP_CODE"
if [ "$HTTP_CODE" = "200" ]; then
    echo "✅ File accessible via HTTP (application OK, Cloudflare cache issue)"
elif [ "$HTTP_CODE" = "403" ]; then
    echo "❌ File forbidden via HTTP (permission or Caddyfile issue)"
elif [ "$HTTP_CODE" = "404" ]; then
    echo "❌ File not found via HTTP (symlink or path issue)"
else
    echo "⚠️  Unexpected HTTP status: $HTTP_CODE"
fi
echo ""

echo "📊 Analysis:"
echo ""
echo "Expected ownership:"
echo "  - /app/public: www-data:www-data"
echo "  - /app/public/storage (symlink): www-data:www-data"
echo "  - /app/storage/app/public: www-data:www-data"
echo "  - Files: www-data:www-data"
echo ""
echo "If any owner is 'root', that's the problem!"

