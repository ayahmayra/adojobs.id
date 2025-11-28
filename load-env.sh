#!/bin/bash

# ====================================
# Load .env.production and export variables
# ====================================

if [ -f ".env.production" ]; then
    # Export all variables from .env.production
    set -a
    source .env.production
    set +a
    echo "✓ Loaded .env.production"
else
    echo "✗ .env.production not found!"
    exit 1
fi

