#!/bin/bash

# ====================================
# Deploy with Environment Variables Loaded
# ====================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${GREEN}===================================${NC}"
echo -e "${GREEN}Deploy with Environment Variables${NC}"
echo -e "${GREEN}===================================${NC}\n"

# Step 1: Check .env.production
if [ ! -f ".env.production" ]; then
    echo -e "${RED}✗ .env.production not found!${NC}"
    echo -e "${YELLOW}Creating from template...${NC}"
    cp env.production.template .env.production
    echo -e "${GREEN}✓ Created .env.production${NC}"
fi

# Step 2: Load environment variables
echo -e "${YELLOW}Loading environment variables from .env.production...${NC}"
set -a
source .env.production
set +a
echo -e "${GREEN}✓ Environment variables loaded${NC}"

# Step 3: Verify critical variables
if [ -z "$DB_PASSWORD" ] || [ -z "$DB_ROOT_PASSWORD" ]; then
    echo -e "${RED}✗ DB_PASSWORD or DB_ROOT_PASSWORD is empty!${NC}"
    echo -e "${YELLOW}Please check .env.production${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Critical variables verified${NC}"

# Step 4: Deploy
echo -e "\n${YELLOW}Deploying containers...${NC}"
docker-compose -f docker-compose.prod.yml up -d

# Step 5: Wait and check status
echo -e "\n${YELLOW}Waiting for services to start...${NC}"
sleep 15

echo -e "\n${GREEN}Deployment Status:${NC}"
docker-compose -f docker-compose.prod.yml ps

