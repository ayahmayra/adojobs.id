# 🚀 Panduan Deployment Lengkap - AdoJobs.id

## Step-by-Step Deployment Guide

Panduan lengkap dari clone repository hingga aplikasi berjalan di production.

---

## 📋 Prerequisites

Sebelum memulai, pastikan server Anda memiliki:

- ✅ Ubuntu/Debian Linux (atau OS Linux lainnya)
- ✅ Docker installed (minimal versi 20.10+)
- ✅ Docker Compose installed (minimal versi 1.29+)
- ✅ Git installed
- ✅ Port 80, 443, dan 443/udp terbuka di firewall
- ✅ Minimum 2GB RAM, 2 CPU cores
- ✅ Disk space minimal 10GB

---

## 🔍 Step 1: Verifikasi Prerequisites

### 1.1 Cek Docker Installation

```bash
# Cek versi Docker
docker --version

# Cek versi Docker Compose
docker-compose --version

# Cek apakah Docker service running
sudo systemctl status docker
# atau
docker ps
```

**Jika Docker belum terinstall:**

```bash
# Ubuntu/Debian
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 1.2 Cek Git Installation

```bash
git --version
```

**Jika Git belum terinstall:**

```bash
# Ubuntu/Debian
sudo apt update
sudo apt install git -y
```

---

## 📥 Step 2: Clone Repository

### 2.1 Clone Repository

```bash
# Pindah ke directory yang diinginkan (contoh: /var/www atau /opt)
cd /var/www
# atau
cd /opt

# Clone repository
git clone https://github.com/ayahmayra/adojobs.id.git adojobs

# Masuk ke direktori project
cd adojobs
```

### 2.2 Verifikasi File

```bash
# Cek struktur direktori
ls -la

# Pastikan file-file penting ada:
# - docker-compose.prod.yml
# - Dockerfile
# - env.production.template
# - deploy-production.sh
# - docker/caddy/Caddyfile
```

---

## ⚙️ Step 3: Setup Environment Configuration

### 3.1 Copy Environment Template

```bash
# Copy template ke .env.production
cp env.production.template .env.production
```

### 3.2 Review Environment File

```bash
# Lihat isi file
cat .env.production
```

**File ini sudah berisi:**
- ✅ Database credentials yang sudah di-generate
- ✅ Konfigurasi aplikasi
- ⚠️ APP_KEY masih perlu di-generate nanti

**⚠️ PENTING:** Pastikan file `.env.production` benar-benar ada dan berisi password. Jika tidak, jalankan:
```bash
# Fix otomatis
./fix-deployment.sh
```

### 3.3 Update Caddyfile Email (PENTING!)

```bash
# Edit Caddyfile untuk SSL certificate
nano docker/caddy/Caddyfile
```

**Cari baris 7:**
```caddy
email your-email@example.com
```

**Ganti dengan email Anda yang valid:**
```caddy
email admin@adojobs.id
```

**Simpan dan keluar:**
- Tekan `Ctrl + X`
- Tekan `Y` untuk save
- Tekan `Enter` untuk confirm

---

## 🔒 Step 4: Setup Firewall (Jika diperlukan)

### 4.1 UFW (Ubuntu Firewall)

```bash
# Allow HTTP dan HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 443/udp

# Cek status
sudo ufw status
```

### 4.2 Firewalld (CentOS/RHEL)

```bash
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

---

## 🌐 Step 5: Setup DNS (Optional - Bisa dilakukan setelah deployment)

**Catatan:** DNS bisa di-setup sebelum atau sesudah deployment. SSL akan otomatis terpasang setelah DNS pointing ke server.

### 5.1 DNS Records

Di DNS provider Anda (Cloudflare, GoDaddy, dll), tambahkan:

```
Type    Name            Value           TTL
A       @               10.10.10.33     3600
A       www             10.10.10.33     3600
```

**Catatan:** Ganti `10.10.10.33` dengan IP server Anda jika berbeda.

---

## 🚀 Step 6: Jalankan Deployment Script

### 6.1 Buat Script Executable

```bash
# Berikan permission execute
chmod +x deploy-production.sh
chmod +x generate-app-key.sh
```

### 6.2 Jalankan Deployment Script

```bash
# Jalankan script deployment
./deploy-production.sh
```

**Script ini akan:**
1. ✅ Build Docker images
2. ✅ Start containers
3. ✅ Wait for database ready
4. ✅ Run migrations
5. ✅ Clear and optimize caches
6. ✅ Set permissions
7. ✅ Create storage link

**Waktu yang dibutuhkan:** Sekitar 5-10 menit (tergantung koneksi internet untuk download images)

### 6.3 Monitor Progress

Script akan menampilkan progress. Tunggu hingga selesai.

**Jika ada error:**
```bash
# Cek logs
docker-compose -f docker-compose.prod.yml logs -f
```

---

## 🔑 Step 7: Generate APP_KEY (First Time Only)

### 7.1 Generate APP_KEY

```bash
# Jalankan script generate key
./generate-app-key.sh
```

**Output akan menampilkan:**
```
Generated APP_KEY:
base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

### 7.2 Update .env.production

```bash
# Edit .env.production
nano .env.production
```

**Cari baris:**
```
APP_KEY=base64:CHANGE_THIS_ON_FIRST_DEPLOYMENT
```

**Ganti dengan key yang di-generate:**
```
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

**Simpan dan keluar:**
- `Ctrl + X`
- `Y`
- `Enter`

### 7.3 Restart Application

```bash
# Restart container app
docker-compose -f docker-compose.prod.yml restart app
```

---

## ✅ Step 8: Verifikasi Deployment

### 8.1 Cek Status Containers

```bash
# Cek semua container running
docker-compose -f docker-compose.prod.yml ps
```

**Harus menampilkan:**
```
NAME            STATUS          PORTS
adojobs_app     Up (healthy)    ...
adojobs_db      Up (healthy)    ...
adojobs_redis   Up (healthy)    ...
adojobs_proxy   Up              ...
```

### 8.2 Test Aplikasi via IP

```bash
# Test via IP
curl http://10.10.10.33
```

**Atau buka browser:**
```
http://10.10.10.33
```

### 8.3 Test via Domain (Jika DNS sudah pointing)

```bash
# Test via domain
curl https://adojobs.id
```

**Atau buka browser:**
```
https://adojobs.id
```

### 8.4 Cek Logs

```bash
# Cek logs aplikasi
docker-compose -f docker-compose.prod.yml logs app --tail=50

# Cek logs proxy (untuk SSL)
docker-compose -f docker-compose.prod.yml logs proxy --tail=50
```

---

## 🗄️ Step 9: Setup Database (First Time)

### 9.1 Run Migrations

```bash
# Run migrations (jika belum otomatis)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### 9.2 Seed Database (Optional)

```bash
# Seed database dengan data awal
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
```

**Ini akan membuat:**
- ✅ Admin user (admin@adojobs.id / password123)
- ✅ Categories
- ✅ Sample jobs
- ✅ Sample users
- ✅ Settings

---

## 🎉 Step 10: Deployment Complete!

### 10.1 Access Application

**Via IP:**
```
http://10.10.10.33
```

**Via Domain (setelah DNS pointing):**
```
https://adojobs.id
```

### 10.2 Default Admin Credentials

**Setelah seeding:**
- **Email:** `admin@adojobs.id`
- **Password:** `password123`

**⚠️ PENTING:** Ganti password admin segera setelah login pertama!

### 10.3 Login Admin

1. Buka `https://adojobs.id/login`
2. Login dengan credentials di atas
3. Ganti password segera
4. Setup settings di `/admin/settings`

---

## 🔧 Troubleshooting

### Problem: Container tidak start

```bash
# Cek logs
docker-compose -f docker-compose.prod.yml logs -f

# Restart containers
docker-compose -f docker-compose.prod.yml restart
```

### Problem: Database connection error

```bash
# Tunggu database ready (30-60 detik)
docker-compose -f docker-compose.prod.yml ps db

# Cek logs database
docker-compose -f docker-compose.prod.yml logs db

# Test connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Lalu ketik: DB::connection()->getPdo();
```

### Problem: SSL tidak terpasang

```bash
# Pastikan DNS sudah pointing ke server
# Cek email di Caddyfile sudah benar
nano docker/caddy/Caddyfile

# Restart proxy
docker-compose -f docker-compose.prod.yml restart proxy

# Cek logs proxy
docker-compose -f docker-compose.prod.yml logs proxy -f
```

### Problem: Permission denied

```bash
# Fix permissions
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 /app/storage /app/bootstrap/cache
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data /app/storage /app/bootstrap/cache
```

---

## 📝 Command Reference

### Useful Commands

```bash
# View logs
docker-compose -f docker-compose.prod.yml logs -f

# Restart all
docker-compose -f docker-compose.prod.yml restart

# Stop all
docker-compose -f docker-compose.prod.yml down

# Start all
docker-compose -f docker-compose.prod.yml up -d

# Status
docker-compose -f docker-compose.prod.yml ps

# Access container
docker-compose -f docker-compose.prod.yml exec app bash

# Run artisan commands
docker-compose -f docker-compose.prod.yml exec app php artisan <command>
```

---

## 🎯 Quick Checklist

Sebelum deployment, pastikan:

- [ ] Docker dan Docker Compose terinstall
- [ ] Git terinstall
- [ ] Repository sudah di-clone
- [ ] `.env.production` sudah dibuat dari template
- [ ] Email di `docker/caddy/Caddyfile` sudah di-update
- [ ] Firewall ports 80, 443 terbuka
- [ ] DNS records sudah di-setup (atau bisa nanti)
- [ ] Script `deploy-production.sh` sudah executable

Setelah deployment:

- [ ] APP_KEY sudah di-generate dan di-update
- [ ] Containers semua running
- [ ] Aplikasi bisa diakses via IP
- [ ] Migrations sudah di-run
- [ ] Database sudah di-seed (optional)
- [ ] Admin bisa login
- [ ] SSL certificate terpasang (jika DNS sudah pointing)

---

## 📞 Support

Jika mengalami masalah:

1. **Cek logs:** `docker-compose -f docker-compose.prod.yml logs -f`
2. **Baca dokumentasi:** `PRODUCTION_DEPLOYMENT.md`
3. **Quick reference:** `PRODUCTION_QUICK_START.md`

---

**Selamat! Deployment Anda seharusnya sudah berjalan! 🎉**

