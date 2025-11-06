# Production Deployment Guide - Final Version

## Status
✅ **PRODUCTION READY** - Semua konfigurasi sudah terbukti bekerja dengan baik

## Pre-requisites

### Server Requirements
- Ubuntu/Debian server
- Docker & Docker Compose installed
- Git installed
- Domain configured (adojobs.id pointing to server)
- Cloudflare configured for SSL/TLS (atau Nginx Proxy Manager)

### Repository
```bash
git clone https://github.com/ayahmayra/adojobs.id.git /var/www/adojobs.id
cd /var/www/adojobs.id
```

## Quick Deployment

### Option 1: Automated Deployment (Recommended)

```bash
cd /var/www/adojobs.id

# 1. Create .env.production from template
cp env.production.template .env.production

# 2. Edit .env.production (set passwords, APP_KEY, etc)
nano .env.production

# 3. Run deployment script
chmod +x deploy-production.sh
./deploy-production.sh
```

Script akan otomatis:
1. ✅ Check .env.production
2. ✅ Stop existing containers
3. ✅ Pull latest code
4. ✅ Build production image dengan --no-cache
5. ✅ Start all services
6. ✅ Generate APP_KEY (jika belum ada)
7. ✅ Wait for database ready
8. ✅ Run migrations
9. ✅ **Create storage link dan fix permissions** (NEW!)
10. ✅ Seed database (optional)
11. ✅ Optimize Laravel (cache config, routes, views)
12. ✅ **Verify storage permissions** (NEW!)

### Option 2: Manual Deployment

```bash
cd /var/www/adojobs.id

# 1. Create .env.production
cp env.production.template .env.production
nano .env.production  # Edit passwords, APP_KEY, etc

# 2. Stop existing containers
docker-compose -f docker-compose.prod.yml --env-file .env.production down

# 3. Pull latest code
sudo git pull origin main

# 4. Build with --no-cache (IMPORTANT!)
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache

# 5. Start services
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d

# 6. Wait for services to be healthy
sleep 30

# 7. Run migrations
docker exec adojobs_app php artisan migrate --force

# 8. Create storage link and fix permissions (IMPORTANT!)
docker exec adojobs_app php artisan storage:link
docker exec adojobs_app chown -R www-data:www-data /app/public /app/storage
docker exec adojobs_app chmod -R 755 /app/public
docker exec adojobs_app chmod -R 775 /app/storage

# 9. Seed database (optional)
docker exec adojobs_app php artisan db:seed --force

# 10. Optimize Laravel
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache

# 11. Verify
docker exec adojobs_app ls -la /app/public/ | grep storage
docker ps
```

## Configuration Files

### 1. docker-compose.prod.yml
✅ **VERIFIED** - Konfigurasi sudah benar

**Key Points:**
- `target: production` - Menggunakan production stage dari Dockerfile
- Storage volumes mounted untuk persistent uploads
- Environment variables dari `.env.production`
- Health checks configured
- Proxy (Caddy) untuk reverse proxy dari NPM

### 2. Dockerfile
✅ **VERIFIED** - Multi-stage build sudah optimal

**Key Points:**
- Stage 1: `base` - Base image dengan dependencies
- Stage 2: `development` - Development environment dengan Caddyfile.dev
- Stage 3: `production` - Production environment dengan Caddyfile.prod
- Permissions di-set saat build: `chown -R www-data:www-data`
- OPcache enabled untuk performance

### 3. docker/frankenphp/Caddyfile.prod
✅ **VERIFIED** - Urutan directive sudah benar

**Key Points:**
```caddy
# File server SEBELUM blocking rules (IMPORTANT!)
try_files {path} {path}/ /index.php?{query}
file_server

# Blocking rules SETELAH file_server
@disallowed {
    path /bootstrap/cache/* /vendor/*
    # /storage/* TIDAK diblokir (symlink valid)
}
respond @disallowed 403
```

### 4. docker/caddy/Caddyfile
✅ **VERIFIED** - Reverse proxy untuk NPM

**Key Points:**
- Listen on `:80` (HTTP from NPM)
- Forward headers: `X-Forwarded-*`, `Host`
- Set `X-Forwarded-Port 443` untuk HTTPS
- Match by `Host` header untuk domain routing

## Post-Deployment Steps

### 1. Verify Services

```bash
# Check all containers running and healthy
docker ps

# Expected:
# adojobs_app - healthy
# adojobs_db - healthy
# adojobs_redis - healthy
# adojobs_proxy - healthy
```

### 2. Verify Storage

```bash
# Check storage link
docker exec adojobs_app ls -la /app/public/ | grep storage
# Expected: storage -> /app/storage/app/public

# Check permissions
docker exec adojobs_app ls -ld /app/public
docker exec adojobs_app ls -ld /app/storage/app/public
# Expected owner: www-data:www-data
```

### 3. Verify Application

```bash
# Test direct access (bypass Cloudflare)
curl -I http://10.10.10.33/

# Test via domain
curl -I https://adojobs.id/

# Expected: HTTP 200 OK
```

### 4. Test Upload

1. Login to admin dashboard: `https://adojobs.id/admin/settings`
2. Upload test image (logo, favicon, banner)
3. Verify file saved: `docker exec adojobs_app ls -la /app/storage/app/public/settings/`
4. Test URL access: `https://adojobs.id/storage/settings/FILENAME.png`
5. Expected: Image displays, HTTP 200 OK

### 5. Purge Cloudflare Cache (If Needed)

1. Login to Cloudflare Dashboard: https://dash.cloudflare.com/
2. Select domain **adojobs.id**
3. Go to **Caching** > **Configuration**
4. Click **"Purge Everything"**
5. Wait 10-30 seconds
6. Test again

## Troubleshooting

### Issue: 403 Forbidden on /storage/*

**Diagnosis:**
```bash
./check-storage-permissions.sh
```

**Fix:**
```bash
./fix-all-storage-permissions.sh
```

**Verify:**
```bash
# Test direct IP (bypass Cloudflare)
curl -I http://10.10.10.33/storage/settings/test.png

# If 200 OK -> Cloudflare cache issue, purge cache
# If 403 -> Permission issue, run fix script again
```

### Issue: File uploaded but 404 Not Found

**Diagnosis:** Storage link broken or not created

**Fix:**
```bash
docker exec adojobs_app rm -f /app/public/storage
docker exec adojobs_app php artisan storage:link
docker exec adojobs_app ls -la /app/public/ | grep storage
```

### Issue: Container using old Caddyfile (dev version)

**Diagnosis:**
```bash
docker exec adojobs_app cat /etc/caddy/Caddyfile | head -20
```

**Fix:**
```bash
# Rebuild with --no-cache
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
```

### Issue: Database connection failed

**Diagnosis:**
```bash
docker logs adojobs_db
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo();"
```

**Fix:**
```bash
# Check .env.production passwords
# Verify DB_PASSWORD and DB_ROOT_PASSWORD set correctly
# Restart database
docker-compose -f docker-compose.prod.yml --env-file .env.production restart db
```

## Maintenance

### Update Application

```bash
cd /var/www/adojobs.id

# 1. Pull latest code
sudo git pull origin main

# 2. Rebuild app container
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app

# 3. Run migrations (if any)
docker exec adojobs_app php artisan migrate --force

# 4. Fix permissions (if needed)
docker exec adojobs_app chown -R www-data:www-data /app/public /app/storage

# 5. Optimize
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache

# 6. Purge Cloudflare cache
# Go to Cloudflare dashboard and purge cache
```

### Backup Database

```bash
# Backup
docker exec adojobs_db mysqldump -u adojobs_user -p adojobs_prod > backup-$(date +%Y%m%d).sql

# Restore
docker exec -i adojobs_db mysql -u adojobs_user -p adojobs_prod < backup-20251105.sql
```

### View Logs

```bash
# Application logs
docker logs adojobs_app -f

# Database logs
docker logs adojobs_db -f

# Proxy logs
docker logs adojobs_proxy -f

# Laravel logs
docker exec adojobs_app tail -f /app/storage/logs/laravel.log
```

## Security Checklist

- ✅ `APP_DEBUG=false` in production
- ✅ `APP_ENV=production`
- ✅ Strong `DB_PASSWORD` and `DB_ROOT_PASSWORD`
- ✅ Unique `APP_KEY` generated
- ✅ Cloudflare SSL/TLS enabled
- ✅ Firewall configured (only 80, 443 open)
- ✅ Storage permissions correct (`www-data:www-data`)
- ✅ Sensitive files blocked in Caddyfile (`.env*`, `.log`, `.sql`)

## Performance Checklist

- ✅ OPcache enabled
- ✅ Redis for cache and sessions
- ✅ Config, route, view cached
- ✅ Composer optimize-autoloader
- ✅ Frontend assets built and minified
- ✅ Cloudflare CDN enabled
- ✅ Gzip/Zstd compression enabled

## Summary

✅ **Deployment script updated** - Includes storage link dan permission fix
✅ **All configurations verified** - Docker Compose, Dockerfile, Caddyfile
✅ **Storage upload working** - Permissions correct, symlink created
✅ **Production ready** - Tested and working

## Related Documentation
- `STORAGE_UPLOAD_FIXED.md` - Complete storage fix documentation
- `FIX_CLOUDFLARE_CACHE.md` - Cloudflare cache troubleshooting
- `deploy-production.sh` - Automated deployment script
- `check-storage-permissions.sh` - Permission diagnosis tool
- `fix-all-storage-permissions.sh` - Permission fix tool

