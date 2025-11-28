# Perbaikan Storage Upload - Upload Gambar Tidak Bekerja

## Masalah

Upload gambar tidak bekerja di production. File mungkin tidak tersimpan atau tidak bisa diakses.

**Error 403 Forbidden**: File di `/storage/settings/*` mengembalikan 403 Forbidden karena Caddyfile memblokir akses ke `/storage/*`.

## Root Cause

1. **Caddyfile memblokir `/storage/*`**: Konfigurasi `docker/frankenphp/Caddyfile.prod` memblokir akses ke `/storage/*`, padahal Laravel menggunakan symlink `public/storage` yang harus bisa diakses
2. **Storage link belum dibuat**: Laravel perlu symbolic link dari `public/storage` ke `storage/app/public`
3. **Permissions tidak benar**: Directory storage mungkin tidak writable
4. **Storage directory tidak ada**: Directory `storage/app/public` mungkin tidak ada

## Solusi

### Langkah 1: Fix 403 Forbidden (Priority)

**Jika mendapatkan error 403 Forbidden untuk file di `/storage/*`:**

```bash
cd /var/www/adojobs.id
./fix-storage-403.sh
```

Script ini akan:
1. ✅ Pull perubahan terbaru (Caddyfile fix)
2. ✅ Rebuild app container
3. ✅ Verify app health

**Atau manual:**

```bash
cd /var/www/adojobs.id

# Pull changes
sudo git pull origin main

# Rebuild app container
docker-compose -f docker-compose.prod.yml --env-file .env.production build app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
```

### Langkah 2: Gunakan Script Fix Storage Link (Jika Storage Link Belum Ada)

```bash
cd /var/www/adojobs.id
./fix-storage-upload.sh
```

Script ini akan:
1. ✅ Create storage link
2. ✅ Fix storage permissions
3. ✅ Verify storage link
4. ✅ Check storage directories
5. ✅ Test storage access

### Langkah 2: Manual Fix

```bash
cd /var/www/adojobs.id

# 1. Create storage link
docker exec adojobs_app php artisan storage:link

# 2. Fix permissions
docker exec adojobs_app chown -R www-data:www-data /app/storage /app/public/storage
docker exec adojobs_app chmod -R 775 /app/storage /app/public/storage

# 3. Verify storage link
docker exec adojobs_app ls -la /app/public/storage

# 4. Check if storage directories exist
docker exec adojobs_app ls -la /app/storage/app/public/
```

## Verifikasi

Setelah fix:

```bash
# 1. Cek storage link
docker exec adojobs_app ls -la /app/public/ | grep storage
# Harus ada: storage -> ../storage/app/public

# 2. Cek permissions
docker exec adojobs_app ls -la /app/storage/app/public/
# Owner harus: www-data

# 3. Test upload dari admin dashboard
# - Upload gambar di /admin/settings
# - Cek apakah file muncul di storage/app/public/
```

## Troubleshooting

### Masalah: Storage link sudah ada tapi upload masih gagal

**Solusi**:
```bash
# Remove existing link dan create baru
docker exec adojobs_app rm -f /app/public/storage
docker exec adojobs_app php artisan storage:link
```

### Masalah: Permission denied saat upload

**Solusi**:
```bash
# Fix permissions dengan lebih agresif
docker exec adojobs_app chown -R www-data:www-data /app/storage
docker exec adojobs_app chmod -R 775 /app/storage
docker exec adojobs_app chmod -R 775 /app/public/storage
```

### Masalah: File ter-upload tapi tidak bisa diakses via URL

**Solusi**:
1. Cek storage link: `docker exec adojobs_app ls -la /app/public/storage`
2. Cek file ada di storage: `docker exec adojobs_app ls -la /app/storage/app/public/`
3. Test URL: `curl https://adojobs.id/storage/filename.jpg`

### Masalah: Storage directory tidak ada

**Solusi**:
```bash
# Create storage directories
docker exec adojobs_app mkdir -p /app/storage/app/public
docker exec adojobs_app php artisan storage:link
docker exec adojobs_app chown -R www-data:www-data /app/storage
docker exec adojobs_app chmod -R 775 /app/storage
```

## Catatan Penting

1. **Storage Link**: Harus dibuat dengan `php artisan storage:link`
2. **Permissions**: Directory storage harus writable oleh www-data
3. **URL Access**: File di storage bisa diakses via `https://adojobs.id/storage/filename.jpg`
4. **Volume Mount**: Pastikan volume mount `./src/storage/app:/app/storage/app` di docker-compose.prod.yml sudah benar

## Status

✅ Script fix sudah dibuat
✅ Dokumentasi lengkap tersedia
⏳ Perlu dijalankan di production server

