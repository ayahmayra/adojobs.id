#!/bin/bash

# Quick script to generate APP_KEY for production
# Usage: ./generate-app-key.sh

echo "Generating APP_KEY for production..."

if [ ! -f ".env.production" ]; then
    echo "Error: .env.production not found!"
    echo "Please create it first: cp env.production.template .env.production"
    exit 1
fi

# Check if docker-compose is running
if ! docker-compose -f docker-compose.prod.yml ps | grep -q "adojobs_app"; then
    echo "Error: Application container is not running!"
    echo "Please start containers first: docker-compose -f docker-compose.prod.yml up -d"
    exit 1
fi

# Generate key
echo "Generating application key..."
APP_KEY=$(docker-compose -f docker-compose.prod.yml exec -T app php artisan key:generate --show 2>/dev/null | grep -o 'base64:[^ ]*' | head -1)

if [ -z "$APP_KEY" ]; then
    echo "Error: Failed to generate APP_KEY"
    exit 1
fi

echo ""
echo "=========================================="
echo "Generated APP_KEY:"
echo "$APP_KEY"
echo "=========================================="
echo ""
echo "Update .env.production with this key:"
echo "  APP_KEY=$APP_KEY"
echo ""
echo "Then restart the application:"
echo "  docker-compose -f docker-compose.prod.yml restart app"
echo ""

