---
description: Langkah instalasi awal Laravel untuk local development menggunakan Herd
---

# Setup Local Development dengan Herd

Panduan ini menjelaskan langkah-langkah instalasi awal untuk menjalankan proyek Laravel AdoJobs.id di local development menggunakan Laravel Herd.

## Prerequisites

1. **Laravel Herd** sudah terinstall di macOS
   - Download dari: https://herd.laravel.com
   - Herd sudah include PHP, Nginx, dan database (MySQL/PostgreSQL)

2. **Composer** (sudah include di Herd)

3. **Node.js & NPM** untuk asset compilation
   - Install via Homebrew: `brew install node`

## Langkah Instalasi

### 1. Pastikan Herd Berjalan

Buka aplikasi Herd dan pastikan service sudah running. Herd akan otomatis mendeteksi folder di direktori `~/Herd/`.

### 2. Clone atau Pindahkan Project

Jika belum ada, clone project ke direktori Herd:

```bash
cd ~/Herd
git clone <repository-url> adojobs.id
cd adojobs.id
```

### 3. Masuk ke Direktori Source Laravel

```bash
cd src
```

### 4. Install Dependencies PHP

// turbo
```bash
composer install
```

### 5. Setup Environment File

Copy file `.env.example` menjadi `.env`:

// turbo
```bash
cp .env.example .env
```

### 6. Generate Application Key

// turbo
```bash
php artisan key:generate
```

### 7. Konfigurasi Database

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=adojobs
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan untuk Herd:**
- Herd menggunakan MySQL secara default
- Username default: `root`
- Password default: kosong (atau sesuai konfigurasi Herd Anda)
- Anda bisa mengakses database via TablePlus atau tool database lainnya

### 8. Buat Database

Buka aplikasi Herd, klik menu "Open DBngin" atau gunakan TablePlus untuk membuat database baru bernama `adojobs`.

Atau via command line:

```bash
mysql -u root -e "CREATE DATABASE adojobs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 9. Jalankan Migration

// turbo
```bash
php artisan migrate
```

Jika ada seeder, jalankan juga:

```bash
php artisan db:seed
```

### 10. Install Dependencies Node.js

// turbo
```bash
npm install
```

### 11. Build Assets (Development)

Untuk development dengan hot reload:

```bash
npm run dev
```

Atau untuk build production:

```bash
npm run build
```

### 12. Setup Storage Link

// turbo
```bash
php artisan storage:link
```

### 13. Clear Cache (Optional)

// turbo
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 14. Akses Aplikasi

Herd akan otomatis membuat domain local untuk project Anda:

```
http://adojobs.test
```

Atau sesuai dengan nama folder di direktori Herd Anda.

## Menjalankan Development Server

### Opsi 1: Menggunakan Herd (Recommended)

Herd sudah otomatis menjalankan web server. Cukup akses:
```
http://adojobs.test
```

### Opsi 2: Menggunakan Artisan Serve

Jika ingin menggunakan Laravel development server:

```bash
php artisan serve
```

Akses di: `http://localhost:8000`

### Opsi 3: Menggunakan Composer Script

Project ini memiliki script `dev` yang menjalankan server, queue, logs, dan vite secara bersamaan:

```bash
composer dev
```

Script ini akan menjalankan:
- Laravel development server
- Queue worker
- Log viewer (Pail)
- Vite dev server untuk hot reload

## Troubleshooting

### Permission Issues

Jika ada masalah permission pada storage:

```bash
chmod -R 775 storage bootstrap/cache
```

### Database Connection Error

1. Pastikan MySQL di Herd sudah running
2. Cek kredensial database di file `.env`
3. Pastikan database sudah dibuat

### Port Already in Use

Jika port 8000 sudah digunakan (untuk artisan serve):

```bash
php artisan serve --port=8001
```

### Node Modules Issues

Jika ada masalah dengan npm:

```bash
rm -rf node_modules package-lock.json
npm install
```

## Struktur Project

Project ini memiliki struktur khusus:
- Root directory berisi Docker files dan deployment scripts
- **`src/`** berisi aplikasi Laravel yang sebenarnya
- Pastikan selalu bekerja di direktori `src/` untuk development

## Perintah Berguna

```bash
# Masuk ke direktori Laravel
cd ~/Herd/adojobs.id/src

# Jalankan migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Fresh migration dengan seeder
php artisan migrate:fresh --seed

# Clear semua cache
php artisan optimize:clear

# Lihat routes
php artisan route:list

# Jalankan queue worker
php artisan queue:work

# Jalankan tests
php artisan test
```

## Next Steps

Setelah instalasi berhasil:

1. Buat user admin jika diperlukan
2. Setup email configuration di `.env`
3. Konfigurasi queue driver (database, redis, dll)
4. Setup file storage (local, S3, dll)
5. Mulai development! 🚀
