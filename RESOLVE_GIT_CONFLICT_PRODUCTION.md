# Mengatasi Git Conflict di Production

## 📋 Problem
```bash
error: Your local changes to the following files would be overwritten by merge:
        docker/frankenphp/Caddyfile
Please commit your changes or stash them before you merge.
```

---

## 🎯 Solutions (Pilih salah satu)

### **Option 1: Stash Local Changes (Recommended)**

Simpan perubahan lokal sementara, pull, lalu apply kembali:

```bash
# 1. Simpan perubahan lokal
sudo git stash save "Local Caddyfile changes on production"

# 2. Pull dari GitHub
sudo git pull origin main

# 3. Lihat perubahan yang di-stash
sudo git stash list

# 4a. Apply stash (if you want to keep local changes)
sudo git stash pop

# 4b. Or discard stash (if you want to use GitHub version)
sudo git stash drop
```

---

### **Option 2: Commit Local Changes First**

Commit perubahan lokal dulu, baru pull:

```bash
# 1. Add file yang berubah
sudo git add docker/frankenphp/Caddyfile

# 2. Commit dengan message
sudo git commit -m "chore: Local Caddyfile modifications on production"

# 3. Pull (might need to merge)
sudo git pull origin main

# 4. If conflict, resolve manually
sudo nano docker/frankenphp/Caddyfile

# 5. Add and commit merge
sudo git add docker/frankenphp/Caddyfile
sudo git commit -m "merge: Resolve Caddyfile conflict"

# 6. Push back to GitHub (optional)
sudo git push origin main
```

---

### **Option 3: Discard Local Changes (Use GitHub Version)**

Buang perubahan lokal dan gunakan versi dari GitHub:

```bash
# ⚠️ WARNING: This will discard ALL local changes

# 1. Check what will be discarded
sudo git diff docker/frankenphp/Caddyfile

# 2. Discard local changes
sudo git checkout -- docker/frankenphp/Caddyfile

# 3. Pull from GitHub
sudo git pull origin main
```

---

### **Option 4: Force Reset (Nuclear Option)**

Reset hard ke versi GitHub (buang SEMUA local changes):

```bash
# ⚠️ WARNING: This will discard ALL local changes in the entire repository

# 1. Fetch latest
sudo git fetch origin

# 2. Hard reset to origin/main
sudo git reset --hard origin/main

# Done! Your code is now exactly like GitHub
```

---

## 🔍 Recommended Approach

**Untuk Production**, saya rekomendasikan **Option 1 (Stash)** karena:
- ✅ Safe (tidak buang perubahan)
- ✅ Bisa review perubahan lokal
- ✅ Bisa pilih mana yang mau di-keep
- ✅ Bisa rollback jika perlu

---

## 📝 Step-by-Step (Recommended)

### **1. Lihat perubahan lokal dulu:**
```bash
cd /var/www/adojobs.id

# Lihat apa yang berubah
sudo git diff docker/frankenphp/Caddyfile
```

### **2. Stash perubahan lokal:**
```bash
# Simpan perubahan
sudo git stash save "Production Caddyfile modifications"

# Konfirmasi stash berhasil
sudo git stash list
# Output: stash@{0}: On main: Production Caddyfile modifications
```

### **3. Pull dari GitHub:**
```bash
# Sekarang pull akan berjalan lancar
sudo git pull origin main
```

### **4. Review stashed changes:**
```bash
# Lihat isi stash
sudo git stash show -p stash@{0}

# Jika perubahan lokal penting, apply kembali
sudo git stash pop

# Jika tidak perlu, buang stash
sudo git stash drop
```

### **5. If conflict after stash pop:**
```bash
# Edit file manually
sudo nano docker/frankenphp/Caddyfile

# Pilih versi yang mau dipakai:
# - Perubahan lokal (production)
# - Perubahan dari GitHub
# - Atau kombinasi keduanya

# Setelah edit, add dan commit
sudo git add docker/frankenphp/Caddyfile
sudo git commit -m "merge: Resolve Caddyfile conflict"
```

---

## 🔧 Compare Local vs GitHub Version

### **Check Local Version:**
```bash
cd /var/www/adojobs.id

# View local Caddyfile
cat docker/frankenphp/Caddyfile
```

### **Check GitHub Version:**
```bash
# Fetch latest
sudo git fetch origin

# View GitHub version (without pulling)
sudo git show origin/main:docker/frankenphp/Caddyfile
```

### **See Differences:**
```bash
# Compare local vs GitHub
sudo git diff origin/main:docker/frankenphp/Caddyfile docker/frankenphp/Caddyfile
```

---

## 🎯 After Resolving Conflict

### **1. Rebuild Container (if Caddyfile changed):**
```bash
# Stop containers
docker-compose -f docker-compose.prod.yml down

# Rebuild app (picks up new Caddyfile)
docker-compose -f docker-compose.prod.yml build --no-cache app

# Start
docker-compose -f docker-compose.prod.yml up -d

# Check logs
docker-compose -f docker-compose.prod.yml logs -f app
```

### **2. Verify Application:**
```bash
# Test application
curl -I http://localhost:8282

# Test via domain
curl -I https://adojobs.id
```

---

## 📊 Expected Caddyfile Content (from GitHub)

```caddyfile
{
    frankenphp {
        num_threads 4
        worker /app/public/index.php
    }
    order php_server before file_server
}

:8080 {
    root * /app/public
    encode gzip zstd
    
    # PHP FrankenPHP with worker mode
    php_server
    
    # Security headers
    header {
        -Server
        X-Frame-Options "SAMEORIGIN"
        X-Content-Type-Options "nosniff"
        X-XSS-Protection "1; mode=block"
        Referrer-Policy "no-referrer-when-downgrade"
    }
    
    # Static file caching
    @static {
        file
        path *.ico *.css *.js *.gif *.jpg *.jpeg *.png *.svg *.woff *.woff2 *.ttf *.eot
    }
    header @static Cache-Control "public, max-age=31536000, immutable"
    
    # Logging
    log {
        output file /var/log/caddy/access.log
        level INFO
    }
}
```

**Key Features:**
- ✅ Worker mode enabled (4 threads)
- ✅ Security headers
- ✅ Static file caching
- ✅ Gzip & Zstd compression
- ✅ Access logging

---

## ⚡ Quick Fix (Copy-Paste)

**Jika ingin gunakan versi GitHub (recommended):**

```bash
cd /var/www/adojobs.id

# Backup local version (optional)
sudo cp docker/frankenphp/Caddyfile docker/frankenphp/Caddyfile.backup.$(date +%Y%m%d)

# Discard local changes
sudo git checkout -- docker/frankenphp/Caddyfile

# Pull from GitHub
sudo git pull origin main

# Rebuild & restart
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml up -d
```

---

## 🎯 Complete Workflow

```bash
# Di server production (/var/www/adojobs.id):

# Step 1: Handle conflict
sudo git stash save "Local Caddyfile changes"

# Step 2: Pull latest
sudo git pull origin main

# Step 3: Run migrations (IMPORTANT!)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Step 4: Check if admin exists
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::where('role', 'admin')->count();
>>> exit

# Step 5: Create admin if needed
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin AdoJobs', 'email' => 'admin@adojobs.id', 'password' => bcrypt('SecurePass123!'), 'role' => 'admin', 'email_verified_at' => now()]);
>>> exit

# Step 6: Run seeders
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force

# Step 7: Rebuild with new Caddyfile
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache app
docker-compose -f docker-compose.prod.yml up -d

# Step 8: Clear cache
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Step 9: Test
curl -I https://adojobs.id

# Done! ✅
```

---

## ✅ Result

**Git Conflict**: ✅ **Solution Provided**  
**Migration**: ✅ **Ready to Deploy**  
**Documentation**: ✅ **Complete**  
**Commands**: ✅ **Ready to Execute**  

---

**Gunakan Option 1 (Stash) untuk safely handle conflict di production!** 🚀✨

**File**: `RESOLVE_GIT_CONFLICT_PRODUCTION.md`
