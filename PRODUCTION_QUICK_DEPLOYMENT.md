# Quick Production Deployment - AdoJobs.id

## 🚀 Deployment ke Server Production

### **Quick Reference untuk Deploy AdoJobs.id**

---

## ⚡ Quick Deployment Steps

### **1. Pull Latest Code**
```bash
cd /var/www/adojobs.id
sudo git pull origin main
```

**Jika ada local changes conflict:**
```bash
# Stash local changes
sudo git stash

# Pull
sudo git pull origin main

# Apply stash (if needed)
sudo git stash pop

# Or discard local changes
sudo git reset --hard origin/main
```

---

### **2. Run Migrations**
```bash
# Run all pending migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Verify migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

**⚠️ Important**: Migration `2025_10_21_060604_add_phone_address_to_users_table.php` 
akan menambahkan kolom `phone` dan `address` jika belum ada.

---

### **3. Run Seeders (First Time Only)**
```bash
# Check if admin exists
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::where('role', 'admin')->count();
>>> exit

# If no admin, create one:
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin AdoJobs', 'email' => 'admin@adojobs.id', 'password' => bcrypt('YourSecurePassword123!'), 'role' => 'admin', 'email_verified_at' => now()]);
>>> exit

# Run local data seeders
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force
```

---

### **4. Clear & Rebuild Cache**
```bash
# Clear all cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:clear
docker-compose -f docker-compose.prod.yml exec app php artisan route:clear
docker-compose -f docker-compose.prod.yml exec app php artisan view:clear

# Rebuild cache
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

---

### **5. Restart Services**
```bash
# Restart app container
docker-compose -f docker-compose.prod.yml restart app

# Or rebuild if Dockerfile changed
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d
```

---

## 🔍 Verification

### **Check Application:**
```bash
# Test internal
curl -I http://localhost:8282

# Should return: HTTP/1.1 200 OK
```

### **Check via Domain:**
```bash
# Test external (via Nginx Proxy Manager)
curl -I https://adojobs.id

# Should return: HTTP/2 200
```

### **Check Logs:**
```bash
# Application logs
docker-compose -f docker-compose.prod.yml logs -f app --tail=50

# Check for errors
docker-compose -f docker-compose.prod.yml logs app | grep -i error
```

---

## 🎯 Complete Fresh Deployment

**For first time deployment on new server:**

```bash
# 1. Clone
cd /var/www
sudo git clone https://github.com/ayahmayra/adojobs.id.git
cd adojobs.id

# 2. Configure
cp src/.env.example src/.env
nano src/.env  # Set production values

# Create docker .env
nano .env
# Add:
# DB_PASSWORD=your_secure_password
# DB_ROOT_PASSWORD=your_secure_root_password
# REDIS_PASSWORD=your_redis_password

# 3. Build & Start
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# Wait for containers to be healthy (30-60 seconds)
docker-compose -f docker-compose.prod.yml ps

# 4. Install Dependencies
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# 5. Generate Key
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate --force

# 6. Link Storage
docker-compose -f docker-compose.prod.yml exec app php artisan storage:link

# 7. Run Migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --force

# 8. Create Admin
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin AdoJobs', 'email' => 'admin@adojobs.id', 'password' => bcrypt('YourSecurePassword123!'), 'role' => 'admin', 'email_verified_at' => now()]);
>>> exit

# 9. Seed Data
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force

# 10. Optimize
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# 11. Configure Nginx Proxy Manager
# Go to: http://your-server-ip:81
# Add proxy host: adojobs.id → adojobs_app:8080
# Request SSL certificate

# 12. Test
curl -I https://adojobs.id
```

---

## 🔄 Update Deployment

**For updating existing production:**

```bash
cd /var/www/adojobs.id

# 1. Backup Database
docker-compose -f docker-compose.prod.yml exec db \
    mysqldump -u adojobs_user -p adojobs_production > backup_$(date +%Y%m%d).sql

# 2. Pull Changes
sudo git pull origin main

# 3. Update Dependencies (if composer.json changed)
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# 4. Run Migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 5. Clear & Rebuild Cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# 6. Restart
docker-compose -f docker-compose.prod.yml restart app

# 7. Restart Queue Workers
sudo systemctl restart adojobs-queue

# 8. Test
curl -I https://adojobs.id
```

---

## 🆘 Common Issues & Solutions

### **Issue: Column not found 'phone' or 'address'**
```bash
# Solution: Run migrations first
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Then run seeders
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force
```

### **Issue: Local changes conflict on git pull**
```bash
# Solution 1: Stash changes
sudo git stash
sudo git pull origin main

# Solution 2: Force pull (discard local changes)
sudo git fetch origin
sudo git reset --hard origin/main
```

### **Issue: Container not starting**
```bash
# Check logs
docker-compose -f docker-compose.prod.yml logs app

# Rebuild
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d
```

### **Issue: 502 Bad Gateway**
```bash
# Check if app is running
docker-compose -f docker-compose.prod.yml ps

# Check app logs
docker-compose -f docker-compose.prod.yml logs app

# Restart app
docker-compose -f docker-compose.prod.yml restart app
```

---

## 📊 Monitoring Commands

### **Check Container Status:**
```bash
docker-compose -f docker-compose.prod.yml ps
docker stats
```

### **View Logs:**
```bash
# All logs
docker-compose -f docker-compose.prod.yml logs -f

# Specific container
docker-compose -f docker-compose.prod.yml logs -f app
docker-compose -f docker-compose.prod.yml logs -f db

# Last 50 lines
docker-compose -f docker-compose.prod.yml logs --tail=50 app
```

### **Check Database:**
```bash
# Connection test
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> DB::connection()->getPdo();
>>> DB::table('users')->count();

# Check migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

---

## 🔐 Security Reminders

### **Before First Deployment:**
- ✅ Change all default passwords in .env
- ✅ Set APP_DEBUG=false
- ✅ Set APP_ENV=production
- ✅ Use strong database passwords
- ✅ Set Redis password
- ✅ Configure mail settings

### **After Deployment:**
- ✅ Change admin password via UI
- ✅ Test SSL certificate (https)
- ✅ Verify firewall rules
- ✅ Setup automated backups
- ✅ Configure monitoring

---

## 📦 Important Files

### **Configuration:**
- `src/.env` - Application environment
- `.env` - Docker environment (DB passwords)
- `docker-compose.prod.yml` - Production compose file

### **Migrations:**
- `src/database/migrations/` - All migrations
- **Important**: `2025_10_21_060604_add_phone_address_to_users_table.php`

### **Seeders:**
- `LocalCategorySeeder.php` - Categories (12 items)
- `LocalSeekerSeeder.php` - Seekers (10 items)
- `LocalEmployerSeeder.php` - Employers (10 items)
- `LocalJobSeeder.php` - Jobs (12 items)
- `LocalArticleSeeder.php` - Articles (5 items)

---

## 🎯 Nginx Proxy Manager Setup

### **Access:**
```
URL: http://your-server-ip:81
Login: admin@example.com / changeme
```

### **Add Proxy Host:**
1. **Hosts** → **Proxy Hosts** → **Add Proxy Host**
2. **Details Tab:**
   - Domain Names: `adojobs.id`, `www.adojobs.id`
   - Scheme: `http`
   - Forward Hostname/IP: `adojobs_app`
   - Forward Port: `8080`
   - ✓ Cache Assets
   - ✓ Block Common Exploits
   - ✓ Websockets Support

3. **SSL Tab:**
   - ✓ Request a new SSL Certificate
   - ✓ Force SSL
   - ✓ HTTP/2 Support
   - ✓ HSTS Enabled
   - Email: your-email@example.com
   - ✓ I Agree to the Let's Encrypt Terms

4. **Save**

---

## ✅ Deployment Checklist

### **Pre-Deployment:**
- [ ] Backup database
- [ ] Pull latest code
- [ ] Review changes (git log)
- [ ] Check .env configuration

### **Deployment:**
- [ ] Update dependencies
- [ ] Run migrations
- [ ] Run seeders (if first time)
- [ ] Clear & rebuild cache
- [ ] Restart containers
- [ ] Restart queue workers

### **Post-Deployment:**
- [ ] Test application access
- [ ] Test HTTPS/SSL
- [ ] Test login functionality
- [ ] Check for errors in logs
- [ ] Verify database integrity
- [ ] Monitor performance

---

## 📚 Related Documentation

- **[PRODUCTION_DEPLOYMENT_GUIDE.md](PRODUCTION_DEPLOYMENT_GUIDE.md)** - Complete deployment guide
- **[PRODUCTION_MIGRATION_GUIDE.md](PRODUCTION_MIGRATION_GUIDE.md)** - Migration troubleshooting
- **[DOCKER_COMMANDS.md](DOCKER_COMMANDS.md)** - Docker commands reference
- **[README.md](README.md)** - Project overview

---

## 🎯 Quick Commands Reference

```bash
# Pull & Update
git pull origin main
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml restart app

# Backup Database
docker-compose -f docker-compose.prod.yml exec db mysqldump -u adojobs_user -p adojobs_production > backup_$(date +%Y%m%d).sql

# View Logs
docker-compose -f docker-compose.prod.yml logs -f app

# Restart All
docker-compose -f docker-compose.prod.yml restart

# Check Status
docker-compose -f docker-compose.prod.yml ps
```

---

## 🌐 Access Points

### **Production URLs:**
```
Website: https://adojobs.id
Admin: https://adojobs.id/admin/dashboard
Employer: https://adojobs.id/employer/dashboard
Seeker: https://adojobs.id/seeker/dashboard
```

### **Internal URLs (for debugging):**
```
Application: http://localhost:8282
PHPMyAdmin: http://localhost:8281 (if enabled)
Nginx Proxy Manager: http://your-server-ip:81
```

---

## ✅ Result

**Quick Deployment Guide**: ✅ **Ready**  
**Git Repository**: ✅ **Pushed to GitHub**  
**Production Ready**: ✅ **Yes**  

**Gunakan panduan ini untuk deployment cepat ke production!** 🚀✨

---

**Updated**: October 21, 2025  
**Repository**: https://github.com/ayahmayra/adojobs.id  
**Status**: ✅ Production Ready
