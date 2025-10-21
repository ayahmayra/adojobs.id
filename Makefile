.PHONY: help up down build rebuild restart logs shell composer artisan migrate seed fresh test clean install

# Default target
.DEFAULT_GOAL := help

# Colors for output
BLUE := \033[0;34m
GREEN := \033[0;32m
YELLOW := \033[0;33m
NC := \033[0m # No Color

help: ## Show this help message
	@echo "$(BLUE)JobMaker - Laravel Job Portal System$(NC)"
	@echo "$(BLUE)========================================$(NC)"
	@echo ""
	@echo "$(GREEN)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-15s$(NC) %s\n", $$1, $$2}'

up: ## Start all Docker containers
	@echo "$(GREEN)Starting Docker containers...$(NC)"
	docker-compose up -d

down: ## Stop all Docker containers
	@echo "$(YELLOW)Stopping Docker containers...$(NC)"
	docker-compose down

build: ## Build Docker images
	@echo "$(GREEN)Building Docker images...$(NC)"
	docker-compose build --no-cache

rebuild: down build up ## Rebuild and restart all containers

restart: down up ## Restart all containers

logs: ## Show logs from all containers
	docker-compose logs -f

logs-app: ## Show logs from app container only
	docker-compose logs -f app

shell: ## Access shell in app container
	docker-compose exec app bash

shell-db: ## Access MariaDB shell
	docker-compose exec db mysql -u jobmaker -psecret jobmaker

composer: ## Run composer command (usage: make composer ARGS="install")
	docker-compose exec app composer $(ARGS)

artisan: ## Run artisan command (usage: make artisan ARGS="migrate")
	docker-compose exec app php artisan $(ARGS)

migrate: ## Run database migrations
	@echo "$(GREEN)Running migrations...$(NC)"
	docker-compose exec app php artisan migrate

migrate-fresh: ## Fresh migrations (drop all tables and re-migrate)
	@echo "$(YELLOW)Running fresh migrations...$(NC)"
	docker-compose exec app php artisan migrate:fresh

seed: ## Seed the database
	@echo "$(GREEN)Seeding database...$(NC)"
	docker-compose exec app php artisan db:seed

fresh: ## Fresh migrations with seeding
	@echo "$(GREEN)Running fresh migrations with seeding...$(NC)"
	docker-compose exec app php artisan migrate:fresh --seed

test: ## Run tests
	docker-compose exec app php artisan test

optimize: ## Optimize Laravel for production
	@echo "$(GREEN)Optimizing Laravel...$(NC)"
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache
	docker-compose exec app php artisan view:cache
	docker-compose exec app php artisan optimize

clear: ## Clear all Laravel caches
	@echo "$(YELLOW)Clearing caches...$(NC)"
	docker-compose exec app php artisan config:clear
	docker-compose exec app php artisan route:clear
	docker-compose exec app php artisan view:clear
	docker-compose exec app php artisan cache:clear
	docker-compose exec app php artisan optimize:clear

clean: down ## Clean up Docker resources
	@echo "$(YELLOW)Cleaning up Docker resources...$(NC)"
	docker-compose down -v
	docker system prune -f

install: ## Initial project setup
	@echo "$(GREEN)Setting up JobMaker project...$(NC)"
	@if [ ! -d "src" ]; then \
		echo "$(GREEN)Creating Laravel project...$(NC)"; \
		docker run --rm -v $(PWD):/app composer:latest create-project laravel/laravel:^11.0 src --prefer-dist; \
	fi
	@if [ ! -f "src/.env" ]; then \
		echo "$(GREEN)Creating .env file...$(NC)"; \
		cp src/.env.example src/.env; \
	fi
	@echo "$(GREEN)Starting containers...$(NC)"
	docker-compose up -d
	@echo "$(GREEN)Installing dependencies...$(NC)"
	docker-compose exec app composer install
	@echo "$(GREEN)Generating application key...$(NC)"
	docker-compose exec app php artisan key:generate
	@echo "$(GREEN)Running migrations...$(NC)"
	docker-compose exec app php artisan migrate:fresh --seed
	@echo "$(GREEN)Installation complete!$(NC)"
	@echo "$(BLUE)Access the application at: http://localhost:8080$(NC)"
	@echo "$(BLUE)Access PHPMyAdmin at: http://localhost:8081$(NC)"

queue: ## Start queue worker
	docker-compose exec app php artisan queue:work

storage-link: ## Create storage symbolic link
	docker-compose exec app php artisan storage:link

npm-install: ## Install npm dependencies
	docker-compose exec app npm install

npm-dev: ## Run npm dev
	docker-compose exec app npm run dev

npm-build: ## Build assets for production
	docker-compose exec app npm run build

