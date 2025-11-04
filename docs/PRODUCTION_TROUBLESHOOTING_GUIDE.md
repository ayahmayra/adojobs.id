# Production Troubleshooting Guide

## Overview
Dokumentasi ini berisi solusi untuk masalah-masalah yang sering terjadi saat deployment AdoJobs.id di production server.

## Common Issues & Solutions

### 1. Port Conflicts

#### Problem
```
Error response from daemon: failed to set up container networking: driver failed programming external connectivity on endpoint jobmaker_redis (6cc390334d5e860c24bacb41bc12e4aea57a8e3398cc5fb1eac08fb575a1f53a): Bind for 0.0.0.0:6379 failed: port is already allocated
```

#### Root Cause
Port 6379 (Redis) dan 3306 (MariaDB) sudah digunakan oleh aplikasi lain di server production.

#### Solution
1. **Ubah port di docker-compose.yml:**
   ```yaml
   # Redis
   ports:
     - "6380:6379"
   
   # MariaDB  
   ports:
     - "3307:3306"
   ```

2. **Commit dan push perubahan:**
   ```bash
   git add docker-compose.yml
   git commit -m "Fix port conflicts - Redis: 6380, MariaDB: 3307"
   git push origin main
   ```

3. **Rebuild di production:**
   ```bash
   git pull origin main
   docker-compose down
   docker-compose up --build -d
   ```

### 2. Database Connection Issues

#### Problem
```
SQLSTATE[HY000] [1045] Access denied for user 'jobmaker'@'172.26.0.5' (using password: YES)
```

#### Root Cause
Environment variables di production (.env) tidak match dengan docker-compose.yml:
- **Production .env:** `adojobsdb2025`, `adojobsdbuser2025`, `j7HvygVlQVTbpIZn`
- **Docker-compose.yml:** `jobmaker`, `jobmaker`, `secret`

#### Solution
1. **Sync .env dengan docker-compose.yml:**
   ```bash
   docker-compose exec app sed -i 's/DB_DATABASE=adojobsdb2025/DB_DATABASE=jobmaker/' .env
   docker-compose exec app sed -i 's/DB_USERNAME=adojobsdbuser2025/DB_USERNAME=jobmaker/' .env
   docker-compose exec app sed -i 's/DB_PASSWORD=j7HvygVlQVTbpIZn/DB_PASSWORD=secret/' .env
   ```

2. **Clear config cache:**
   ```bash
   docker-compose exec app php artisan config:clear
   ```

### 3. Config Cache Issues

#### Problem
```
production.ERROR: NOAUTH Authentication required. {"exception":"[object] (RedisException(code: 0): NOAUTH Authentication required.
```

#### Root Cause
Config cache masih menyimpan setting lama (Redis) meskipun sudah ubah ke file driver.

#### Solution
1. **Hapus semua config cache:**
   ```bash
   docker-compose exec app sh -c "rm -rf bootstrap/cache/*.php"
   docker-compose exec app sh -c "rm -rf storage/framework/cache/data/*"
   ```

2. **Clear semua Laravel cache:**
   ```bash
   docker-compose exec app php artisan cache:clear
   docker-compose exec app php artisan config:clear
   docker-compose exec app php artisan view:clear
   docker-compose exec app php artisan route:clear
   ```

3. **Restart container:**
   ```bash
   docker-compose restart app
   ```

### 4. Database Volume Corruption

#### Problem
```
ERROR 1045 (28000): Access denied for user 'root'@'localhost' (using password: YES)
```

#### Root Cause
Database volume corrupt dari previous deployment atau environment variables salah.

#### Solution
1. **Stop semua container:**
   ```bash
   docker-compose down
   ```

2. **Hapus volume database:**
   ```bash
   docker volume rm adojobsid_mariadb_data
   ```

3. **Rebuild container:**
   ```bash
   docker-compose up --build -d
   ```

4. **Tunggu container ready:**
   ```bash
   sleep 30
   ```

5. **Test koneksi:**
   ```bash
   docker-compose exec app php artisan migrate:status
   ```

### 5. Application Logic Errors

#### Problem
```
DivisionByZeroError - Internal Server Error
Division by zero
```

#### Root Cause
Empty database menyebabkan `Application::count()` = 0, leading to division by zero.

#### Solution
1. **Fix DashboardController.php:**
   ```php
   // Before (line 56)
   'application_rate' => $totalJobs > 0 ? round((Application::where('status', 'hired')->count() / Application::count()) * 100, 1) : 0,
   
   // After
   'application_rate' => $totalJobs > 0 && Application::count() > 0 ? round((Application::where('status', 'hired')->count() / Application::count()) * 100, 1) : 0,
   ```

2. **Commit dan push fix:**
   ```bash
   git add src/app/Http/Controllers/Admin/DashboardController.php
   git commit -m "Fix division by zero error in admin dashboard"
   git push origin main
   ```

3. **Deploy di production:**
   ```bash
   git pull origin main
   docker-compose restart app
   ```

## Quick Fix Commands

### Reset Everything
```bash
# Stop containers
docker-compose down

# Remove volumes
docker volume rm adojobsid_mariadb_data
docker volume rm adojobsid_redis_data

# Pull latest code
git pull origin main

# Rebuild everything
docker-compose up --build -d

# Wait for containers
sleep 30

# Run migrations
docker-compose exec app php artisan migrate --force

# Run seeders
docker-compose exec app php artisan db:seed --class=LocalDataSeeder --force

# Create admin user
docker-compose exec app php artisan tinker --execute="
\$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@adojobs.id',
    'password' => bcrypt('admin123'),
    'role' => 'admin',
    'email_verified_at' => now()
]);
echo 'Admin user created with ID: ' . \$user->id;
"

# Test application
curl -I http://localhost:8282
```

### Check Container Status
```bash
# Check all containers
docker-compose ps

# Check logs
docker-compose logs app
docker-compose logs db
docker-compose logs redis

# Check database connection
docker-compose exec app php artisan migrate:status

# Check environment
docker-compose exec app sh -c "grep -E 'DB_|CACHE_|SESSION_' .env"
```

## Prevention Tips

1. **Always check port conflicts** before deployment
2. **Keep .env consistent** with docker-compose.yml
3. **Clear all caches** after environment changes
4. **Use defensive programming** to avoid division by zero
5. **Test database connection** before running migrations
6. **Monitor container logs** for early error detection

## Emergency Contacts

- **Server Admin:** sysadmin@trustserver
- **Application:** https://adojobs.id
- **GitHub:** https://github.com/ayahmayra/adojobs.id

---

**Last Updated:** October 21, 2025  
**Version:** 1.0  
**Author:** AdoJobs.id Development Team
