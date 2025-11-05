#!/bin/bash

# ====================================
# Quick Fix for Deployment Issues
# ====================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}===================================${NC}"
echo -e "${GREEN}Quick Fix for Deployment${NC}"
echo -e "${GREEN}===================================${NC}\n"

# Step 1: Ensure .env.production exists
echo -e "${YELLOW}[1/4] Checking .env.production...${NC}"
if [ ! -f ".env.production" ]; then
    echo -e "${RED}✗ .env.production not found!${NC}"
    echo -e "${YELLOW}Creating from template...${NC}"
    cp env.production.template .env.production
    echo -e "${GREEN}✓ Created .env.production${NC}"
else
    echo -e "${GREEN}✓ .env.production exists${NC}"
    
    # Check if passwords are set
    if grep -q "DB_PASSWORD=CHANGE_THIS\|DB_PASSWORD=$" .env.production 2>/dev/null; then
        echo -e "${RED}✗ DB_PASSWORD is empty or placeholder!${NC}"
        echo -e "${YELLOW}Updating from template...${NC}"
        # Extract passwords from template
        if [ -f "env.production.template" ]; then
            DB_PASS=$(grep "^DB_PASSWORD=" env.production.template | cut -d '=' -f2)
            DB_ROOT=$(grep "^DB_ROOT_PASSWORD=" env.production.template | cut -d '=' -f2)
            sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASS|" .env.production
            sed -i "s|^DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=$DB_ROOT|" .env.production
            echo -e "${GREEN}✓ Updated passwords${NC}"
        fi
    else
        echo -e "${GREEN}✓ Passwords are set${NC}"
    fi
fi

# Step 2: Stop all containers
echo -e "\n${YELLOW}[2/4] Stopping containers...${NC}"
docker-compose -f docker-compose.prod.yml down 2>/dev/null || true
echo -e "${GREEN}✓ Containers stopped${NC}"

# Step 3: Load environment variables
echo -e "\n${YELLOW}[3/5] Loading environment variables...${NC}"
if [ -f ".env.production" ]; then
    set -a
    source .env.production
    set +a
    echo -e "${GREEN}✓ Environment variables loaded${NC}"
else
    echo -e "${RED}✗ .env.production not found!${NC}"
    exit 1
fi

# Step 4: Start database first with env_file
echo -e "\n${YELLOW}[4/5] Starting database...${NC}"
# Use --env-file to load variables for docker-compose substitution
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d db
echo -e "${YELLOW}Waiting for database to initialize (30 seconds)...${NC}"
sleep 30

# Check database status
if docker-compose -f docker-compose.prod.yml ps db | grep -q "healthy\|Up"; then
    echo -e "${GREEN}✓ Database is running${NC}"
else
    echo -e "${RED}✗ Database failed to start${NC}"
    echo -e "${YELLOW}Checking logs...${NC}"
    docker-compose -f docker-compose.prod.yml logs db --tail=30
    echo -e "\n${RED}Please check the logs above and fix the issue${NC}"
    exit 1
fi

# Step 5: Start all services with env_file
echo -e "\n${YELLOW}[5/5] Starting all services...${NC}"
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d

# Wait a bit
sleep 10

# Final status
echo -e "\n${GREEN}===================================${NC}"
echo -e "${GREEN}Deployment Status${NC}"
echo -e "${GREEN}===================================${NC}\n"
docker-compose -f docker-compose.prod.yml ps

echo -e "\n${YELLOW}Next steps:${NC}"
echo -e "1. Check logs: ${GREEN}docker-compose -f docker-compose.prod.yml logs -f${NC}"
echo -e "2. Generate APP_KEY: ${GREEN}./generate-app-key.sh${NC}"
echo -e "3. Run migrations: ${GREEN}docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force${NC}"

