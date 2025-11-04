# Production Deployment Checklist

## 📋 Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Copy `.env.production.example` to `.env.production`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate and set secure `APP_KEY`
- [ ] Set correct `APP_URL` (production domain)
- [ ] Configure database credentials
- [ ] Set strong database passwords
- [ ] Configure Redis settings
- [ ] Configure mail settings
- [ ] Set secure session/cache drivers

### 2. Database Preparation
- [ ] Database server is running and accessible
- [ ] Database credentials are correct
- [ ] Database user has proper permissions
- [ ] Database backup strategy in place
- [ ] Character set is UTF8MB4

### 3. Code Preparation
- [ ] All code is committed to Git
- [ ] All tests are passing
- [ ] No debug code or var_dumps remain
- [ ] Remove all console.log statements
- [ ] Update version numbers if applicable

### 4. Docker Configuration
- [ ] `docker-compose.prod.yml` is configured
- [ ] Dockerfile production stage is optimized
- [ ] Health checks are configured
- [ ] Resource limits are set appropriately
- [ ] Volumes are properly configured
- [ ] Network configuration is correct

### 5. Security
- [ ] All passwords are strong and unique
- [ ] `APP_DEBUG` is set to `false`
- [ ] Error logging is configured (not displayed)
- [ ] HTTPS is configured (if using reverse proxy)
- [ ] File permissions are correct
- [ ] .env files are not in version control
- [ ] Security headers are configured in Caddyfile

### 6. Laravel Optimization
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `php artisan optimize`
- [ ] OPcache is enabled
- [ ] Storage is properly linked

### 7. File System
- [ ] Storage directories are writable
- [ ] Upload size limits are configured
- [ ] Disk space is sufficient
- [ ] Backup strategy for uploads

### 8. Monitoring & Logging
- [ ] Log rotation is configured
- [ ] Error monitoring is set up
- [ ] Performance monitoring is configured
- [ ] Health check endpoints are working

---

## 🚀 Deployment Steps

### First-Time Deployment

1. **Clone Repository**
   ```bash
   git clone https://github.com/ayahmayra/adojobs.id.git
   cd adojobs.id
   ```

2. **Configure Environment**
   ```bash
   cp .env.production.example .env.production
   nano .env.production  # Edit with production values
   ```

3. **Make Deploy Script Executable**
   ```bash
   chmod +x deploy.sh
   ```

4. **Run Deployment**
   ```bash
   ./deploy.sh
   ```

5. **Verify Deployment**
   ```bash
   docker-compose -f docker-compose.prod.yml ps
   docker-compose -f docker-compose.prod.yml logs -f app
   ```

6. **Run Seeders (First Time Only)**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan db:seed
   ```

### Subsequent Deployments

1. **Pull Latest Code**
   ```bash
   git pull origin main
   ```

2. **Run Deployment Script**
   ```bash
   ./deploy.sh
   ```

3. **Verify**
   ```bash
   docker-compose -f docker-compose.prod.yml ps
   curl http://localhost:8282
   ```

---

## 🔍 Post-Deployment Verification

### Application Health
- [ ] Application homepage loads
- [ ] User registration works
- [ ] User login works
- [ ] Job listings display
- [ ] Search functionality works
- [ ] File uploads work
- [ ] Email notifications work (if configured)
- [ ] No JavaScript errors in console

### Database
- [ ] All migrations ran successfully
- [ ] Database connections are working
- [ ] Seeded data is present (if applicable)

### Performance
- [ ] Page load times are acceptable
- [ ] No N+1 query issues
- [ ] Caching is working
- [ ] Static assets load quickly

### Security
- [ ] Debug mode is OFF
- [ ] No sensitive data exposed
- [ ] CSRF protection is working
- [ ] XSS protection headers present

### Logs
- [ ] No critical errors in logs
- [ ] Application logs are being written
- [ ] Log rotation is working

---

## 🛠️ Common Post-Deployment Tasks

### Clear All Caches
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec app php artisan view:clear
```

### Rebuild Optimizations
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
```

### Run Migrations
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### Check Application Status
```bash
docker-compose -f docker-compose.prod.yml ps
docker-compose -f docker-compose.prod.yml logs -f app
```

### Restart Services
```bash
docker-compose -f docker-compose.prod.yml restart app
docker-compose -f docker-compose.prod.yml restart db
docker-compose -f docker-compose.prod.yml restart redis
```

### Access Container Shell
```bash
docker-compose -f docker-compose.prod.yml exec app bash
```

### View Logs
```bash
# All services
docker-compose -f docker-compose.prod.yml logs -f

# Specific service
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f db

# Laravel logs
docker-compose -f docker-compose.prod.yml exec app tail -f storage/logs/laravel.log
```

---

## 🚨 Troubleshooting

### Container Won't Start
1. Check logs: `docker-compose -f docker-compose.prod.yml logs app`
2. Verify .env.production configuration
3. Check disk space: `df -h`
4. Verify ports aren't in use: `netstat -tlnp | grep 8282`

### Database Connection Errors
1. Verify database container is running
2. Check database credentials in .env.production
3. Test connection: `docker-compose -f docker-compose.prod.yml exec app php artisan tinker`
4. Check database logs: `docker-compose -f docker-compose.prod.yml logs db`

### Permission Errors
```bash
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /app/storage
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 /app/storage
```

### FrankenPHP Worker Restarting
1. Check for syntax errors in code
2. Review Laravel logs
3. Check memory limits
4. Reduce worker count in Caddyfile if needed

### Can't Access Application
1. Check if container is running
2. Check if port is open
3. Check firewall settings
4. Check reverse proxy configuration (if applicable)

---

## 📊 Monitoring

### Health Check Endpoint
```bash
curl http://localhost:8282/
```

### Database Status
```bash
docker-compose -f docker-compose.prod.yml exec db mysql -u root -p -e "SHOW PROCESSLIST;"
```

### Redis Status
```bash
docker-compose -f docker-compose.prod.yml exec redis redis-cli INFO
```

### Container Resource Usage
```bash
docker stats
```

---

## 🔄 Backup Strategy

### Database Backup
```bash
docker-compose -f docker-compose.prod.yml exec db mysqldump -u root -p adojobs > backup-$(date +%Y%m%d).sql
```

### Storage Backup
```bash
tar -czf storage-backup-$(date +%Y%m%d).tar.gz src/storage/app/
```

### Full System Backup
```bash
# Stop containers
docker-compose -f docker-compose.prod.yml down

# Backup volumes
docker run --rm -v adojobs_mariadb_data:/data -v $(pwd):/backup alpine tar czf /backup/mariadb-backup-$(date +%Y%m%d).tar.gz /data

# Restart containers
docker-compose -f docker-compose.prod.yml up -d
```

---

## 📝 Important Notes

1. **Always test in staging first** before deploying to production
2. **Keep backups** before major updates
3. **Monitor logs** after deployment
4. **Document any manual changes** made after deployment
5. **Use version tags** in Git for releases
6. **Have a rollback plan** ready

---

## 🆘 Emergency Rollback

If deployment fails and you need to rollback:

```bash
# Stop current containers
docker-compose -f docker-compose.prod.yml down

# Checkout previous version
git reset --hard <previous-commit-hash>

# Redeploy
./deploy.sh

# Restore database from backup if needed
docker-compose -f docker-compose.prod.yml exec db mysql -u root -p adojobs < backup-YYYYMMDD.sql
```

---

## 📞 Support

For issues or questions:
- Check logs first
- Review this checklist
- Consult documentation in `/docs` folder
- Check GitHub issues


