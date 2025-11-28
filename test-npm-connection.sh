#!/bin/bash

# Script to test connection from NPM server to AdoJobs production server

echo "🔍 Testing Connection from NPM to AdoJobs Production Server"
echo ""

# Test 1: Basic HTTP connection
echo "[1/4] Testing basic HTTP connection..."
RESPONSE=$(curl -s -o /dev/null -w "%{http_code}" -H "Host: adojobs.id" http://10.10.10.33/)
if [ "$RESPONSE" = "200" ]; then
    echo "✅ HTTP connection OK (Status: $RESPONSE)"
else
    echo "❌ HTTP connection failed (Status: $RESPONSE)"
fi
echo ""

# Test 2: Full response with headers
echo "[2/4] Testing with verbose output..."
curl -v -H "Host: adojobs.id" http://10.10.10.33/ 2>&1 | head -30
echo ""

# Test 3: Check if container is accessible
echo "[3/4] Testing direct container access..."
curl -s -o /dev/null -w "Status: %{http_code}\n" http://10.10.10.33/ || echo "❌ Cannot connect to 10.10.10.33:80"
echo ""

# Test 4: Check response time
echo "[4/4] Testing response time..."
time curl -s -H "Host: adojobs.id" http://10.10.10.33/ > /dev/null
echo ""

echo "✅ Testing complete!"
echo ""
echo "📝 Next steps:"
echo "   1. If all tests pass, configure NPM proxy host"
echo "   2. If tests fail, check firewall and container status"
echo "   3. Check container logs: docker logs adojobs_proxy --tail 50"

