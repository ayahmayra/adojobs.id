# Fix 403 Forbidden untuk Storage Files - Final

## Status

✅ **403 Forbidden sudah tidak terjadi** - Test dengan `filename.png` (file tidak ada) menghasilkan 404, bukan 403.

## Masalah yang Ditemukan

1. ✅ Caddyfile sudah diperbaiki (tidak memblokir `/storage/*`)
2. ⚠️  **File dengan owner `root:root`** perlu diperbaiki:
   - `bbzTHRsxgkFLRxsikKAWql2T36dmyEqlaT812ccw.png` - owner: `root:root` (harus `www-data:www-data`)

## Solusi

### Langkah 1: Rebuild App Container (Jika Belum)

Pastikan perubahan Caddyfile sudah diterapkan:

```bash
cd /var/www/adojobs.id

# Pull changes
sudo git pull origin main

# Rebuild app container
docker-compose -f docker-compose.prod.yml --env-file .env.production build app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app

# Tunggu 15 detik
sleep 15

# Verify
docker ps --format "{{.Names}} {{.Status}}" | grep adojobs_app
```

### Langkah 2: Fix File Permissions

```bash
cd /var/www/adojobs.id
./fix-storage-permissions.sh
```

**Atau manual:**

```bash
# Fix ownership
docker exec adojobs_app find /app/storage/app/public -type f -not -user www-data -exec chown www-data:www-data {} \;
docker exec adojobs_app find /app/storage/app/public -type d -not -user www-data -exec chown www-data:www-data {} \;

# Fix permissions
docker exec adojobs_app chmod -R 775 /app/storage/app/public
```

### Langkah 3: Test dengan Filename yang Benar

```bash
# Test dengan file yang benar-benar ada
curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png

# Harus return 200 OK, bukan 403 atau 404
```

## Verifikasi

### 1. Check File Permissions

```bash
docker exec adojobs_app ls -lah /app/storage/app/public/settings/
```

**Expected:**
- Semua file owner: `www-data:www-data`
- Permissions: `-rwxrwxr-x` (775)

### 2. Test Storage Access

```bash
# Test dengan file yang ada
curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png
```

**Expected:**
- HTTP/2 200 (bukan 403, bukan 404)
- `content-type: image/png` atau `image/jpeg`

### 3. Test dari Browser

Buka di browser:
```
https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png
```

**Expected:**
- Gambar tampil dengan benar
- Tidak ada error 403 atau 404

## Troubleshooting

### Masalah: Masih 403 Forbidden

**Kemungkinan:**
- App container belum di-rebuild setelah Caddyfile changes
- Caddyfile lama masih digunakan

**Solusi:**
```bash
# Rebuild app container
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
```

### Masalah: 404 Not Found (File Ada Tapi Tidak Ditemukan)

**Kemungkinan:**
- Storage link tidak ada atau broken
- File path salah

**Solusi:**
```bash
# Recreate storage link
docker exec adojobs_app rm -f /app/public/storage
docker exec adojobs_app php artisan storage:link

# Verify
docker exec adojobs_app ls -la /app/public/ | grep storage
```

### Masalah: Permission Denied Saat Upload

**Kemungkinan:**
- Directory tidak writable
- Owner tidak benar

**Solusi:**
```bash
# Fix permissions
docker exec adojobs_app chown -R www-data:www-data /app/storage
docker exec adojobs_app chmod -R 775 /app/storage
```

## Summary

✅ **403 Forbidden sudah fixed** - Caddyfile tidak lagi memblokir `/storage/*`
✅ **Storage link sudah ada** - Symlink `public/storage` sudah dibuat
⚠️  **File permissions perlu diperbaiki** - File dengan owner `root` perlu diubah ke `www-data`

## Next Steps

1. ✅ Rebuild app container (jika belum)
2. ✅ Fix file permissions dengan script
3. ✅ Test dengan filename yang benar
4. ✅ Verify dari browser

Setelah semua langkah selesai, upload gambar seharusnya berfungsi dengan baik dan file bisa diakses via URL.

