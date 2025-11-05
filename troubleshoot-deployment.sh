#!/bin/bash

# ====================================
# Troubleshooting Script for Deployment Issues
# ====================================

set -e

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}===================================${NC}"
echo -e "${YELLOW}Troubleshooting Deployment Issues${NC}"
echo -e "${YELLOW}===================================${NC}\n"

# Check 1: .env.production exists
echo -e "${YELLOW}[1/5] Checking .env.production...${NC}"
if [ ! -f ".env.production" ]; then
    echo -e "${RED}✗ .env.production not found!${NC}"
    echo -e "${YELLOW}Creating from template...${NC}"
    cp env.production.template .env.production
    echo -e "${GREEN}✓ Created .env.production${NC}"
    echo -e "${RED}IMPORTANT: Please verify the file exists and has correct values${NC}\n"
else
    echo -e "${GREEN}✓ .env.production exists${NC}"
    
    # Check if it has required variables
    if ! grep -q "DB_PASSWORD=" .env.production || grep -q "DB_PASSWORD=$" .env.production || grep -q "CHANGE_THIS" .env.production; then
        echo -e "${RED}✗ .env.production has empty or placeholder values!${NC}"
        echo -e "${YELLOW}Verifying variables...${NC}"
    else
        echo -e "${GREEN}✓ .env.production has values${NC}"
    fi
fi

# Check 2: Database container status
echo -e "\n${YELLOW}[2/5] Checking database container...${NC}"
if docker-compose -f docker-compose.prod.yml ps db | grep -q "Up"; then
    echo -e "${GREEN}✓ Database container is running${NC}"
    
    # Check health
    if docker-compose -f docker-compose.prod.yml ps db | grep -q "healthy"; then
        echo -e "${GREEN}✓ Database is healthy${NC}"
    else
        echo -e "${RED}✗ Database is unhealthy${NC}"
        echo -e "${YELLOW}Checking database logs...${NC}"
        docker-compose -f docker-compose.prod.yml logs db --tail=20
    fi
else
    echo -e "${RED}✗ Database container is not running${NC}"
    echo -e "${YELLOW}Checking why...${NC}"
    docker-compose -f docker-compose.prod.yml logs db --tail=30
fi

# Check 3: Environment variables
echo -e "\n${YELLOW}[3/5] Checking environment variables...${NC}"
if [ -f ".env.production" ]; then
    echo -e "${YELLOW}Key variables from .env.production:${NC}"
    grep -E "^DB_|^APP_" .env.production | grep -v "PASSWORD" | head -5
    echo -e "${YELLOW}(Passwords hidden for security)${NC}"
fi

# Check 4: Docker compose file
echo -e "\n${YELLOW}[4/5] Verifying docker-compose.prod.yml...${NC}"
if [ -f "docker-compose.prod.yml" ]; then
    echo -e "${GREEN}✓ docker-compose.prod.yml exists${NC}"
    
    # Check if env_file is specified
    if grep -q "env_file:" docker-compose.prod.yml; then
        echo -e "${GREEN}✓ env_file is configured${NC}"
    else
        echo -e "${RED}✗ env_file not found in docker-compose.prod.yml${NC}"
    fi
else
    echo -e "${RED}✗ docker-compose.prod.yml not found!${NC}"
fi

# Check 5: Network and volumes
echo -e "\n${YELLOW}[5/5] Checking Docker resources...${NC}"
echo -e "${YELLOW}Volumes:${NC}"
docker volume ls | grep adojobsid || echo "No volumes found"

echo -e "\n${YELLOW}Networks:${NC}"
docker network ls | grep adojobs || echo "No networks found"

# Recommendations
echo -e "\n${YELLOW}===================================${NC}"
echo -e "${YELLOW}Recommended Actions:${NC}"
echo -e "${YELLOW}===================================${NC}\n"

if [ ! -f ".env.production" ]; then
    echo -e "1. ${GREEN}Create .env.production:${NC}"
    echo -e "   ${YELLOW}cp env.production.template .env.production${NC}\n"
fi

echo -e "2. ${GREEN}Stop all containers:${NC}"
echo -e "   ${YELLOW}docker-compose -f docker-compose.prod.yml down${NC}\n"

echo -e "3. ${GREEN}Check database logs:${NC}"
echo -e "   ${YELLOW}docker-compose -f docker-compose.prod.yml logs db${NC}\n"

echo -e "4. ${GREEN}Restart with fresh start:${NC}"
echo -e "   ${YELLOW}docker-compose -f docker-compose.prod.yml up -d${NC}\n"

echo -e "5. ${GREEN}If still failing, check MySQL config:${NC}"
echo -e "   ${YELLOW}ls -la docker/mysql/my.cnf${NC}\n"

