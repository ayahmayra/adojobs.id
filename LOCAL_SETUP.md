# Setup Local Development - AdoJobs.id

Panduan lengkap untuk menjalankan proyek AdoJobs.id di local development menggunakan Laravel Herd.

## 📋 Prerequisites

Sebelum memulai, pastikan Anda sudah menginstall:

1. **Laravel Herd** - Web server untuk macOS
   - Download: https://herd.laravel.com
   - Include: PHP 8.2+, Nginx, MySQL/PostgreSQL
   
2. **Node.js & NPM** - Untuk compile assets
   ```bash
   brew install node
   ```

3. **Git** - Version control
   ```bash
   brew install git
   ```

## 🚀 Quick Start

Untuk instalasi cepat, jalankan perintah berikut di direktori `src/`:

```bash
cd ~/Herd/adojobs.id/src
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan storage:link
```

Akses aplikasi di: **http://adojobs.test**

## 📖 Langkah Detail

### 1. Persiapan Project

**Clone atau pindahkan project ke direktori Herd:**

```bash
cd ~/Herd
# Jika clone dari repository
git clone <repository-url> adojobs.id

# Atau jika sudah ada, pastikan di direktori Herd
cd adojobs.id
```

### 2. Masuk ke Direktori Laravel

Project ini memiliki struktur khusus dimana aplikasi Laravel ada di folder `src/`:

```bash
cd src
```

### 3. Install Dependencies

**Install PHP dependencies via Composer:**

```bash
composer install
```

**Install Node.js dependencies:**

```bash
npm install
```

### 4. Setup Environment

**Copy environment file:**

```bash
cp .env.example .env
```

**Generate application key:**

```bash
php artisan key:generate
```

**Edit file `.env` dan sesuaikan konfigurasi:**

```env
APP_NAME="AdoJobs.id"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://adojobs.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adojobs
DB_USERNAME=root
DB_PASSWORD=

# Untuk Herd, biasanya:
# - Username: root
# - Password: kosong atau sesuai konfigurasi Herd
```

### 5. Setup Database

**Buat database baru:**

Opsi 1 - Via Command Line:
```bash
mysql -u root -e "CREATE DATABASE adojobs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Opsi 2 - Via Herd:
1. Buka aplikasi Herd
2. Klik "Open DBngin" atau "Open Database"
3. Buat database baru bernama `adojobs`

Opsi 3 - Via TablePlus/Sequel Pro:
1. Connect ke MySQL (127.0.0.1:3306)
2. Buat database baru `adojobs`

**Jalankan migration:**

```bash
php artisan migrate
```

**Jalankan seeder (jika ada):**

```bash
php artisan db:seed
```

### 6. Build Assets

**Development mode (dengan hot reload):**

```bash
npm run dev
```

**Production build:**

```bash
npm run build
```

### 7. Setup Storage

**Buat symbolic link untuk storage:**

```bash
php artisan storage:link
```

**Set permissions (jika diperlukan):**

```bash
chmod -R 775 storage bootstrap/cache
```

### 8. Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

## 🌐 Akses Aplikasi

### Via Herd (Recommended)

Herd otomatis membuat domain local berdasarkan nama folder:

```
http://adojobs.test
```

### Via Artisan Serve

Jika ingin menggunakan Laravel development server:

```bash
php artisan serve
```

Akses di: `http://localhost:8000`

### Via Composer Script

Project ini memiliki script khusus yang menjalankan semua service sekaligus:

```bash
composer dev
```

Script ini akan menjalankan:
- ✅ Laravel development server (`php artisan serve`)
- ✅ Queue worker (`php artisan queue:listen`)
- ✅ Log viewer (`php artisan pail`)
- ✅ Vite dev server (`npm run dev`)

## 🛠️ Perintah Berguna

### Development

```bash
# Jalankan development server dengan semua service
composer dev

# Jalankan migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Fresh migration dengan seeder
php artisan migrate:fresh --seed

# Lihat semua routes
php artisan route:list

# Clear semua cache
php artisan optimize:clear
```

### Testing

```bash
# Jalankan tests
php artisan test

# Atau via composer
composer test
```

### Queue & Jobs

```bash
# Jalankan queue worker
php artisan queue:work

# Jalankan queue dengan retry
php artisan queue:listen --tries=3
```

### Database

```bash
# Akses tinker (Laravel REPL)
php artisan tinker

# Backup database (jika ada package backup)
php artisan backup:run

# Lihat status migration
php artisan migrate:status
```

## 🐛 Troubleshooting

### 1. Permission Denied pada Storage

```bash
chmod -R 775 storage bootstrap/cache
sudo chown -R $(whoami):staff storage bootstrap/cache
```

### 2. Database Connection Error

**Cek apakah MySQL running:**
- Buka aplikasi Herd
- Pastikan service MySQL aktif

**Verifikasi kredensial:**
```bash
mysql -u root -p
# Masukkan password (atau kosong jika tidak ada)
```

**Update `.env`:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adojobs
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Port Already in Use

Jika port 8000 sudah digunakan:

```bash
php artisan serve --port=8001
```

### 4. Node Modules Issues

```bash
rm -rf node_modules package-lock.json
npm cache clean --force
npm install
```

### 5. Composer Issues

```bash
rm -rf vendor composer.lock
composer clear-cache
composer install
```

### 6. Domain .test Tidak Bisa Diakses

**Pastikan Herd running:**
- Buka aplikasi Herd
- Klik "Start" jika belum running

**Cek konfigurasi Herd:**
- Buka Herd Settings
- Pastikan direktori `~/Herd` sudah terdaftar
- Restart Herd jika perlu

### 7. Vite/Asset Build Error

```bash
# Clear cache
rm -rf node_modules/.vite

# Rebuild
npm run build
```

## 📁 Struktur Project

```
adojobs.id/
├── .agent/              # Workflow documentation
├── docker/              # Docker configuration
├── docs/                # Documentation
├── src/                 # 🎯 Aplikasi Laravel (WORK HERE)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   └── ...
├── .env                 # Environment config (root)
└── docker-compose.yml   # Docker setup
```

**⚠️ PENTING:** Selalu bekerja di direktori `src/` untuk development Laravel!

## 🔧 Konfigurasi Tambahan

### Email Configuration

Edit `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@adojobs.test
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue Configuration

Untuk development, gunakan `sync`:

```env
QUEUE_CONNECTION=sync
```

Untuk production-like testing, gunakan `database`:

```env
QUEUE_CONNECTION=database
```

Lalu jalankan worker:

```bash
php artisan queue:work
```

### Cache Configuration

Untuk development:

```env
CACHE_DRIVER=file
SESSION_DRIVER=file
```

Untuk production-like testing dengan Redis:

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

## 🎯 Next Steps

Setelah instalasi berhasil:

1. ✅ Buat user admin (jika ada seeder admin)
2. ✅ Test fitur-fitur utama aplikasi
3. ✅ Setup email testing (Mailtrap/MailHog)
4. ✅ Konfigurasi queue untuk background jobs
5. ✅ Setup file storage (local/S3)
6. ✅ Mulai development! 🚀

## 📚 Resources

- **Laravel Documentation:** https://laravel.com/docs
- **Laravel Herd:** https://herd.laravel.com
- **Laravel Breeze:** https://laravel.com/docs/starter-kits#breeze
- **Tailwind CSS:** https://tailwindcss.com

## 💡 Tips

1. **Gunakan Herd** untuk development - lebih mudah dan cepat
2. **Jalankan `composer dev`** untuk development dengan hot reload
3. **Gunakan Tinker** untuk testing code cepat: `php artisan tinker`
4. **Monitor logs** dengan Laravel Pail: `php artisan pail`
5. **Backup database** secara berkala saat development

---

**Happy Coding! 🎉**

Jika ada pertanyaan atau masalah, silakan buka issue atau hubungi tim development.
