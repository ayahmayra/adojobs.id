# 🚀 AdoJobs Production Deployment Guide

## Quick Links

- 📋 [Pre-Deployment Checklist](PRODUCTION_DEPLOYMENT_CHECKLIST.md) - Checklist lengkap sebelum deployment
- ✅ [Production Ready Review](PRODUCTION_READY_REVIEW.md) - Review kesiapan production
- 🔧 [Development Workflow](DEVELOPMENT_WORKFLOW.md) - Panduan development

---

## 🎯 Quick Start (Local Testing)

Test konfigurasi production di local environment Anda:

```bash
# 1. Pastikan .env.production sudah ada (sudah dibuat)
ls -la .env.production

# 2. Build dan jalankan production stack
docker-compose -f docker-compose.prod.yml up -d --build

# 3. Tunggu container siap (20-30 detik)
docker-compose -f docker-compose.prod.yml ps

# 4. Copy .env.production ke dalam container
docker-compose -f docker-compose.prod.yml exec app cp /app/../.env.production /app/.env

# 5. Jalankan migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 6. Seed database (opsional, untuk testing)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# 7. Optimize Laravel
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
docker-compose -f docker-compose.prod.yml exec app php artisan optimize

# 8. Test aplikasi
curl http://localhost:8282
open http://localhost:8282

# 9. Lihat logs
docker-compose -f docker-compose.prod.yml logs -f app

# 10. Stop saat selesai testing
docker-compose -f docker-compose.prod.yml down
```

---

## 🚀 Production Deployment (Server)

### Pertama Kali Deployment

```bash
# 1. Clone repository di server
git clone https://github.com/ayahmayra/adojobs.id.git
cd adojobs.id

# 2. Buat file .env.production
cp env.production.example .env.production

# 3. Edit dengan nilai production yang sebenarnya
nano .env.production
# Update:
#   - APP_KEY (generate baru)
#   - APP_URL (domain production)
#   - DB_PASSWORD (password kuat)
#   - DB_ROOT_PASSWORD (password kuat)
#   - Mail settings (jika ada)

# 4. Jalankan deployment script
chmod +x deploy.sh
./deploy.sh

# 5. Seed database (hanya sekali)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
```

### Update Deployment (Pull Changes)

```bash
# Cara termudah - gunakan script
./deploy.sh

# Atau gunakan Makefile
make -f Makefile.prod deploy

# Atau manual
git pull origin main
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml up -d
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
```

---

## 📁 File Structure Production

```
.
├── docker-compose.prod.yml          # Production Docker Compose ⭐
├── .env.production                  # Production environment (don't commit!)
├── env.production.example           # Production env template
├── deploy.sh                        # Automated deployment script ⭐
├── Makefile.prod                    # Production commands ⭐
│
├── Dockerfile                       # Multi-stage (dev + prod)
├── docker/
│   ├── frankenphp/
│   │   ├── Caddyfile               # Development config
│   │   └── Caddyfile.prod          # Production config ⭐
│   └── mysql/
│       └── my.cnf                  # MySQL config
│
├── PRODUCTION_DEPLOYMENT_CHECKLIST.md  # Deployment checklist
├── PRODUCTION_READY_REVIEW.md          # Kesiapan production
└── README_PRODUCTION.md                # File ini
```

---

## 🛠️ Common Commands (Production)

### Container Management

```bash
# Start services
docker-compose -f docker-compose.prod.yml up -d

# Stop services
docker-compose -f docker-compose.prod.yml down

# Restart app only
docker-compose -f docker-compose.prod.yml restart app

# Restart all
docker-compose -f docker-compose.prod.yml restart

# Check status
docker-compose -f docker-compose.prod.yml ps

# View logs
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f

# Access shell
docker-compose -f docker-compose.prod.yml exec app bash
```

### Laravel Commands

```bash
# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Seed database
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force

# Optimize
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
docker-compose -f docker-compose.prod.yml exec app php artisan optimize

# Clear cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec app php artisan view:clear

# Tinker
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
```

### Database

```bash
# Access database
docker-compose -f docker-compose.prod.yml exec db mysql -u root -p

# Backup database
docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p adojobs > backup-$(date +%Y%m%d).sql

# Restore database
docker-compose -f docker-compose.prod.yml exec -T db mysql -u root -p adojobs < backup-20241104.sql

# Test connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Then: DB::connection()->getPdo();
```

### Makefile Commands (Easier!)

```bash
# Deploy (full process)
make -f Makefile.prod deploy

# Container management
make -f Makefile.prod up
make -f Makefile.prod down
make -f Makefile.prod restart
make -f Makefile.prod status

# Logs
make -f Makefile.prod logs
make -f Makefile.prod logs-tail

# Shell access
make -f Makefile.prod shell
make -f Makefile.prod db-shell
make -f Makefile.prod tinker

# Laravel operations
make -f Makefile.prod migrate
make -f Makefile.prod optimize
make -f Makefile.prod clear-cache

# Database
make -f Makefile.prod db-backup
make -f Makefile.prod db-restore BACKUP_FILE=backup-20241104.sql

# Monitoring
make -f Makefile.prod health
make -f Makefile.prod stats

# Emergency
make -f Makefile.prod emergency-down

# See all commands
make -f Makefile.prod help
```

---

## 🔍 Troubleshooting

### Container tidak mau start

```bash
# Check logs
docker-compose -f docker-compose.prod.yml logs app

# Check environment
docker-compose -f docker-compose.prod.yml exec app php artisan config:show

# Rebuild
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml up -d
```

### Database connection error

```bash
# Check database status
docker-compose -f docker-compose.prod.yml ps db

# Check database logs
docker-compose -f docker-compose.prod.yml logs db

# Test connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Then: DB::connection()->getPdo();

# Check credentials in .env.production
cat .env.production | grep DB_
```

### Permission errors

```bash
# Fix permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 /app/storage /app/bootstrap/cache

# Or use Makefile
make -f Makefile.prod permissions
```

### FrankenPHP worker restarting

```bash
# Check Laravel logs
docker-compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log

# Check for PHP errors
docker-compose -f docker-compose.prod.yml logs app | grep -i error

# Reduce worker count (edit docker/frankenphp/Caddyfile.prod)
# Change: num 2 → num 1
```

### Application slow or not responding

```bash
# Check container resources
docker stats

# Check Redis
docker-compose -f docker-compose.prod.yml exec redis redis-cli ping

# Clear all caches
make -f Makefile.prod clear-cache

# Rebuild optimizations
make -f Makefile.prod optimize
```

---

## 📊 Monitoring

### Health Check

```bash
# Quick health check
curl http://localhost:8282/

# Using Makefile
make -f Makefile.prod health

# Container status
docker-compose -f docker-compose.prod.yml ps

# Resource usage
docker stats --no-stream
```

### Logs

```bash
# Application logs
docker-compose -f docker-compose.prod.yml logs -f app

# Laravel logs
docker-compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log

# Caddy logs
docker-compose -f docker-compose.prod.yml exec app tail -f /var/log/caddy/access.log

# All logs
docker-compose -f docker-compose.prod.yml logs -f
```

### Performance

```bash
# Container stats
docker stats

# Redis info
docker-compose -f docker-compose.prod.yml exec redis redis-cli INFO

# Database status
docker-compose -f docker-compose.prod.yml exec db mysql -u root -p -e "SHOW PROCESSLIST;"
```

---

## 🔐 Security Checklist

Before going to production:

- [ ] `APP_DEBUG=false` in `.env.production`
- [ ] `APP_ENV=production` in `.env.production`
- [ ] Strong database passwords set
- [ ] `APP_KEY` generated (unique)
- [ ] `.env.production` not committed to Git
- [ ] File permissions correct (775 for storage)
- [ ] HTTPS configured (via reverse proxy)
- [ ] Firewall configured
- [ ] Only necessary ports open (80, 443)
- [ ] Backup strategy in place
- [ ] Monitoring tools configured

---

## 📝 Differences: Development vs Production

| Feature | Development | Production |
|---------|-------------|------------|
| **File** | `docker-compose.yml` | `docker-compose.prod.yml` |
| **Command** | `docker-compose up` | `docker-compose -f docker-compose.prod.yml up` |
| **Port** | 8282 | 8282 (or 80/443 with proxy) |
| **Debug** | ON | OFF |
| **Cache** | File | Redis |
| **Session** | File | Redis |
| **Logs** | Debug | Error only |
| **Assets** | Live | Pre-built |
| **Volumes** | Full `/app` | Storage only |
| **PHPMyAdmin** | Yes (port 8281) | No |

---

## 🎯 Pre-Production Checklist

Review [PRODUCTION_DEPLOYMENT_CHECKLIST.md](PRODUCTION_DEPLOYMENT_CHECKLIST.md) untuk checklist lengkap.

**Quick checklist:**

1. ✅ `.env.production` configured
2. ✅ Database credentials set
3. ✅ `APP_KEY` generated
4. ✅ Test in local first
5. ✅ Backup plan ready
6. ✅ Monitoring configured
7. ✅ SSL certificate ready
8. ✅ Domain DNS configured

---

## 🆘 Emergency Procedures

### Application Down

```bash
# Check status
docker-compose -f docker-compose.prod.yml ps

# Restart quickly
docker-compose -f docker-compose.prod.yml restart app

# Or full restart
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d

# Emergency shutdown
make -f Makefile.prod emergency-down
```

### Rollback to Previous Version

```bash
# Stop services
docker-compose -f docker-compose.prod.yml down

# Rollback code
git log --oneline  # Find previous commit
git reset --hard <commit-hash>

# Redeploy
./deploy.sh

# Restore database if needed
docker-compose -f docker-compose.prod.yml exec -T db mysql -u root -p adojobs < backup-YYYYMMDD.sql
```

---

## 📞 Getting Help

1. **Check logs first:**
   ```bash
   make -f Makefile.prod logs
   ```

2. **Review documentation:**
   - [PRODUCTION_DEPLOYMENT_CHECKLIST.md](PRODUCTION_DEPLOYMENT_CHECKLIST.md)
   - [PRODUCTION_READY_REVIEW.md](PRODUCTION_READY_REVIEW.md)

3. **Common issues:**
   - See Troubleshooting section above
   - Check GitHub Issues

4. **Contact:**
   - Create GitHub issue
   - Check project documentation

---

## 📚 Additional Resources

- [Laravel Deployment Docs](https://laravel.com/docs/deployment)
- [FrankenPHP Documentation](https://frankenphp.dev)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [Caddy Documentation](https://caddyserver.com/docs/)

---

**Last Updated:** November 4, 2025  
**Version:** 1.0


