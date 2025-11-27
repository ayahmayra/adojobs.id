# Fresh Server Setup Guide - AdoJobs.id

Panduan lengkap untuk setup server dari awal, termasuk pembersihan Docker dan instalasi stack yang dibutuhkan.

## Prerequisites

- Server Ubuntu 20.04/22.04/24.04
- Akses root atau sudo
- Koneksi internet stabil

---

## Step 1: Pembersihan Docker Sebelumnya

### 1.1 Hentikan Semua Container yang Berjalan

```bash
# Hentikan semua container
sudo docker stop $(sudo docker ps -aq) 2>/dev/null || true
```

### 1.2 Hapus Semua Container

```bash
# Hapus semua container (running dan stopped)
sudo docker rm -f $(sudo docker ps -aq) 2>/dev/null || true
```

### 1.3 Hapus Semua Images

```bash
# Hapus semua images
sudo docker rmi -f $(sudo docker images -q) 2>/dev/null || true
```

### 1.4 Hapus Semua Volumes

```bash
# Hapus semua volumes
sudo docker volume rm $(sudo docker volume ls -q) 2>/dev/null || true
```

### 1.5 Hapus Semua Networks (Kecuali Default)

```bash
# Hapus semua custom networks
sudo docker network prune -f
```

### 1.6 Pembersihan Menyeluruh

```bash
# Hapus semua yang tidak terpakai (images, containers, volumes, networks)
sudo docker system prune -a --volumes -f
```

### 1.7 Verifikasi Pembersihan

```bash
# Pastikan tidak ada container
sudo docker ps -a

# Pastikan tidak ada images
sudo docker images

# Pastikan tidak ada volumes
sudo docker volume ls

# Cek disk space yang dibebaskan
sudo docker system df
```

### 1.8 (Optional) Uninstall Docker Completely

Jika ingin menghapus Docker sepenuhnya:

```bash
# Stop Docker service
sudo systemctl stop docker
sudo systemctl stop docker.socket
sudo systemctl stop containerd

# Remove Docker packages
sudo apt-get purge -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin

# Remove Docker directories
sudo rm -rf /var/lib/docker
sudo rm -rf /var/lib/containerd
sudo rm -rf /etc/docker
sudo rm -rf ~/.docker

# Remove Docker group
sudo groupdel docker 2>/dev/null || true

# Clean up
sudo apt-get autoremove -y
sudo apt-get autoclean
```

---

## Step 2: Instalasi Stack yang Dibutuhkan

### 2.1 Update System

```bash
# Update package list
sudo apt update

# Upgrade packages
sudo apt upgrade -y
```

### 2.2 Install Dependencies Dasar

```bash
# Install essential packages
sudo apt install -y \
    curl \
    wget \
    git \
    unzip \
    software-properties-common \
    apt-transport-https \
    ca-certificates \
    gnupg \
    lsb-release
```

### 2.3 Install Nginx

```bash
# Install Nginx
sudo apt install -y nginx

# Start dan enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Verifikasi
sudo systemctl status nginx
nginx -v
```

### 2.4 Install PHP 8.2 dan Extensions

```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.2 dan extensions yang dibutuhkan
sudo apt install -y \
    php8.2-fpm \
    php8.2-cli \
    php8.2-common \
    php8.2-mysql \
    php8.2-zip \
    php8.2-gd \
    php8.2-mbstring \
    php8.2-curl \
    php8.2-xml \
    php8.2-bcmath \
    php8.2-intl \
    php8.2-redis \
    php8.2-soap \
    php8.2-imagick \
    php8.2-opcache

# Start dan enable PHP-FPM
sudo systemctl start php8.2-fpm
sudo systemctl enable php8.2-fpm

# Verifikasi
php -v
sudo systemctl status php8.2-fpm
```

### 2.5 Install Composer

```bash
# Download Composer installer
curl -sS https://getcomposer.org/installer -o composer-setup.php

# Install Composer globally
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Remove installer
rm composer-setup.php

# Verifikasi
composer --version
```

### 2.6 Install MariaDB

```bash
# Install MariaDB
sudo apt install -y mariadb-server mariadb-client

# Start dan enable MariaDB
sudo systemctl start mariadb
sudo systemctl enable mariadb

# Secure installation
sudo mysql_secure_installation
```

**Jawaban untuk mysql_secure_installation:**
- Enter current password for root: `[Enter]` (kosong jika baru install)
- Switch to unix_socket authentication: `N`
- Change the root password: `Y` (masukkan password yang kuat)
- Remove anonymous users: `Y`
- Disallow root login remotely: `Y`
- Remove test database: `Y`
- Reload privilege tables: `Y`

```bash
# Verifikasi
sudo systemctl status mariadb
mysql --version
```

### 2.7 Install Redis

```bash
# Install Redis
sudo apt install -y redis-server

# Configure Redis untuk production
sudo sed -i 's/^supervised no/supervised systemd/' /etc/redis/redis.conf

# Restart Redis
sudo systemctl restart redis-server
sudo systemctl enable redis-server

# Verifikasi
sudo systemctl status redis-server
redis-cli ping  # Should return PONG
```

### 2.8 Install Supervisor

```bash
# Install Supervisor
sudo apt install -y supervisor

# Start dan enable Supervisor
sudo systemctl start supervisor
sudo systemctl enable supervisor

# Verifikasi
sudo systemctl status supervisor
supervisorctl --version
```

### 2.9 Install Node.js dan NPM (untuk build assets)

```bash
# Install Node.js 20.x LTS
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Verifikasi
node -v
npm -v
```

### 2.10 Konfigurasi Firewall (UFW)

```bash
# Install UFW jika belum ada
sudo apt install -y ufw

# Allow SSH (PENTING: jangan sampai terkunci!)
sudo ufw allow OpenSSH

# Allow HTTP dan HTTPS
sudo ufw allow 'Nginx Full'

# Enable firewall
sudo ufw --force enable

# Verifikasi
sudo ufw status
```

---

## Step 3: Setup Aplikasi AdoJobs.id

### 3.1 Buat User untuk Aplikasi

```bash
# Buat user 'adojobs' (optional, bisa pakai user existing)
sudo useradd -m -s /bin/bash adojobs

# Set password
sudo passwd adojobs

# Tambahkan ke sudo group (optional)
sudo usermod -aG sudo adojobs
```

### 3.2 Buat Direktori Aplikasi

```bash
# Buat direktori untuk aplikasi
sudo mkdir -p /var/www/adojobs.id

# Set ownership
sudo chown -R $USER:$USER /var/www/adojobs.id
```

### 3.3 Clone Repository

```bash
# Pindah ke direktori parent
cd /var/www

# Hapus direktori lama jika ada
sudo rm -rf adojobs.id

# Clone repository langsung (BUKAN ke direktori kosong)
git clone https://github.com/ayahmayra/adojobs.id.git

# Atau jika sudah ada SSH key
# git clone git@github.com:ayahmayra/adojobs.id.git

# Masuk ke direktori hasil clone
cd adojobs.id
```

> **Note:** Jika repository private, pastikan sudah setup SSH key atau gunakan personal access token.

**Verifikasi Clone Berhasil:**

```bash
# Cek isi direktori root
ls -la

# Harus melihat:
# - src/ (direktori source code Laravel)
# - docker/ (konfigurasi Docker)
# - Dockerfile
# - docker-compose.yml
# - README.md
# dll

# Source code Laravel ada di direktori src/
ls -la src/

# Cek apakah file Laravel ada
ls -la src/composer.json src/artisan src/package.json
```

> **PENTING:** Struktur repository ini berbeda dari Laravel standar. Source code Laravel ada di direktori **`src/`**, bukan di root!

**Troubleshooting Clone Issues:**

Jika repository private dan perlu authentication:

```bash
# Gunakan Personal Access Token (PAT)
git clone https://YOUR_USERNAME:YOUR_TOKEN@github.com/ayahmayra/adojobs.id.git

# Atau setup SSH key terlebih dahulu
# Generate SSH key
ssh-keygen -t ed25519 -C "your_email@example.com"

# Copy public key
cat ~/.ssh/id_ed25519.pub
# Tambahkan ke GitHub Settings > SSH Keys

# Test SSH connection
ssh -T git@github.com

# Clone dengan SSH
git clone git@github.com:ayahmayra/adojobs.id.git
```

Jika clone gagal karena network:

```bash
# Test koneksi
ping github.com

# Test git
git --version

# Coba clone dengan verbose
GIT_TRACE=1 git clone https://github.com/ayahmayra/adojobs.id.git
```

### 3.4 Install Dependencies PHP

> **IMPORTANT:** Jangan jalankan composer sebagai root! Pastikan Anda menjalankan sebagai user biasa yang memiliki akses ke direktori.

```bash
# Pastikan Anda berada di direktori yang benar
pwd  # Harus menampilkan: /var/www/adojobs.id

# Masuk ke direktori src/ (source code Laravel)
cd src

# Pastikan composer.json ada
ls -la composer.json

# Install Composer dependencies (production)
composer install --optimize-autoloader --no-dev

# Atau untuk development
# composer install
```

**Jika muncul error "Do not run Composer as root":**

```bash
# Pastikan direktori dimiliki oleh user Anda (bukan root)
sudo chown -R $USER:$USER /var/www/adojobs.id

# Kemudian jalankan composer lagi (tanpa sudo)
composer install --optimize-autoloader --no-dev
```

**Jika composer.json tidak ditemukan:**

```bash
# Cek apakah Anda di direktori yang benar
pwd

# Pastikan Anda di /var/www/adojobs.id/src
cd /var/www/adojobs.id/src

# Cek isi direktori
ls -la

# Jika file Laravel tidak ada, berarti clone repository gagal
# Kembali ke Step 3.3 dan pastikan clone berhasil
```

### 3.5 Install Dependencies Node.js

```bash
# Pastikan masih di direktori src/
pwd  # Harus: /var/www/adojobs.id/src

# Install NPM packages
npm install

# Build assets untuk production
npm run build

# Atau untuk development
# npm run dev
```

### 3.6 Setup Environment File

```bash
# Pastikan masih di /var/www/adojobs.id/src
pwd

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 3.7 Konfigurasi Environment

Edit file `.env`:

```bash
nano .env
```

Update konfigurasi berikut:

```env
APP_NAME="AdoJobs.id"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://adojobs.id

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adojobs_db
DB_USERNAME=adojobs_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail (sesuaikan dengan provider Anda)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@adojobs.id"
MAIL_FROM_NAME="${APP_NAME}"
```

### 3.8 Setup Database

```bash
# Login ke MariaDB
sudo mysql -u root -p
```

Jalankan SQL berikut:

```sql
-- Buat database
CREATE DATABASE adojobs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Buat user
CREATE USER 'adojobs_user'@'localhost' IDENTIFIED BY 'your_secure_password';

-- Berikan privileges
GRANT ALL PRIVILEGES ON adojobs_db.* TO 'adojobs_user'@'localhost';

-- Flush privileges
FLUSH PRIVILEGES;

-- Exit
EXIT;
```

### 3.9 Jalankan Migrasi Database

```bash
# Jalankan migrasi
php artisan migrate --force

# Jalankan seeder (jika ada)
php artisan db:seed --force
```

### 3.10 Setup Storage dan Cache

```bash
# Pastikan di direktori src/
cd /var/www/adojobs.id/src

# Buat symbolic link untuk storage
php artisan storage:link

# Clear dan cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### 3.11 Set Permissions

```bash
# Set ownership ke www-data (Nginx user) untuk seluruh direktori
sudo chown -R www-data:www-data /var/www/adojobs.id

# Set permissions untuk storage dan cache (di dalam src/)
sudo chmod -R 775 /var/www/adojobs.id/src/storage
sudo chmod -R 775 /var/www/adojobs.id/src/bootstrap/cache

# Set permissions untuk direktori src/
sudo find /var/www/adojobs.id/src -type f -exec chmod 644 {} \;
sudo find /var/www/adojobs.id/src -type d -exec chmod 755 {} \;
```

---

## Step 4: Konfigurasi Nginx

### 4.1 Buat Konfigurasi Nginx

```bash
# Buat file konfigurasi
sudo nano /etc/nginx/sites-available/adojobs.id
```

Isi dengan konfigurasi berikut:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name adojobs.id www.adojobs.id;
    
    # Redirect to HTTPS (akan diaktifkan setelah SSL setup)
    # return 301 https://$server_name$request_uri;
    
    root /var/www/adojobs.id/src/public;
    index index.php index.html index.htm;

    # Logging
    access_log /var/log/nginx/adojobs.id-access.log;
    error_log /var/log/nginx/adojobs.id-error.log;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json application/javascript;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Increase timeout untuk request yang lama
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 4.2 Enable Site

```bash
# Buat symbolic link
sudo ln -s /etc/nginx/sites-available/adojobs.id /etc/nginx/sites-enabled/

# Hapus default site (optional)
sudo rm /etc/nginx/sites-enabled/default

# Test konfigurasi
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## Step 5: Setup Queue Worker dengan Supervisor

### 5.1 Buat Konfigurasi Supervisor

```bash
# Buat file konfigurasi
sudo nano /etc/supervisor/conf.d/adojobs-worker.conf
```

Isi dengan:

```ini
[program:adojobs-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/adojobs.id/src/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/adojobs.id/src/storage/logs/worker.log
stopwaitsecs=3600
```

### 5.2 Update Supervisor

```bash
# Reload konfigurasi
sudo supervisorctl reread
sudo supervisorctl update

# Start worker
sudo supervisorctl start adojobs-worker:*

# Cek status
sudo supervisorctl status
```

---

## Step 6: Setup SSL dengan Let's Encrypt (Optional tapi Recommended)

### 6.1 Install Certbot

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx
```

### 6.2 Dapatkan SSL Certificate

```bash
# Dapatkan certificate (pastikan domain sudah pointing ke server)
sudo certbot --nginx -d adojobs.id -d www.adojobs.id
```

Ikuti instruksi:
- Masukkan email untuk notifikasi
- Setuju terms of service
- Pilih redirect HTTP ke HTTPS (recommended)

### 6.3 Auto-renewal

```bash
# Test auto-renewal
sudo certbot renew --dry-run

# Certbot akan otomatis setup cron job untuk renewal
```

---

## Step 7: Setup Cron Jobs

### 7.1 Edit Crontab

```bash
# Edit crontab untuk www-data user
sudo crontab -u www-data -e
```

### 7.2 Tambahkan Laravel Scheduler

```cron
# Laravel Scheduler
* * * * * cd /var/www/adojobs.id/src && php artisan schedule:run >> /dev/null 2>&1
```

---

## Step 8: Verifikasi dan Testing

### 8.1 Cek Services

```bash
# Cek Nginx
sudo systemctl status nginx

# Cek PHP-FPM
sudo systemctl status php8.2-fpm

# Cek MariaDB
sudo systemctl status mariadb

# Cek Redis
sudo systemctl status redis-server

# Cek Supervisor
sudo systemctl status supervisor
sudo supervisorctl status
```

### 8.2 Cek Logs

```bash
# Nginx error log
sudo tail -f /var/log/nginx/adojobs.id-error.log

# Laravel log
tail -f /var/www/adojobs.id/src/storage/logs/laravel.log

# PHP-FPM log
sudo tail -f /var/log/php8.2-fpm.log
```

### 8.3 Test Aplikasi

```bash
# Test dari command line
curl http://localhost

# Atau buka di browser
# http://your-server-ip
# atau
# https://adojobs.id (jika sudah setup domain dan SSL)
```

### 8.4 Test Queue

```bash
# Dispatch test job dari tinker
php artisan tinker

# Di tinker:
# dispatch(new App\Jobs\TestJob());
# exit
```

---

## Troubleshooting

### Permission Issues

```bash
# Reset permissions
sudo chown -R www-data:www-data /var/www/adojobs.id
sudo chmod -R 775 /var/www/adojobs.id/src/storage
sudo chmod -R 775 /var/www/adojobs.id/src/bootstrap/cache
```

### 502 Bad Gateway

```bash
# Cek PHP-FPM status
sudo systemctl status php8.2-fpm

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Cek socket file
ls -la /var/run/php/php8.2-fpm.sock
```

### Database Connection Error

```bash
# Cek MariaDB status
sudo systemctl status mariadb

# Test connection
mysql -u adojobs_user -p adojobs_db

# Cek .env file
cat /var/www/adojobs.id/src/.env | grep DB_
```

### Queue Not Processing

```bash
# Cek supervisor status
sudo supervisorctl status

# Restart worker
sudo supervisorctl restart adojobs-worker:*

# Cek worker log
tail -f /var/www/adojobs.id/src/storage/logs/worker.log
```

---

## Maintenance Commands

### Update Aplikasi

```bash
# Masuk ke direktori aplikasi
cd /var/www/adojobs.id

# Pull perubahan terbaru
git pull origin main

# Masuk ke direktori src/
cd src

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Jalankan migrasi
php artisan migrate --force

# Clear dan rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue worker
sudo supervisorctl restart adojobs-worker:*
```

### Backup Database

```bash
# Backup database
mysqldump -u adojobs_user -p adojobs_db > backup-$(date +%Y%m%d-%H%M%S).sql

# Atau dengan gzip
mysqldump -u adojobs_user -p adojobs_db | gzip > backup-$(date +%Y%m%d-%H%M%S).sql.gz
```

### Monitor Resources

```bash
# Cek disk usage
df -h

# Cek memory usage
free -h

# Cek CPU usage
top

# Cek process
ps aux | grep php
```

---

## Security Checklist

- [ ] Firewall (UFW) enabled dan configured
- [ ] SSH key-based authentication (disable password login)
- [ ] SSL/TLS certificate installed
- [ ] Database user dengan privileges minimal
- [ ] `.env` file tidak accessible dari web
- [ ] `APP_DEBUG=false` di production
- [ ] Regular security updates (`sudo apt update && sudo apt upgrade`)
- [ ] Backup strategy implemented
- [ ] Log monitoring setup
- [ ] Rate limiting configured di aplikasi

---

## Useful Commands Reference

```bash
# Restart semua services
sudo systemctl restart nginx php8.2-fpm mariadb redis-server supervisor

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check Laravel version
php artisan --version

# List all artisan commands
php artisan list

# Enter maintenance mode
php artisan down

# Exit maintenance mode
php artisan up
```

---

## Next Steps

1. Setup monitoring (optional): Install monitoring tools seperti Netdata, Prometheus, atau New Relic
2. Setup backup automation: Buat script untuk backup otomatis database dan files
3. Setup CI/CD (optional): Integrate dengan GitHub Actions untuk automated deployment
4. Performance tuning: Optimize PHP-FPM, Nginx, dan MariaDB configuration
5. Setup logging aggregation: Integrate dengan ELK stack atau Papertrail

---

**Dokumentasi ini dibuat untuk AdoJobs.id**  
Last updated: 2025-11-27
