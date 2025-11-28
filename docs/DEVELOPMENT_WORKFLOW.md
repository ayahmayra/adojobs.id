# Development Workflow Guide

## Overview
Dokumentasi ini menjelaskan cara melakukan development AdoJobs.id dengan dual environment setup (Development & Production).

## Environment Setup

### **Development Environment (Local)**
- **Purpose:** Development, testing, debugging
- **Technology:** PHP built-in server (stabil untuk development)
- **Database:** MariaDB dengan data sample
- **Cache:** File-based (tidak perlu Redis)
- **URL:** http://localhost:8282

### **Production Environment (Server)**
- **Purpose:** Live application untuk users
- **Technology:** FrankenPHP dengan worker mode (performansi optimal)
- **Database:** MariaDB dengan data real
- **Cache:** Redis (performansi tinggi)
- **URL:** https://adojobs.id

## Development Workflow

### **1. Start Development Environment**
```bash
# Gunakan script otomatis
./dev.sh

# Atau manual
docker-compose -f docker-compose.dev.yml up --build -d
```

### **2. Development Tasks**
```bash
# Install dependencies
docker-compose -f docker-compose.dev.yml exec app composer install

# Run migrations
docker-compose -f docker-compose.dev.yml exec app php artisan migrate

# Run seeders
docker-compose -f docker-compose.dev.yml exec app php artisan db:seed --class=LocalDataSeeder

# Clear cache
docker-compose -f docker-compose.dev.yml exec app php artisan cache:clear

# Run tests
docker-compose -f docker-compose.dev.yml exec app php artisan test
```

### **3. Code Changes**
- Edit files di `src/` directory
- Changes akan langsung terlihat di http://localhost:8282
- Hot reload dengan PHP built-in server

### **4. Database Management**
- **phpMyAdmin:** http://localhost:8281
- **Direct access:** `docker-compose -f docker-compose.dev.yml exec db mariadb -u root -proot_secret`

### **5. Stop Development Environment**
```bash
# Stop containers
docker-compose -f docker-compose.dev.yml down

# Stop dan hapus volumes (reset database)
docker-compose -f docker-compose.dev.yml down -v
```

## Production Deployment

### **1. Commit Changes**
```bash
git add .
git commit -m "Feature: Add new functionality"
git push origin main
```

### **2. Deploy to Production**
```bash
# Di server production
git pull origin main
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up --build -d
```

### **3. Run Production Migrations**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

## Development vs Production Differences

| Aspect | Development | Production |
|--------|-------------|------------|
| **Server** | PHP built-in | FrankenPHP worker |
| **Cache** | File-based | Redis |
| **Database** | Local MariaDB | Production MariaDB |
| **Debug** | APP_DEBUG=true | APP_DEBUG=false |
| **Assets** | Vite dev server | Built assets |
| **Performance** | Development-focused | Production-optimized |

## Troubleshooting

### **Development Issues**
```bash
# Check container status
docker-compose -f docker-compose.dev.yml ps

# Check logs
docker-compose -f docker-compose.dev.yml logs app

# Restart containers
docker-compose -f docker-compose.dev.yml restart app
```

### **Production Issues**
```bash
# Check production status
docker-compose -f docker-compose.prod.yml ps

# Check production logs
docker-compose -f docker-compose.prod.yml logs app

# Restart production
docker-compose -f docker-compose.prod.yml restart app
```

## Best Practices

### **Development**
1. **Always test locally** sebelum deploy ke production
2. **Use feature branches** untuk development
3. **Run tests** sebelum commit
4. **Clear cache** setelah changes

### **Production**
1. **Backup database** sebelum major updates
2. **Test in staging** environment jika ada
3. **Monitor logs** setelah deployment
4. **Rollback plan** siap jika ada issues

## Quick Commands

### **Development**
```bash
# Start development
./dev.sh

# Stop development
docker-compose -f docker-compose.dev.yml down

# Reset development database
docker-compose -f docker-compose.dev.yml down -v
./dev.sh
```

### **Production**
```bash
# Deploy to production
git pull origin main
docker-compose -f docker-compose.prod.yml up --build -d

# Check production status
curl -I https://adojobs.id
```

## URLs

### **Development**
- **Application:** http://localhost:8282
- **Database Admin:** http://localhost:8281
- **Admin Login:** admin@adojobs.id / admin123

### **Production**
- **Application:** https://adojobs.id
- **Admin Panel:** https://adojobs.id/admin/dashboard

---

**Last Updated:** October 21, 2025  
**Version:** 1.0  
**Author:** AdoJobs.id Development Team
