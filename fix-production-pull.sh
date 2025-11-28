#!/bin/bash

# Script to safely pull updates on production server
# This handles local changes to docker/caddy/Caddyfile

set -e

echo "🔍 Checking current status..."
cd /var/www/adojobs.id || exit 1

# Backup current Caddyfile if it exists and has changes
if [ -f docker/caddy/Caddyfile ]; then
    echo "📦 Backing up current Caddyfile..."
    cp docker/caddy/Caddyfile docker/caddy/Caddyfile.backup.$(date +%Y%m%d_%H%M%S)
fi

echo "📥 Stashing local changes..."
git stash push -m "Backup before pull $(date +%Y-%m-%d_%H:%M:%S)" docker/caddy/Caddyfile

echo "⬇️  Pulling latest changes..."
git pull origin main

echo "✅ Pull completed successfully!"
echo ""
echo "📝 Note: If you had important local changes, they are in:"
echo "   git stash list"
echo "   To view: git stash show -p stash@{0}"
echo "   To apply: git stash pop"

