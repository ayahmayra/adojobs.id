# AdoJobs.id - Production Deployment

> **Status**: ✅ PRODUCTION READY - Verified and working on production server

## Quick Start

```bash
cd /var/www/adojobs.id
sudo git pull origin main
./deploy-production.sh
```

## Documentation Index

### 📖 Main Guides
1. **[DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)** - Quick start dan overview
2. **[PRODUCTION_DEPLOYMENT_FINAL.md](PRODUCTION_DEPLOYMENT_FINAL.md)** - Complete deployment guide
3. **[PRODUCTION_READY_CHECKLIST.md](PRODUCTION_READY_CHECKLIST.md)** - Verification checklist
4. **[FINAL_CONFIGURATION_SUMMARY.md](FINAL_CONFIGURATION_SUMMARY.md)** - Configuration details

### 🔧 Troubleshooting
1. **[STORAGE_UPLOAD_FIXED.md](STORAGE_UPLOAD_FIXED.md)** - Storage upload fix documentation
2. **[FIX_STORAGE_403_ORDER.md](FIX_STORAGE_403_ORDER.md)** - Caddyfile directive order fix
3. **[FIX_CLOUDFLARE_CACHE.md](FIX_CLOUDFLARE_CACHE.md)** - Cloudflare cache issues

### 🛠️ Tools & Scripts
1. **[deploy-production.sh](deploy-production.sh)** - Automated deployment script
2. **[check-storage-permissions.sh](check-storage-permissions.sh)** - Permission diagnosis
3. **[fix-all-storage-permissions.sh](fix-all-storage-permissions.sh)** - Auto-fix permissions
4. **[fix-cloudflare-cache.sh](fix-cloudflare-cache.sh)** - Cache bypass testing

## What's Configured

### ✅ Infrastructure
- Docker Compose production setup
- FrankenPHP (PHP 8.3) app server
- MariaDB 11.2 database
- Redis 7 for cache/session/queue
- Caddy reverse proxy

### ✅ Application
- Laravel 11 framework
- Production environment (DEBUG=false)
- OPcache enabled
- Redis cache configured
- Storage upload working

### ✅ Security
- SSL/TLS via Cloudflare
- Strong passwords
- File protection
- Correct permissions
- Production mode

### ✅ Performance
- OPcache bytecode caching
- Redis application cache
- Config/route/view cached
- Composer optimized
- Assets minified
- Gzip/Zstd compression

## Key Features

### 🚀 One-Command Deployment
```bash
./deploy-production.sh
```
Automatically handles:
- Build production image
- Start all services
- Run migrations
- Fix permissions
- Create storage link
- Optimize caches

### 🔍 Automatic Diagnosis
```bash
./check-storage-permissions.sh
```
Checks:
- File ownership
- Directory permissions
- Storage link status
- HTTP accessibility

### 🛠️ Automatic Fix
```bash
./fix-all-storage-permissions.sh
```
Fixes:
- File ownership (www-data)
- Directory permissions
- Storage link recreation
- Verification

## Architecture

```
Internet → Cloudflare (SSL/CDN) → NAT → NPM → Caddy → FrankenPHP → Laravel
                                                           ├→ MariaDB
                                                           └→ Redis
```

## Server Info

- **Domain**: https://adojobs.id
- **IP**: 10.10.10.33
- **Admin**: https://adojobs.id/admin

## Quick Commands

### Deployment
```bash
# Full deployment
./deploy-production.sh

# Quick update
sudo git pull && docker-compose -f docker-compose.prod.yml restart app
```

### Monitoring
```bash
# View logs
docker logs adojobs_app -f

# Check status
docker ps

# Laravel logs
docker exec adojobs_app tail -f /app/storage/logs/laravel.log
```

### Troubleshooting
```bash
# Check permissions
./check-storage-permissions.sh

# Fix permissions
./fix-all-storage-permissions.sh

# Restart services
docker-compose -f docker-compose.prod.yml restart
```

## Recent Fixes

### Storage Upload (403 Forbidden) ✅
- **Issue**: Files uploaded but 403 on access
- **Fixed**: Caddyfile directive order, permissions, Cloudflare cache
- **Status**: Working perfectly

### URL Generation ✅
- **Issue**: URLs with internal hostname
- **Fixed**: APP_URL configuration and cache
- **Status**: Working perfectly

### Domain Access ✅
- **Issue**: Works on IP but not domain
- **Fixed**: Caddyfile host matching, proxy headers
- **Status**: Working perfectly

## Support

### Documentation
Read the complete guides in order:
1. Start with `DEPLOYMENT_SUMMARY.md`
2. Then `PRODUCTION_READY_CHECKLIST.md`
3. For issues: `STORAGE_UPLOAD_FIXED.md`

### Tools
Use the provided scripts:
- Diagnosis: `./check-storage-permissions.sh`
- Fix: `./fix-all-storage-permissions.sh`
- Deploy: `./deploy-production.sh`

### Logs
Check logs for errors:
```bash
docker logs adojobs_app -f
docker exec adojobs_app tail -f /app/storage/logs/laravel.log
```

## Summary

✅ **Production Ready**
- All configurations verified
- Complete documentation
- Automated deployment
- Troubleshooting tools
- Performance optimized

🚀 **Next Deploy**: `./deploy-production.sh`

---

For detailed information, see [DEPLOYMENT_SUMMARY.md](DEPLOYMENT_SUMMARY.md)

