#!/bin/bash

# Script to check Laravel error details

set -e

echo "🔍 Checking Laravel Error Details..."
echo ""

cd /var/www/adojobs.id || exit 1

# Check if app container is running
if ! docker ps --format "{{.Names}}" | grep -q "^adojobs_app$"; then
    echo "❌ App container is not running!"
    exit 1
fi

echo "[1/4] Recent Laravel Logs (last 100 lines):"
docker exec adojobs_app tail -100 /app/storage/logs/laravel.log 2>/dev/null | tail -50
echo ""

echo "[2/4] Recent Errors/Exceptions:"
docker exec adojobs_app tail -200 /app/storage/logs/laravel.log 2>/dev/null | grep -i "error\|exception\|fatal" | tail -30
echo ""

echo "[3/4] Latest Error Stack Trace:"
docker exec adojobs_app tail -300 /app/storage/logs/laravel.log 2>/dev/null | grep -A 20 "production.ERROR" | tail -30
echo ""

echo "[4/4] Testing with Host header:"
echo "   Request: curl -H 'Host: adojobs.id' http://10.10.10.33/"
echo "   Response status:"
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" -H "Host: adojobs.id" http://10.10.10.33/
echo ""

echo "✅ Check complete!"
echo ""
echo "📝 Next steps based on error:"
echo "   - If 'Uninitialized string offset': Check request headers"
echo "   - If 'Table not found': Run migrations"
echo "   - If 'APP_KEY': Generate key"
echo "   - If other: Check error message above"

