# ⚡ Quick Start Guide - JobMaker

Jalankan JobMaker Job Portal System dalam **10 menit**!

---

## 📋 Prerequisites

Pastikan Anda sudah menginstall:
- ✅ **Docker Desktop** (atau Docker + Docker Compose)
- ✅ **Git**
- ✅ **4GB RAM** minimum
- ✅ **10GB Disk Space**

**Verifikasi instalasi:**
```bash
docker --version        # Docker version 20.x atau lebih baru
docker-compose --version  # Docker Compose version 2.x atau lebih baru
```

**Jika belum terinstall:**
- macOS/Windows: Download [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- Linux: Ikuti [panduan instalasi Docker](https://docs.docker.com/engine/install/)

---

## 🚀 Installation Steps

### Step 1: Clone Project

```bash
# Clone repository
git clone <your-repository-url> jobmakerproject
cd jobmakerproject
```

---

### Step 2: Setup Environment

```bash
# Copy file environment (sudah pre-configured)
cp src/.env.example src/.env
```

✅ **File `.env` sudah dikonfigurasi dengan benar untuk Docker!**  
❌ **JANGAN ubah nilai `DB_HOST`, `DB_DATABASE`, `REDIS_HOST`**

---

### Step 3: Build Docker Images

**⏱️ Estimasi waktu: 5-10 menit (pertama kali saja)**

```bash
# PASTIKAN Docker Desktop sedang berjalan!

# Build image
docker-compose build app
```

**Dengan Makefile (jika tersedia):**
```bash
make build
```

**Proses build akan:**
- Download FrankenPHP base image (~500MB)
- Install PHP extensions (Redis, MySQL, GD, dll)
- Install Composer
- Compile OPcache

**☕ Ambil kopi sebentar... proses ini butuh waktu!**

---

### Step 4: Start All Containers

```bash
# Start semua services
docker-compose up -d
```

**Dengan Makefile:**
```bash
make up
```

**Tunggu 30-60 detik** agar database siap.

**Verifikasi containers berjalan:**
```bash
docker-compose ps

# Output yang diharapkan:
# NAME                  STATUS
# jobmaker_app          Up (healthy)
# jobmaker_db           Up
# jobmaker_redis        Up
# jobmaker_phpmyadmin   Up
```

---

### Step 5: Install Laravel Dependencies

```bash
# Install Composer packages
docker-compose exec app composer install

# Generate encryption key
docker-compose exec app php artisan key:generate
```

**Dengan Makefile:**
```bash
make composer ARGS="install"
make artisan ARGS="key:generate"
```

---

### Step 6: Setup Database

```bash
# Run migrations & seeders (membuat tabel + data demo)
docker-compose exec app php artisan migrate:fresh --seed --force
```

**Dengan Makefile:**
```bash
make fresh
```

**Proses ini akan:**
- ✅ Membuat semua tabel database
- ✅ Mengisi data demo:
  - 1 Admin user
  - 3 Employer users
  - 5 Job Seeker users
  - 15 Job categories
  - 30 Job postings
  - 25 Job applications

---

### Step 7: Clear Cache (Opsional tapi Direkomendasikan)

```bash
# Clear semua cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

**Dengan Makefile:**
```bash
make clear
```

---

## ✅ Verification

### Test Redis Connection
```bash
docker-compose exec app php artisan tinker --execute="echo 'Redis: ' . (\Illuminate\Support\Facades\Redis::connection()->ping() ? 'OK' : 'FAILED');"
```

**Expected output:** `Redis: OK`

### Test Database
```bash
docker-compose exec app php artisan tinker --execute="echo 'Users: ' . \App\Models\User::count();"
```

**Expected output:** `Users: 9`

---

## 🌐 Access the Application

Buka browser Anda dan akses:

### 1. Aplikasi Utama
```
http://localhost:8282
```

### 2. PHPMyAdmin (Database Admin)
```
http://localhost:8281
```
- **Server:** `db`
- **Username:** `jobmaker_user`
- **Password:** `jobmaker_password`

---

## 👤 Login dengan Demo Accounts

### Admin
```
📧 Email: admin@jobmaker.local
🔑 Password: password
🔗 Dashboard: http://localhost:8282/admin/dashboard
```

**Capabilities:**
- Manage semua users
- Manage semua jobs
- Manage categories
- System settings

### Employer (Perusahaan)
```
📧 Email: employer1@jobmaker.local
🔑 Password: password
🔗 Dashboard: http://localhost:8282/employer/dashboard
```

**Capabilities:**
- Post new jobs
- Manage job listings
- Review applications
- Update application status

### Job Seeker (Pencari Kerja)
```
📧 Email: seeker1@jobmaker.local
🔑 Password: password
🔗 Dashboard: http://localhost:8282/seeker/dashboard
```

**Capabilities:**
- Browse jobs
- Apply for jobs
- Track application status
- Manage profile

---

## 🛠️ Common Commands

### Docker Operations
```bash
# View all logs
docker-compose logs -f

# View app logs only
docker-compose logs -f app

# Stop all containers
docker-compose down

# Restart all containers
docker-compose restart

# Rebuild and restart
docker-compose down
docker-compose build
docker-compose up -d
```

### Laravel Commands
```bash
# Access Laravel Tinker (REPL)
docker-compose exec app php artisan tinker

# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Run migrations
docker-compose exec app php artisan migrate

# Fresh database with seed
docker-compose exec app php artisan migrate:fresh --seed --force

# Access container shell
docker-compose exec app bash
```

### With Makefile (Shortcut)
```bash
make logs          # View logs
make down          # Stop containers
make up            # Start containers
make restart       # Restart containers
make shell         # Access container
make clear         # Clear caches
make fresh         # Fresh DB with seed
```

---

## 🐛 Troubleshooting

### Issue 1: Docker Daemon Not Running

**Error:**
```
Cannot connect to the Docker daemon
```

**Solution:**
```bash
# macOS: Buka Docker Desktop
open -a Docker

# Tunggu hingga Docker running, lalu coba lagi
```

---

### Issue 2: Port Already in Use

**Error:**
```
Bind for 0.0.0.0:8080 failed: port is already allocated
```

**Solution:**

Opsi A - Stop service yang menggunakan port:
```bash
# macOS/Linux
lsof -ti:8080 | xargs kill -9

# Windows PowerShell
Get-Process -Id (Get-NetTCPConnection -LocalPort 8080).OwningProcess | Stop-Process
```

Opsi B - Ubah port di `docker-compose.yml`:
```yaml
services:
  app:
    ports:
      - "8090:8080"  # Ganti 8080 ke 8090
```

---

### Issue 3: Container Keeps Restarting

**Error:**
```
jobmaker_app   Restarting
```

**Solution:**
```bash
# Lihat logs untuk identifikasi masalah
docker-compose logs app --tail=50

# Biasanya karena:
# 1. Database belum siap -> Tunggu 1-2 menit
# 2. Caddyfile error -> Rebuild container
# 3. Permission error -> Fix permissions

# Rebuild jika perlu
docker-compose down
docker-compose build --no-cache app
docker-compose up -d
```

---

### Issue 4: Class "Redis" Not Found

**Error:**
```
Class "Redis" not found
```

**Solution:**
```bash
# Redis extension sudah terinstall di Dockerfile terbaru
# Rebuild container:
docker-compose down
docker-compose build --no-cache app
docker-compose up -d

# Verifikasi Redis extension
docker-compose exec app php -m | grep redis
# Output: redis
```

---

### Issue 5: Database Connection Error

**Error:**
```
SQLSTATE[HY000] [2002] Connection refused
```

**Solution:**
```bash
# Tunggu database fully initialized (30-60 detik)
docker-compose ps

# Jika db container sudah Up, coba migration lagi
docker-compose exec app php artisan migrate:fresh --seed --force
```

---

### Issue 6: Permission Denied

**Error:**
```
The stream or file "storage/logs/laravel.log" could not be opened
```

**Solution:**
```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

---

## 📚 Next Steps

Setelah instalasi berhasil, coba eksplorasi fitur:

1. ✅ **Login sebagai Admin** → Manage users, jobs, categories
2. ✅ **Login sebagai Employer** → Post jobs, review applications
3. ✅ **Login sebagai Seeker** → Browse jobs, apply for positions
4. ✅ **Check PHPMyAdmin** → Explore database structure
5. ✅ **Read full documentation** → [README.md](README.md)

---

## 🔄 Daily Usage

### Start Working
```bash
# Pagi: Start containers
docker-compose up -d

# Verifikasi
docker-compose ps
```

### Stop Working
```bash
# Sore: Stop containers
docker-compose down
```

### Update Code
```bash
# Setelah pull code baru
docker-compose exec app composer install
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
```

---

## 📖 Full Documentation

Untuk dokumentasi lengkap, lihat:
- **[INSTALLATION.md](INSTALLATION.md)** - Panduan instalasi detail
- **[README.md](README.md)** - Dokumentasi lengkap project
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - Panduan kontribusi

---

## 🆘 Need Help?

1. **Check logs:** `docker-compose logs -f app`
2. **Check troubleshooting** di atas
3. **Read full docs:** [INSTALLATION.md](INSTALLATION.md)
4. **Open issue** di repository

---

## ✅ Installation Checklist

- [ ] Docker Desktop terinstall dan running
- [ ] Project di-clone
- [ ] Environment file copied (`src/.env`)
- [ ] Docker images built (`docker-compose build app`)
- [ ] Containers started (`docker-compose up -d`)
- [ ] Composer dependencies installed
- [ ] Application key generated
- [ ] Database migrated & seeded
- [ ] Cache cleared
- [ ] Application accessible at http://localhost:8282
- [ ] Login berhasil dengan demo account

---

**🎉 Happy Coding!**

**Application:** http://localhost:8282  
**Admin Login:** admin@jobmaker.local / password

---

*Last Updated: October 2025*
