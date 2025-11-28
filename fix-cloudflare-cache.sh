#!/bin/bash

# Script to test storage access bypassing Cloudflare cache

set -e

echo "🔧 Testing Storage Access - Bypassing Cloudflare Cache..."
echo ""

cd /var/www/adojobs.id || exit 1

echo "[1/3] Testing direct IP access (bypass Cloudflare)..."
echo "URL: http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png"
curl -I http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png | head -5
echo ""

echo "[2/3] Testing with Host header (simulate domain access)..."
curl -I -H "Host: adojobs.id" http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png | head -5
echo ""

echo "[3/3] Testing via domain (through Cloudflare - might be cached)..."
echo "URL: https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png"
curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png | head -10
echo ""

echo "📝 Analysis:"
echo "   - If IP test returns 200 OK: Application is fixed, Cloudflare cache is the issue"
echo "   - If IP test returns 403: Application still needs fixing"
echo ""
echo "🔥 If Cloudflare cache is the issue, you need to:"
echo "   1. Login to Cloudflare dashboard"
echo "   2. Go to Caching > Configuration"
echo "   3. Click 'Purge Everything' or 'Purge by URL'"
echo "   4. Purge: https://adojobs.id/storage/*"
echo ""
echo "⚡ Or use Cloudflare API to purge cache:"
echo "   curl -X POST 'https://api.cloudflare.com/client/v4/zones/YOUR_ZONE_ID/purge_cache' \\"
echo "     -H 'Authorization: Bearer YOUR_API_TOKEN' \\"
echo "     -H 'Content-Type: application/json' \\"
echo "     --data '{\"files\":[\"https://adojobs.id/storage/settings/*\"]}'"

