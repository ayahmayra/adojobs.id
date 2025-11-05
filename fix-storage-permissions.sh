#!/bin/bash

# Script to fix storage file permissions and test access

set -e

echo "🔧 Fixing Storage File Permissions..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    exit 1
fi

echo "[1/4] Fixing file ownership in storage/app/public..."
docker exec adojobs_app find /app/storage/app/public -type f -not -user www-data -exec chown www-data:www-data {} \;
docker exec adojobs_app find /app/storage/app/public -type d -not -user www-data -exec chown www-data:www-data {} \;
echo "✅ File ownership fixed"
echo ""

echo "[2/4] Fixing file permissions..."
docker exec adojobs_app chmod -R 775 /app/storage/app/public
echo "✅ File permissions fixed"
echo ""

echo "[3/4] Verifying storage files..."
docker exec adojobs_app ls -lah /app/storage/app/public/settings/ | head -10
echo ""

echo "[4/4] Testing storage access..."
# Get first file in settings directory
FIRST_FILE=$(docker exec adojobs_app ls /app/storage/app/public/settings/ | head -1)
if [ -n "$FIRST_FILE" ]; then
    echo "Testing access to: $FIRST_FILE"
    echo "URL: https://adojobs.id/storage/settings/$FIRST_FILE"
    echo ""
    echo "Testing with curl..."
    curl -I "https://adojobs.id/storage/settings/$FIRST_FILE" | head -5
    echo ""
    echo "✅ Test complete!"
    echo ""
    echo "📝 If you see 200 OK, the fix is successful!"
    echo "📝 If you see 403 Forbidden, Caddyfile still needs fixing"
    echo "📝 If you see 404 Not Found, check the file path"
else
    echo "⚠️  No files found in settings directory"
fi

