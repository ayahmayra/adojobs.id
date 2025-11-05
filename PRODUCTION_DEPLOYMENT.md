# Production Deployment Guide - AdoJobs.id

## Overview
This guide covers the complete production deployment setup for AdoJobs.id on server IP `10.10.10.33` with domain `https://adojobs.id`.

## Prerequisites

1. **Server Requirements:**
   - Ubuntu/Debian Linux server
   - Minimum 2GB RAM, 2 CPU cores
   - Docker and Docker Compose installed
   - Ports 80, 443, and 443/udp open in firewall

2. **DNS Configuration:**
   - Point `adojobs.id` and `www.adojobs.id` to IP `10.10.10.33`

## Quick Start

### 1. Clone Repository
```bash
cd /path/to/deployment
git clone <repository-url> .
```

### 2. Setup Environment
```bash
# Copy production environment template
cp env.production.template .env.production

# Edit .env.production and update:
# - APP_KEY (generate after first deployment)
# - Email in docker/caddy/Caddyfile (line 6) for Let's Encrypt
```

### 3. Deploy
```bash
# Run deployment script
./deploy-production.sh
```

### 4. Generate APP_KEY (First Time Only)
```bash
# After first deployment, generate key:
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate --show

# Copy the output and update APP_KEY in .env.production
# Then restart:
docker-compose -f docker-compose.prod.yml restart app
```

## Configuration Details

### Database Credentials (Pre-generated)

**Database User:**
- Database: `adojobs_prod`
- Username: `adojobs_user`
- Password: `Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y`

**Database Root:**
- Root Password: `Gb43QutnerQYnEvB8Yc2y3nPccEI7LcI`

**⚠️ IMPORTANT:** These passwords are generated and included in `env.production.template`. For additional security, you can regenerate them using:
```bash
openssl rand -base64 32 | tr -d "=+/" | cut -c1-32
```

### Network Configuration

**Services:**
- **App (FrankenPHP):** Internal port 8080 (accessed via proxy)
- **Database (MariaDB):** Internal port 3306
- **Redis:** Internal port 6379
- **Proxy (Caddy):** External ports 80, 443

**Access:**
- HTTPS: `https://adojobs.id`
- HTTP (IP): `http://10.10.10.33`

### SSL/TLS Configuration

Caddy automatically handles SSL certificates via Let's Encrypt. To configure:

1. Update email in `docker/caddy/Caddyfile` (line 6):
```caddy
email your-email@example.com
```

2. For Cloudflare DNS (optional), add to Caddyfile:
```caddy
{
    email your-email@example.com
    acme_dns cloudflare
}
```

3. Restart proxy:
```bash
docker-compose -f docker-compose.prod.yml restart proxy
```

## Manual Deployment Steps

If you prefer manual deployment:

```bash
# 1. Build images
docker-compose -f docker-compose.prod.yml build

# 2. Start services
docker-compose -f docker-compose.prod.yml up -d

# 3. Wait for database (30 seconds)
sleep 30

# 4. Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 5. Generate APP_KEY (first time)
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate

# 6. Optimize application
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# 7. Set permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 /app/storage /app/bootstrap/cache

# 8. Create storage link
docker-compose -f docker-compose.prod.yml exec app php artisan storage:link
```

## Common Commands

### View Logs
```bash
# All services
docker-compose -f docker-compose.prod.yml logs -f

# Specific service
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f proxy
docker-compose -f docker-compose.prod.yml logs -f db
```

### Restart Services
```bash
# All services
docker-compose -f docker-compose.prod.yml restart

# Specific service
docker-compose -f docker-compose.prod.yml restart app
docker-compose -f docker-compose.prod.yml restart proxy
```

### Stop Services
```bash
docker-compose -f docker-compose.prod.yml down
```

### Check Status
```bash
docker-compose -f docker-compose.prod.yml ps
```

### Access Application Container
```bash
docker-compose -f docker-compose.prod.yml exec app bash
```

### Run Artisan Commands
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan <command>
```

## Database Management

### Backup Database
```bash
docker-compose -f docker-compose.prod.yml exec db mysqldump -u adojobs_user -p adojobs_prod > backup_$(date +%Y%m%d_%H%M%S).sql
```

### Restore Database
```bash
docker-compose -f docker-compose.prod.yml exec -T db mysql -u adojobs_user -p adojobs_prod < backup.sql
```

### Access Database
```bash
docker-compose -f docker-compose.prod.yml exec db mysql -u adojobs_user -p adojobs_prod
```

## Troubleshooting

### SSL Certificate Issues
```bash
# Check Caddy logs
docker-compose -f docker-compose.prod.yml logs proxy

# Restart proxy
docker-compose -f docker-compose.prod.yml restart proxy
```

### Application Not Starting
```bash
# Check app logs
docker-compose -f docker-compose.prod.yml logs app

# Check health
docker-compose -f docker-compose.prod.yml exec app curl -f http://localhost:8080/
```

### Database Connection Issues
```bash
# Check database logs
docker-compose -f docker-compose.prod.yml logs db

# Test connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Then: DB::connection()->getPdo();
```

### Clear All Caches
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## Security Checklist

- [ ] APP_KEY is set and secure
- [ ] Database passwords are strong and unique
- [ ] APP_DEBUG is set to `false`
- [ ] Firewall rules configured (ports 80, 443)
- [ ] DNS records properly configured
- [ ] SSL certificates are valid
- [ ] Regular backups configured
- [ ] Log rotation configured
- [ ] Updates scheduled

## Backup Strategy

### Automated Backup Script
Create a cron job for daily backups:

```bash
# Add to crontab (crontab -e)
0 2 * * * /path/to/backup-script.sh
```

Example backup script:
```bash
#!/bin/bash
BACKUP_DIR="/backups"
DATE=$(date +%Y%m%d_%H%M%S)
docker-compose -f docker-compose.prod.yml exec -T db mysqldump -u adojobs_user -p'Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y' adojobs_prod > $BACKUP_DIR/db_$DATE.sql
# Keep only last 7 days
find $BACKUP_DIR -name "db_*.sql" -mtime +7 -delete
```

## Monitoring

### Health Checks
All services have health checks configured. Monitor with:
```bash
docker-compose -f docker-compose.prod.yml ps
```

### Resource Usage
```bash
docker stats
```

## Updates

### Update Application
```bash
# Pull latest code
git pull

# Rebuild and restart
docker-compose -f docker-compose.prod.yml build app
docker-compose -f docker-compose.prod.yml up -d app

# Run migrations if any
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear and rebuild caches
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
```

## Support

For issues or questions, check:
1. Application logs: `docker-compose -f docker-compose.prod.yml logs app`
2. Proxy logs: `docker-compose -f docker-compose.prod.yml logs proxy`
3. Database logs: `docker-compose -f docker-compose.prod.yml logs db`

---

**Last Updated:** $(date)
**Server IP:** 10.10.10.33
**Domain:** https://adojobs.id

