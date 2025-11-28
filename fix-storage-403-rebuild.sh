#!/bin/bash

# Script to fix 403 Forbidden by rebuilding container with correct Caddyfile

set -e

echo "🔧 Fixing 403 Forbidden - Rebuilding Container..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/5] Pulling latest changes..."
sudo git pull origin main
echo "✅ Changes pulled"
echo ""

echo "[2/5] Stopping app container..."
docker-compose -f docker-compose.prod.yml --env-file .env.production stop app
echo "✅ App container stopped"
echo ""

echo "[3/5] Rebuilding app container with --no-cache (this may take a few minutes)..."
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app
echo "✅ App container rebuilt"
echo ""

echo "[4/5] Starting app container..."
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
echo "✅ App container started"
echo ""

echo "[5/5] Waiting for app to be healthy..."
sleep 20

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
echo "📝 Verification:"
echo "   1. Check Caddyfile in container:"
echo "      docker exec adojobs_app cat /etc/caddy/Caddyfile | grep -A 5 'file_server'"
echo ""
echo "   2. Should see:"
echo "      try_files {path} {path}/ /index.php?{query}"
echo "      file_server"
echo "      (not @disallowed with /storage/*)"
echo ""
echo "   3. Test storage access:"
echo "      curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png"
echo ""
echo "   4. Should return 200 OK, not 403"

