#!/bin/bash

# Script to check app container health on production server

echo "🔍 Checking app container health..."
echo ""

# Check container status
echo "📊 Container Status:"
docker ps --filter "name=adojobs_app" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
echo ""

# Check logs for errors
echo "📋 Recent Logs (last 50 lines):"
docker logs adojobs_app --tail 50 2>&1 | grep -i "error\|fatal\|exception" || echo "No errors found in recent logs"
echo ""

# Test health endpoint
echo "🏥 Testing Health Endpoint:"
if docker exec adojobs_app curl -f http://localhost:8080/up > /dev/null 2>&1; then
    echo "✅ Health endpoint /up is responding"
else
    echo "❌ Health endpoint /up is not responding"
    echo ""
    echo "Testing root endpoint:"
    docker exec adojobs_app curl -f http://localhost:8080/ > /dev/null 2>&1 && echo "✅ Root endpoint is responding" || echo "❌ Root endpoint is not responding"
fi
echo ""

# Check database connection
echo "🔌 Testing Database Connection:"
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connected successfully';" 2>&1 | head -5
echo ""

# Check Redis connection
echo "📦 Testing Redis Connection:"
# Method 1: Direct connection test using redis-cli (most reliable)
if docker exec adojobs_redis redis-cli ping > /dev/null 2>&1; then
    echo "✅ Redis server is running and responding"
    # Method 2: Test from app container using Cache facade
    REDIS_TEST=$(docker exec adojobs_app php artisan tinker --execute="echo Cache::store('redis')->put('health_check', 'ok', 10) ? 'OK' : 'FAIL';" 2>&1 | tail -1 | grep -oE "OK|FAIL|true|false" | head -1)
    if [ "$REDIS_TEST" = "OK" ] || [ "$REDIS_TEST" = "true" ]; then
        echo "✅ Redis connection from app is working"
        # Clean up test key
        docker exec adojobs_app php artisan tinker --execute="Cache::store('redis')->forget('health_check');" > /dev/null 2>&1
    else
        echo "⚠️  Redis server is running but app connection may have issues"
        echo "   This is usually OK if CACHE_DRIVER is set to 'file' instead of 'redis'"
    fi
else
    echo "❌ Redis server is not responding"
fi
echo ""

# Check environment
echo "🌍 Environment Variables:"
docker exec adojobs_app env | grep -E "APP_ENV|APP_DEBUG|DB_HOST|REDIS_HOST" | sort
echo ""

echo "✅ Health check complete!"

