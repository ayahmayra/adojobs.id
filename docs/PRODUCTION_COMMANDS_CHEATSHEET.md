# Production Commands Cheatsheet

## ⚡ Quick Reference untuk AdoJobs.id Production

---

## 🚀 Deployment Commands

### **Pull & Update:**
```bash
cd /var/www/adojobs.id
sudo git checkout -- docker/frankenphp/Caddyfile  # Discard local changes
sudo git pull origin main
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml up -d
```

### **Run Migrations:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

### **Clear Cache:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

---

## 🔍 Monitoring Commands

### **Check Status:**
```bash
docker-compose -f docker-compose.prod.yml ps
docker stats
```

### **View Logs:**
```bash
# All logs
docker-compose -f docker-compose.prod.yml logs -f

# App only
docker-compose -f docker-compose.prod.yml logs -f app --tail=50

# Check for errors
docker-compose -f docker-compose.prod.yml logs app | grep -i error
```

### **Health Check:**
```bash
curl -I http://localhost:8282
curl -I https://adojobs.id
```

---

## 🗄️ Database Commands

### **Backup:**
```bash
docker-compose -f docker-compose.prod.yml exec db \
    mysqldump -u adojobs_user -p adojobs_production > backup_$(date +%Y%m%d).sql
```

### **Restore:**
```bash
docker-compose -f docker-compose.prod.yml exec -T db \
    mysql -u adojobs_user -p adojobs_production < backup_20251021.sql
```

### **Check Data:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::count();
>>> \App\Models\Job::count();
>>> exit
```

---

## 🔧 Troubleshooting Commands

### **502 Bad Gateway:**
```bash
# Check app logs
docker-compose -f docker-compose.prod.yml logs app

# Restart app
docker-compose -f docker-compose.prod.yml restart app

# Full rebuild
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d
```

### **Container Not Starting:**
```bash
# Check logs
docker-compose -f docker-compose.prod.yml logs app

# Check resources
docker stats

# Remove and recreate
docker-compose -f docker-compose.prod.yml down -v
docker-compose -f docker-compose.prod.yml up -d
```

---

## 👤 User Management

### **Create Admin:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@adojobs.id', 'password' => bcrypt('Password123!'), 'role' => 'admin', 'email_verified_at' => now()]);
>>> exit
```

### **Reset Password:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> $user = \App\Models\User::where('email', 'admin@adojobs.id')->first();
>>> $user->password = bcrypt('NewPassword123!');
>>> $user->save();
>>> exit
```

---

## 🔄 Restart Commands

### **Restart App Only:**
```bash
docker-compose -f docker-compose.prod.yml restart app
```

### **Restart All:**
```bash
docker-compose -f docker-compose.prod.yml restart
```

### **Restart Queue Workers:**
```bash
sudo systemctl restart adojobs-queue
```

---

## 📊 Useful Queries

### **Check Tables:**
```bash
docker-compose -f docker-compose.prod.yml exec db \
    mysql -u adojobs_user -p adojobs_production -e "SHOW TABLES;"
```

### **Check Table Structure:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> Schema::getColumnListing('users');
>>> Schema::hasColumn('users', 'phone');
>>> exit
```

### **Count Records:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> echo "Users: " . \App\Models\User::count() . "\n";
>>> echo "Jobs: " . \App\Models\Job::count() . "\n";
>>> echo "Categories: " . \App\Models\Category::count() . "\n";
>>> exit
```

---

## 🎯 One-Line Commands

```bash
# Pull & Deploy
cd /var/www/adojobs.id && sudo git pull origin main && docker-compose -f docker-compose.prod.yml down && docker-compose -f docker-compose.prod.yml up -d

# Migrate & Cache
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force && docker-compose -f docker-compose.prod.yml exec app php artisan config:cache

# Full Update
cd /var/www/adojobs.id && sudo git pull origin main && docker-compose -f docker-compose.prod.yml down && docker-compose -f docker-compose.prod.yml build --no-cache && docker-compose -f docker-compose.prod.yml up -d && docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force && docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
```

---

## 📋 Complete Fix Workflow (Current Issue)

```bash
# At production server:
cd /var/www/adojobs.id

# 1. Fix Caddyfile conflict
sudo git checkout -- docker/frankenphp/Caddyfile

# 2. Pull latest (includes worker fix)
sudo git pull origin main

# 3. Rebuild & Start
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml up -d

# 4. Wait for healthy (30-60 seconds)
sleep 30

# 5. Check logs (should see "FrankenPHP started 🐘")
docker-compose -f docker-compose.prod.yml logs app | grep "FrankenPHP started"

# 6. Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 7. Create admin (if first time)
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin AdoJobs', 'email' => 'admin@adojobs.id', 'password' => bcrypt('SecurePass123!'), 'role' => 'admin', 'email_verified_at' => now()]);
>>> exit

# 8. Run seeders
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force

# 9. Optimize
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# 10. Test
curl -I http://localhost:8282  # Should return HTTP/1.1 200
curl -I https://adojobs.id      # Should return HTTP/2 200

# Done! ✅
```

---

## ✅ Verification Checklist

After deployment, verify:
- [ ] `docker-compose ps` shows all containers "Up"
- [ ] No error in logs: `docker-compose logs app | grep -i error`
- [ ] FrankenPHP started: `docker-compose logs app | grep "FrankenPHP started"`
- [ ] Internal access: `curl http://localhost:8282` returns 200
- [ ] External access: `curl https://adojobs.id` returns 200
- [ ] Admin can login: https://adojobs.id/admin/dashboard
- [ ] Database seeded: Check jobs, users, categories exist

---

**Quick Reference**: Copy & paste commands as needed! 📝✨
