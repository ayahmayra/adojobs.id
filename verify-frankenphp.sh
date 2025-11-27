#!/bin/bash

echo "==================================="
echo "FrankenPHP Verification Script"
echo "==================================="
echo ""

# 1. Check Dockerfile
echo "1. Checking Dockerfile..."
if grep -q "dunglas/frankenphp" Dockerfile; then
    echo "   ✅ Dockerfile uses FrankenPHP base image"
else
    echo "   ❌ FrankenPHP not found in Dockerfile"
fi
echo ""

# 2. Check Caddyfile
echo "2. Checking Caddyfile..."
if [ -f "docker/frankenphp/Caddyfile" ]; then
    echo "   ✅ Caddyfile exists"
    if grep -q "frankenphp {" docker/frankenphp/Caddyfile; then
        echo "   ✅ FrankenPHP configuration found"
    fi
    if grep -q "worker {" docker/frankenphp/Caddyfile; then
        echo "   ✅ Worker mode enabled"
    fi
else
    echo "   ❌ Caddyfile not found"
fi
echo ""

# 3. Check if container is running
echo "3. Checking running container..."
if docker-compose ps | grep -q "app.*Up"; then
    echo "   ✅ App container is running"
    
    # Check process inside container
    echo "   Checking FrankenPHP process..."
    if docker-compose exec -T app ps aux | grep -q frankenphp; then
        echo "   ✅ FrankenPHP process is running"
    else
        echo "   ❌ FrankenPHP process not found"
    fi
else
    echo "   ⚠️  App container is not running"
    echo "   Run: docker-compose up -d"
fi
echo ""

# 4. Check FrankenPHP binary
echo "4. Checking FrankenPHP binary..."
if docker-compose exec -T app which frankenphp > /dev/null 2>&1; then
    echo "   ✅ FrankenPHP binary found"
    VERSION=$(docker-compose exec -T app frankenphp version 2>/dev/null | head -n 1)
    echo "   Version: $VERSION"
else
    echo "   ❌ FrankenPHP binary not found"
fi
echo ""

# 5. Test HTTP response
echo "5. Testing HTTP response..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8282 | grep -q "200\|302"; then
    echo "   ✅ Application is responding"
    
    # Check server header
    SERVER_HEADER=$(curl -s -I http://localhost:8282 | grep -i "server:" | cut -d' ' -f2-)
    if [ ! -z "$SERVER_HEADER" ]; then
        echo "   Server: $SERVER_HEADER"
    fi
else
    echo "   ❌ Application is not responding"
fi
echo ""

echo "==================================="
echo "Verification Complete!"
echo "==================================="
