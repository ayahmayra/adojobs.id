#!/bin/bash

# ===================================
# AdoJobs Production Deployment Script
# ===================================

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}==================================${NC}"
echo -e "${GREEN}AdoJobs Production Deployment${NC}"
echo -e "${GREEN}==================================${NC}"

# Check if .env.production exists
if [ ! -f ".env.production" ]; then
    echo -e "${RED}Error: .env.production file not found!${NC}"
    echo -e "${YELLOW}Please copy .env.production.example to .env.production and configure it.${NC}"
    exit 1
fi

# Step 1: Pull latest code
echo -e "\n${GREEN}[1/10] Pulling latest code from Git...${NC}"
git pull origin main

# Step 2: Stop existing containers
echo -e "\n${GREEN}[2/10] Stopping existing containers...${NC}"
docker-compose -f docker-compose.prod.yml down

# Step 3: Build fresh images
echo -e "\n${GREEN}[3/10] Building Docker images...${NC}"
docker-compose -f docker-compose.prod.yml build --no-cache app

# Step 4: Start database and redis first
echo -e "\n${GREEN}[4/10] Starting database and Redis...${NC}"
docker-compose -f docker-compose.prod.yml up -d db redis

# Wait for database to be ready
echo -e "${YELLOW}Waiting for database to be ready...${NC}"
sleep 20

# Step 5: Copy .env.production to src/.env
echo -e "\n${GREEN}[5/10] Configuring environment...${NC}"
cp .env.production src/.env

# Step 6: Install dependencies inside a temporary container
echo -e "\n${GREEN}[6/10] Installing dependencies...${NC}"
docker-compose -f docker-compose.prod.yml run --rm app composer install --no-dev --optimize-autoloader --no-interaction

# Step 7: Generate application key if not set
echo -e "\n${GREEN}[7/10] Setting up application key...${NC}"
docker-compose -f docker-compose.prod.yml run --rm app php artisan key:generate --force

# Step 8: Run migrations
echo -e "\n${GREEN}[8/10] Running database migrations...${NC}"
docker-compose -f docker-compose.prod.yml run --rm app php artisan migrate --force

# Step 9: Optimize Laravel
echo -e "\n${GREEN}[9/10] Optimizing Laravel...${NC}"
docker-compose -f docker-compose.prod.yml run --rm app php artisan config:cache
docker-compose -f docker-compose.prod.yml run --rm app php artisan route:cache
docker-compose -f docker-compose.prod.yml run --rm app php artisan view:cache
docker-compose -f docker-compose.prod.yml run --rm app php artisan optimize

# Step 10: Create storage link
echo -e "\n${GREEN}[10/10] Creating storage link...${NC}"
docker-compose -f docker-compose.prod.yml run --rm app php artisan storage:link

# Start all services
echo -e "\n${GREEN}Starting all services...${NC}"
docker-compose -f docker-compose.prod.yml up -d

# Wait for app to be ready
echo -e "${YELLOW}Waiting for application to be ready...${NC}"
sleep 10

# Check container status
echo -e "\n${GREEN}Container Status:${NC}"
docker-compose -f docker-compose.prod.yml ps

# Show logs
echo -e "\n${GREEN}Recent logs (last 20 lines):${NC}"
docker-compose -f docker-compose.prod.yml logs --tail=20 app

echo -e "\n${GREEN}==================================${NC}"
echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}==================================${NC}"
echo -e "\n${YELLOW}Application should be running at:${NC} http://localhost:8282"
echo -e "\n${YELLOW}To view logs:${NC} docker-compose -f docker-compose.prod.yml logs -f"
echo -e "${YELLOW}To stop:${NC} docker-compose -f docker-compose.prod.yml down"
echo -e "${YELLOW}To restart:${NC} docker-compose -f docker-compose.prod.yml restart app"


