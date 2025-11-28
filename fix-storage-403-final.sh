#!/bin/bash

# Script to fix 403 Forbidden error for storage files - Final Fix

set -e

echo "🔧 Fixing 403 Forbidden for Storage Files - Final Fix..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/4] Pulling latest changes..."
sudo git pull origin main
echo "✅ Changes pulled"
echo ""

echo "[2/4] Rebuilding app container with corrected Caddyfile order..."
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app
echo "✅ App container rebuilt"
echo ""

echo "[3/4] Restarting app container..."
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
echo "✅ App container restarted"
echo ""

echo "[4/4] Waiting for app to be healthy..."
sleep 15

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
echo "   2. Test URL: curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png"
echo "   3. Should return 200 OK, not 403 Forbidden"
echo ""
echo "🔍 If still 403, check Caddyfile inside container:"
echo "   docker exec adojobs_app cat /etc/caddy/Caddyfile | grep -A 5 disallowed"

