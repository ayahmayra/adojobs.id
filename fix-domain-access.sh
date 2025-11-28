#!/bin/bash

# Script to fix domain access issue

set -e

echo "🔧 Fixing Domain Access Issue..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/4] Pulling latest Caddyfile changes..."
sudo git pull origin main
echo "✅ Latest changes pulled"
echo ""

echo "[2/4] Validating Caddyfile..."
VALIDATION_OUTPUT=$(docker exec adojobs_proxy caddy validate --config /etc/caddy/Caddyfile 2>&1 || echo "ERROR")
if echo "$VALIDATION_OUTPUT" | grep -q "valid\|Valid"; then
    echo "✅ Caddyfile is valid"
else
    echo "⚠️  Checking Caddyfile syntax..."
    echo "$VALIDATION_OUTPUT" | head -10
fi
echo ""

echo "[3/4] Restarting proxy container..."
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy
echo "✅ Proxy container restarted"
echo ""

echo "[4/4] Waiting for proxy to be ready..."
sleep 5
echo "✅ Proxy ready"
echo ""

echo "✅ Fix complete!"
echo ""
echo "📝 Testing:"
echo ""
echo "   Test with IP:"
echo "   curl http://10.10.10.33/"
echo ""
echo "   Test with domain (from NPM server):"
echo "   curl -H 'Host: adojobs.id' http://10.10.10.33/"
echo ""
echo "   Test from browser:"
echo "   https://adojobs.id (via NPM)"
echo ""
echo "   Check logs if still error:"
echo "   docker logs adojobs_proxy --tail 50"
echo "   docker logs adojobs_app --tail 50"

