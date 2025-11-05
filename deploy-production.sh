#!/bin/bash

# ====================================
# AdoJobs.id Production Deployment Script
# ====================================

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}===================================${NC}"
echo -e "${GREEN}AdoJobs.id Production Deployment${NC}"
echo -e "${GREEN}===================================${NC}\n"

# Check if .env.production exists
if [ ! -f ".env.production" ]; then
    echo -e "${YELLOW}Warning: .env.production not found!${NC}"
    echo -e "${YELLOW}Creating from template...${NC}"
    cp env.production.template .env.production
    echo -e "${RED}IMPORTANT: Please edit .env.production and set APP_KEY using:${NC}"
    echo -e "${RED}  docker-compose -f docker-compose.prod.yml exec app php artisan key:generate${NC}"
    echo -e "${RED}Then copy the key to APP_KEY in .env.production${NC}\n"
    read -p "Press Enter after you've updated .env.production..."
fi

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}Error: Docker is not running!${NC}"
    exit 1
fi

# Check if docker-compose is available
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}Error: docker-compose is not installed!${NC}"
    exit 1
fi

echo -e "${GREEN}Step 1: Building Docker images...${NC}"
docker-compose -f docker-compose.prod.yml build --no-cache

echo -e "\n${GREEN}Step 2: Starting containers...${NC}"
docker-compose -f docker-compose.prod.yml up -d

echo -e "\n${GREEN}Step 3: Waiting for services to be ready...${NC}"
sleep 10

# Wait for database to be ready
echo -e "${YELLOW}Waiting for database...${NC}"
timeout=60
counter=0
until docker-compose -f docker-compose.prod.yml exec -T db mysqladmin ping -h localhost --silent 2>/dev/null; do
    sleep 2
    counter=$((counter + 2))
    if [ $counter -ge $timeout ]; then
        echo -e "${RED}Database failed to start within $timeout seconds${NC}"
        exit 1
    fi
done
echo -e "${GREEN}Database is ready!${NC}"

# Check if APP_KEY needs to be set
if grep -q "CHANGE_THIS_ON_FIRST_DEPLOYMENT" .env.production; then
    echo -e "\n${YELLOW}Step 4: Generating application key...${NC}"
    APP_KEY=$(docker-compose -f docker-compose.prod.yml exec -T app php artisan key:generate --show 2>/dev/null | tail -1)
    if [ ! -z "$APP_KEY" ]; then
        echo -e "${GREEN}Generated APP_KEY: $APP_KEY${NC}"
        echo -e "${YELLOW}Please update APP_KEY in .env.production file${NC}"
        echo -e "${YELLOW}Then restart containers: docker-compose -f docker-compose.prod.yml restart app${NC}"
    fi
else
    echo -e "\n${GREEN}Step 4: Application key already set${NC}"
fi

echo -e "\n${GREEN}Step 5: Running database migrations...${NC}"
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

echo -e "\n${GREEN}Step 6: Clearing and optimizing caches...${NC}"
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec -T app php artisan view:clear

echo -e "\n${GREEN}Step 7: Optimizing application...${NC}"
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec -T app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec -T app php artisan view:cache

echo -e "\n${GREEN}Step 8: Setting permissions...${NC}"
docker-compose -f docker-compose.prod.yml exec -T app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker-compose -f docker-compose.prod.yml exec -T app chmod -R 775 /app/storage /app/bootstrap/cache

echo -e "\n${GREEN}Step 9: Creating storage link...${NC}"
docker-compose -f docker-compose.prod.yml exec -T app php artisan storage:link || true

echo -e "\n${GREEN}===================================${NC}"
echo -e "${GREEN}Deployment completed successfully!${NC}"
echo -e "${GREEN}===================================${NC}\n"

echo -e "${YELLOW}Next steps:${NC}"
echo -e "1. Update DNS records to point adojobs.id and www.adojobs.id to ${YELLOW}10.10.10.33${NC}"
echo -e "2. Update Caddyfile email in ${YELLOW}docker/caddy/Caddyfile${NC} (line 6)"
echo -e "3. Restart proxy: ${YELLOW}docker-compose -f docker-compose.prod.yml restart proxy${NC}"
echo -e "4. Check logs: ${YELLOW}docker-compose -f docker-compose.prod.yml logs -f${NC}"
echo -e "5. Access via: ${GREEN}https://adojobs.id${NC} or ${GREEN}http://10.10.10.33${NC}\n"

echo -e "${YELLOW}Useful commands:${NC}"
echo -e "  View logs: ${GREEN}docker-compose -f docker-compose.prod.yml logs -f${NC}"
echo -e "  Stop: ${GREEN}docker-compose -f docker-compose.prod.yml down${NC}"
echo -e "  Restart: ${GREEN}docker-compose -f docker-compose.prod.yml restart${NC}"
echo -e "  Status: ${GREEN}docker-compose -f docker-compose.prod.yml ps${NC}"

