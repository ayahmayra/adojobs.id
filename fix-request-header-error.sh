#!/bin/bash

# Script to fix "Uninitialized string offset" error in Symfony Request

set -e

echo "🔧 Fixing Request Header Error..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/3] Pulling latest Caddyfile changes..."
sudo git pull origin main
echo "✅ Latest changes pulled"
echo ""

echo "[2/3] Restarting proxy container to apply Caddyfile changes..."
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy
echo "✅ Proxy container restarted"
echo ""

echo "[3/3] Waiting for proxy to be ready..."
sleep 5
echo "✅ Proxy ready"
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Test the application:"
echo "   curl http://10.10.10.33/"
echo ""
echo "   If still error, check logs:"
echo "   docker logs adojobs_app --tail 50"
echo "   docker logs adojobs_proxy --tail 50"

