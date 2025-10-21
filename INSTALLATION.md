# 📦 Installation Guide - JobMaker Project

Panduan lengkap untuk menginstall dan menjalankan JobMaker Job Portal System di komputer atau server baru.

---

## 📋 Prerequisites (Persyaratan)

Sebelum memulai, pastikan sistem Anda memiliki:

### 1. Docker Desktop atau Docker Engine

**Untuk macOS:**
```bash
# Download Docker Desktop dari:
https://www.docker.com/products/docker-desktop/

# Atau install via Homebrew:
brew install --cask docker

# Verifikasi instalasi:
docker --version
docker-compose --version
```

**Untuk Linux (Ubuntu/Debian):**
```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verifikasi
docker --version
docker-compose --version
```

**Untuk Windows:**
```bash
# Download dan install Docker Desktop dari:
https://www.docker.com/products/docker-desktop/

# Pastikan WSL2 sudah terinstall
# Verifikasi di PowerShell:
docker --version
docker-compose --version
```

### 2. Git

```bash
# Verifikasi Git terinstall:
git --version

# Jika belum terinstall:
# macOS: brew install git
# Linux: sudo apt-get install git
# Windows: https://git-scm.com/download/win
```

### 3. Text Editor (Opsional)
- VS Code, Sublime Text, atau editor favorit Anda

---

## 🚀 Langkah Instalasi

### Step 1: Clone atau Download Project

**Opsi A: Via Git Clone**
```bash
# Clone repository
git clone <your-repository-url> jobmakerproject
cd jobmakerproject
```

**Opsi B: Download ZIP**
```bash
# Download project sebagai ZIP
# Extract ke folder jobmakerproject
cd jobmakerproject
```

---

### Step 2: Persiapan Environment

Buat file `.env` untuk konfigurasi Laravel:

```bash
# Copy template environment
cp src/.env.example src/.env
```

**PENTING:** File `.env` sudah dikonfigurasi dengan benar untuk Docker. Tidak perlu diubah kecuali untuk production.

Verifikasi isi `src/.env` memiliki konfigurasi berikut:

```env
APP_NAME=JobMaker
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8080

# Database Configuration (JANGAN DIUBAH untuk Docker)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=jobmaker_db
DB_USERNAME=jobmaker_user
DB_PASSWORD=jobmaker_password

# Redis Configuration (JANGAN DIUBAH untuk Docker)
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

### Step 3: Build Docker Containers

Pastikan Docker Desktop sedang berjalan, lalu jalankan:

```bash
# Build Docker images (pertama kali akan memakan waktu 5-10 menit)
docker-compose build app

# Atau jika menggunakan Makefile:
make build
```

**Catatan:** Proses build akan:
- Download base image FrankenPHP
- Install PHP extensions (Redis, MySQL, GD, dll)
- Install Composer
- Compile OPcache
- Total waktu: 5-10 menit (tergantung koneksi internet)

---

### Step 4: Start All Containers

```bash
# Start semua containers
docker-compose up -d

# Atau dengan Makefile:
make up
```

Tunggu beberapa detik hingga semua containers running. Verifikasi dengan:

```bash
docker-compose ps

# Output yang diharapkan:
# NAME                  STATUS
# jobmaker_app          Up (healthy)
# jobmaker_db           Up
# jobmaker_redis        Up
# jobmaker_phpmyadmin   Up
```

**Troubleshooting:** Jika container `jobmaker_app` restart terus:
```bash
# Lihat logs untuk error:
docker-compose logs app

# Biasanya karena menunggu database ready
# Tunggu 30-60 detik, lalu cek lagi:
docker-compose ps
```

---

### Step 5: Install Laravel Dependencies

```bash
# Install Composer packages
docker-compose exec app composer install

# Atau dengan Makefile:
make composer ARGS="install"
```

**Catatan:** Jika belum ada file `.env`, jalankan:
```bash
docker-compose exec app cp .env.example .env
```

---

### Step 6: Generate Application Key

```bash
# Generate encryption key
docker-compose exec app php artisan key:generate

# Atau dengan Makefile:
make artisan ARGS="key:generate"
```

---

### Step 7: Setup Database

Jalankan migrations dan seeders untuk membuat tabel dan data demo:

```bash
# Run migrations dan seeders
docker-compose exec app php artisan migrate:fresh --seed --force

# Atau dengan Makefile:
make fresh
```

**Proses ini akan:**
1. Drop semua tabel yang ada (jika ada)
2. Membuat tabel baru
3. Mengisi database dengan data demo:
   - 1 Admin user
   - 3 Employer users dengan company profiles
   - 5 Job Seeker users dengan seeker profiles
   - 15 Job categories
   - 30 Job postings
   - 25 Job applications

---

### Step 8: Install Laravel Breeze (Opsional)

Jika belum terinstall, install authentication scaffolding:

```bash
# Install Breeze
docker-compose exec app php artisan breeze:install blade --no-interaction
```

**Catatan:** NPM tidak diperlukan untuk development basic. Frontend sudah ter-compile.

---

### Step 9: Clear Cache

```bash
# Clear semua cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear

# Atau dengan Makefile:
make clear
```

---

### Step 10: Verifikasi Instalasi

#### A. Test Redis Connection
```bash
docker-compose exec app php artisan tinker --execute="echo 'Redis: ' . (\Illuminate\Support\Facades\Redis::connection()->ping() ? 'OK' : 'FAILED');"
```

Output yang diharapkan:
```
Redis: OK
```

#### B. Test Database Connection
```bash
docker-compose exec app php artisan tinker --execute="echo 'DB Tables: ' . count(DB::select('SHOW TABLES'));"
```

Output yang diharapkan:
```
DB Tables: 11
```

#### C. Akses Aplikasi

Buka browser dan akses:

1. **Aplikasi Utama:** http://localhost:8080
   - Harus menampilkan homepage JobMaker

2. **PHPMyAdmin:** http://localhost:8081
   - Server: `db`
   - Username: `jobmaker_user`
   - Password: `jobmaker_password`

---

## 👤 Default Login Credentials

Setelah seeding berhasil, gunakan akun berikut untuk login:

### Admin
```
Email: admin@jobmaker.local
Password: password
Dashboard: http://localhost:8080/admin/dashboard
```

### Employer (Perusahaan)
```
Email: employer1@jobmaker.local
Password: password
Dashboard: http://localhost:8080/employer/dashboard
```

### Job Seeker (Pencari Kerja)
```
Email: seeker1@jobmaker.local
Password: password
Dashboard: http://localhost:8080/seeker/dashboard
```

**Catatan:** Semua password default adalah `password`

---

## 🔧 Post-Installation Setup

### Set Permissions (Linux/macOS)

Jika mengalami permission errors:

```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Enable Queue Worker (Opsional)

Untuk background jobs:

```bash
# Jalankan queue worker
docker-compose exec -d app php artisan queue:work

# Atau dengan Makefile:
make queue
```

### Setup Cron Jobs (Production)

Untuk scheduled tasks di production:

```bash
# Tambahkan ke crontab server:
* * * * * cd /path-to-project && docker-compose exec -T app php artisan schedule:run >> /dev/null 2>&1
```

---

## 📊 Verifikasi Data Demo

Setelah instalasi, Anda harus memiliki:

```bash
# Cek jumlah data
docker-compose exec app php artisan tinker
```

Di dalam tinker, jalankan:
```php
echo "Users: " . \App\Models\User::count() . "\n";
echo "Employers: " . \App\Models\Employer::count() . "\n";
echo "Seekers: " . \App\Models\Seeker::count() . "\n";
echo "Categories: " . \App\Models\Category::count() . "\n";
echo "Jobs: " . \App\Models\Job::count() . "\n";
echo "Applications: " . \App\Models\Application::count() . "\n";
```

Output yang diharapkan:
```
Users: 9
Employers: 3
Seekers: 5
Categories: 15
Jobs: 30
Applications: 25
```

---

## 🐛 Troubleshooting Common Issues

### Issue 1: Docker Daemon Not Running

**Error:**
```
Cannot connect to the Docker daemon. Is the docker daemon running?
```

**Solusi:**
```bash
# macOS: Buka Docker Desktop dari Applications
open -a Docker

# Linux: Start Docker service
sudo systemctl start docker

# Verifikasi
docker ps
```

---

### Issue 2: Port Already in Use

**Error:**
```
Bind for 0.0.0.0:8080 failed: port is already allocated
```

**Solusi:**

Opsi A - Stop service yang menggunakan port:
```bash
# macOS/Linux: Cari process yang menggunakan port
lsof -ti:8080 | xargs kill -9

# Windows: 
netstat -ano | findstr :8080
taskkill /PID <PID> /F
```

Opsi B - Ubah port di `docker-compose.yml`:
```yaml
services:
  app:
    ports:
      - "8090:8080"  # Ubah 8080 ke 8090
```

---

### Issue 3: Class "Redis" Not Found

**Error:**
```
Class "Redis" not found
```

**Solusi:**
```bash
# Rebuild container dengan Redis extension
docker-compose down
docker-compose build --no-cache app
docker-compose up -d

# Verifikasi Redis extension
docker-compose exec app php -m | grep redis
```

---

### Issue 4: Migration Errors - Table Already Exists

**Error:**
```
SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'jobs' already exists
```

**Solusi:**
```bash
# Drop semua tabel dan migrate ulang
docker-compose exec app php artisan migrate:fresh --seed --force
```

---

### Issue 5: Permission Denied Errors

**Error:**
```
The stream or file "storage/logs/laravel.log" could not be opened
```

**Solusi:**
```bash
# Fix permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

---

### Issue 6: Caddyfile Configuration Error

**Error:**
```
Error: adapting config using caddyfile: directive 'php_server' is not an ordered
```

**Solusi:** Caddyfile sudah diperbaiki dalam versi terbaru. Jika masih error:
```bash
# Copy Caddyfile yang benar
docker cp ./docker/frankenphp/Caddyfile jobmaker_app:/etc/caddy/Caddyfile
docker-compose restart app
```

---

## 🔄 Stop & Start Application

### Stop Aplikasi
```bash
# Stop semua containers
docker-compose down

# Atau dengan Makefile:
make down
```

### Start Aplikasi
```bash
# Start semua containers
docker-compose up -d

# Atau dengan Makefile:
make up
```

### Restart Aplikasi
```bash
# Restart semua containers
docker-compose restart

# Atau dengan Makefile:
make restart
```

---

## 🗑️ Uninstall / Clean Up

### Remove Containers Only
```bash
docker-compose down
```

### Remove Containers + Volumes (Database Data)
```bash
docker-compose down -v
```

### Remove Everything (Containers, Volumes, Images)
```bash
docker-compose down -v --rmi all

# Atau dengan Makefile:
make clean
```

---

## 📦 Deployment ke Server Production

### 1. Requirements Server
- Docker & Docker Compose
- Minimal 2GB RAM
- 20GB Storage
- Nginx (reverse proxy) - opsional
- SSL Certificate (Let's Encrypt)

### 2. Environment Variables
Update `src/.env` untuk production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_PASSWORD=<strong-password>
REDIS_PASSWORD=<strong-password>
```

### 3. Docker Compose untuk Production
```bash
# Build production image
docker-compose -f docker-compose.prod.yml build

# Start dengan production config
docker-compose -f docker-compose.prod.yml up -d
```

### 4. Optimize untuk Production
```bash
# Optimize Laravel
docker-compose exec app php artisan optimize
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Atau dengan Makefile:
make optimize
```

---

## 🔐 Security Checklist

Untuk production deployment:

- [ ] Ubah semua default passwords
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY`
- [ ] Setup HTTPS/SSL
- [ ] Setup firewall rules
- [ ] Enable database backups
- [ ] Setup monitoring (Sentry, Laravel Telescope)
- [ ] Review file permissions
- [ ] Disable PHPMyAdmin di production

---

## 📚 Additional Resources

- [Docker Documentation](https://docs.docker.com/)
- [Laravel Documentation](https://laravel.com/docs)
- [FrankenPHP Documentation](https://frankenphp.dev/)
- [Redis Documentation](https://redis.io/docs/)
- [MariaDB Documentation](https://mariadb.org/documentation/)

---

## 🆘 Need Help?

1. Check logs: `docker-compose logs -f`
2. Check container status: `docker-compose ps`
3. Review this troubleshooting guide
4. Open an issue in the repository

---

**Installation Complete! 🎉**

Access your application at: **http://localhost:8080**

Default Login:
- Admin: `admin@jobmaker.local` / `password`
- Employer: `employer1@jobmaker.local` / `password`
- Seeker: `seeker1@jobmaker.local` / `password`

---

*Last Updated: October 2025*

