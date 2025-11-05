#!/bin/bash

# Script to fix 403 Forbidden error for storage files

set -e

echo "🔧 Fixing 403 Forbidden for Storage Files..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/3] Pulling latest changes..."
sudo git pull origin main
echo "✅ Changes pulled"
echo ""

echo "[2/3] Rebuilding app container to apply Caddyfile changes..."
docker-compose -f docker-compose.prod.yml --env-file .env.production build app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
echo "✅ App container rebuilt"
echo ""

echo "[3/3] Waiting for app to be healthy..."
sleep 10

# Check app health
if docker ps --format "{{.Names}} {{.Status}}" | grep -q "adojobs_app.*healthy"; then
    echo "✅ App container is healthy"
else
    echo "⚠️  App container might still be starting..."
    docker ps --format "{{.Names}} {{.Status}}" | grep adojobs_app
fi
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Test storage access:"
echo "   1. Check if files exist: docker exec adojobs_app ls -la /app/storage/app/public/settings/"
echo "   2. Test URL: curl -I https://adojobs.id/storage/settings/filename.png"
echo "   3. Should return 200 OK, not 403 Forbidden"

