#!/bin/bash

# Development Environment Script for AdoJobs.id

echo "🚀 Starting AdoJobs.id Development Environment..."

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${GREEN}✅ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

print_error() {
    echo -e "${RED}❌ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    print_error "Docker is not running. Please start Docker first."
    exit 1
fi

# Stop any existing containers
print_info "Stopping existing containers..."
docker-compose -f docker-compose.dev.yml down

# Start development environment
print_info "Starting development environment..."
docker-compose -f docker-compose.dev.yml up --build -d

# Wait for containers to be ready
print_info "Waiting for containers to be ready..."
sleep 30

# Check if containers are running
if docker-compose -f docker-compose.dev.yml ps | grep -q "Up"; then
    print_status "Development environment started successfully!"
    
    # Run migrations
    print_info "Running database migrations..."
    docker-compose -f docker-compose.dev.yml exec app php artisan migrate --force
    
    # Run seeders
    print_info "Running database seeders..."
    docker-compose -f docker-compose.dev.yml exec app php artisan db:seed --class=LocalDataSeeder --force
    
    # Create admin user
    print_info "Creating admin user..."
    docker-compose -f docker-compose.dev.yml exec app php artisan tinker --execute="
    \$user = App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@adojobs.id',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'email_verified_at' => now()
    ]);
    echo 'Admin user created with ID: ' . \$user->id;
    "
    
    print_status "Development environment is ready!"
    echo ""
    print_info "🌐 Application: http://localhost:8282"
    print_info "🗄️  Database: http://localhost:8281 (phpMyAdmin)"
    print_info "👤 Admin Login: admin@adojobs.id / admin123"
    echo ""
    print_info "To stop the environment, run: ./dev.sh stop"
    
else
    print_error "Failed to start development environment"
    exit 1
fi
