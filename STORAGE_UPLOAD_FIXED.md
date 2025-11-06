# Storage Upload Fixed - Dokumentasi Lengkap

## Status
✅ **SOLVED** - Storage upload dan akses file berfungsi dengan baik

## Masalah yang Ditemukan

### 1. Storage Link Belum Dibuat
- **Issue**: Symlink dari `public/storage` ke `storage/app/public` belum dibuat
- **Solution**: `php artisan storage:link`

### 2. Caddyfile Memblokir `/storage/*`
- **Issue**: `docker/frankenphp/Caddyfile.prod` memblokir akses ke `/storage/*`
- **Solution**: Menghapus `/storage/*` dari `@disallowed` matcher

### 3. Urutan Directive Salah di Caddyfile
- **Issue**: `respond @disallowed 403` dievaluasi sebelum `file_server`, sehingga file di-block sebelum dilayani
- **Solution**: Memindahkan `file_server` sebelum blocking rules

### 4. Container Belum Di-rebuild
- **Issue**: Container production masih menggunakan Caddyfile lama (dev version)
- **Solution**: Rebuild container dengan `--no-cache`

### 5. File Permission dan Ownership Salah
- **Issue**: File dan directory di `public` dan `storage` memiliki owner `root`, bukan `www-data`
- **Solution**: `chown -R www-data:www-data /app/public /app/storage`

### 6. Cloudflare Cache
- **Issue**: Cloudflare masih serving cached 403 response
- **Solution**: Purge Cloudflare cache

## Solusi yang Diterapkan

### 1. Perbaikan Caddyfile.prod

**File**: `docker/frankenphp/Caddyfile.prod`

**Perubahan Utama:**
```caddy
# BENAR - File server dulu, blocking rules kemudian
try_files {path} {path}/ /index.php?{query}
file_server

# Blocking rules (setelah file_server)
@blocked {
    path *.env* *.log *.sql *.sqlite .git/* .gitignore .gitattributes
}
respond @blocked 403

@disallowed {
    path /bootstrap/cache/* /vendor/*
    # /storage/* DIHAPUS - tidak diblokir karena symlink valid
}
respond @disallowed 403
```

**Sebelumnya (SALAH):**
```caddy
# SALAH - Blocking rules dulu, file_server tidak pernah tercapai
@disallowed {
    path /storage/* /bootstrap/cache/* /vendor/*
}
respond @disallowed 403

try_files {path} {path}/ /index.php?{query}
file_server
```

### 2. Rebuild Container dengan Benar

```bash
# Stop container
docker-compose -f docker-compose.prod.yml --env-file .env.production stop app

# Rebuild dengan --no-cache (PENTING!)
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app

# Start container
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app
```

### 3. Fix Storage Permissions dan Ownership

```bash
# Fix ownership untuk public dan storage
docker exec adojobs_app chown -R www-data:www-data /app/public
docker exec adojobs_app chown -R www-data:www-data /app/storage

# Fix permissions
docker exec adojobs_app chmod -R 755 /app/public
docker exec adojobs_app chmod -R 775 /app/storage

# Recreate storage link dengan ownership yang benar
docker exec adojobs_app rm -f /app/public/storage
docker exec adojobs_app php artisan storage:link
```

### 4. Purge Cloudflare Cache

1. Login ke Cloudflare Dashboard: https://dash.cloudflare.com/
2. Pilih domain **adojobs.id**
3. Go to **Caching** > **Configuration**
4. Click **"Purge Everything"**

## Verifikasi Final

### 1. Check Caddyfile di Container
```bash
docker exec adojobs_app cat /etc/caddy/Caddyfile | grep -A 5 "file_server"
```

**Expected:**
```
try_files {path} {path}/ /index.php?{query}
file_server

@blocked {
    ...
}
```

### 2. Check Permissions
```bash
docker exec adojobs_app ls -ld /app/public
docker exec adojobs_app ls -la /app/public/ | grep storage
docker exec adojobs_app ls -ld /app/storage/app/public
```

**Expected:**
- Owner: `www-data:www-data`
- Permissions: `755` untuk directories, `644` atau `775` untuk files

### 3. Test HTTP Access
```bash
# Test direct IP (bypass Cloudflare)
curl -I http://10.10.10.33/storage/settings/FILENAME.png

# Test via domain
curl -I https://adojobs.id/storage/settings/FILENAME.png
```

**Expected:** HTTP 200 OK

### 4. Test Upload dari Admin Dashboard
1. Login ke `/admin/settings`
2. Upload gambar (logo, favicon, banner)
3. Verify file tersimpan di `storage/app/public/settings/`
4. Verify file bisa diakses via URL

## Scripts yang Dibuat

### Diagnosis
- `check-storage-permissions.sh` - Check semua ownership dan permissions
- `fix-cloudflare-cache.sh` - Test access bypassing Cloudflare

### Fix
- `fix-all-storage-permissions.sh` - Fix semua ownership dan permissions
- `fix-storage-403-rebuild.sh` - Rebuild container dengan benar
- `fix-storage-upload.sh` - Fix storage link dan permissions

## Konfigurasi Final yang Bekerja

### docker-compose.prod.yml
```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: production  # Menggunakan production stage
      args:
        - BUILDKIT_INLINE_CACHE=1
    volumes:
      # Mount storage untuk persistent uploads
      - ./src/storage/app:/app/storage/app
      - ./src/storage/logs:/app/storage/logs
```

### Dockerfile
```dockerfile
# Stage 3: Production build
FROM base AS production

# Copy FrankenPHP Caddyfile for production
COPY docker/frankenphp/Caddyfile.prod /etc/caddy/Caddyfile

# Permissions sudah di-set
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache
```

### docker/frankenphp/Caddyfile.prod
```caddy
:8080 {
    root * /app/public
    
    php_server
    
    # File server SEBELUM blocking rules
    try_files {path} {path}/ /index.php?{query}
    file_server
    
    # Blocking rules SETELAH file_server
    @disallowed {
        path /bootstrap/cache/* /vendor/*
        # /storage/* TIDAK diblokir
    }
    respond @disallowed 403
}
```

## Best Practices untuk Production

### 1. Storage Permissions
- **Owner**: `www-data:www-data` untuk semua files dan directories
- **Permissions**: `755` untuk directories, `644` untuk files (atau `775`/`664` jika perlu writable)

### 2. Storage Link
- Selalu gunakan `php artisan storage:link`
- Jangan manual create symlink
- Verify setelah deployment: `ls -la /app/public/ | grep storage`

### 3. Caddyfile Order
- `file_server` harus muncul SEBELUM `respond @disallowed 403`
- Jangan block `/storage/*` karena itu symlink valid

### 4. Container Build
- Selalu rebuild dengan `--no-cache` jika ada perubahan Caddyfile
- Verify Caddyfile di container setelah rebuild

### 5. Cloudflare
- Setelah perubahan aplikasi, purge Cloudflare cache
- Atau gunakan Development Mode saat testing
- Consider exclude `/storage/*` dari Cloudflare cache

## Troubleshooting

### Masalah: Upload berhasil tapi file tidak bisa diakses (403)

**Diagnosis:**
```bash
./check-storage-permissions.sh
```

**Fix:**
```bash
./fix-all-storage-permissions.sh
```

### Masalah: File bisa diakses via IP tapi tidak via domain

**Diagnosis:** Cloudflare cache

**Fix:** Purge Cloudflare cache

### Masalah: Setelah rebuild masih 403

**Diagnosis:** Container masih menggunakan Caddyfile lama

**Fix:**
```bash
# Verify Caddyfile di container
docker exec adojobs_app cat /etc/caddy/Caddyfile | head -70

# Rebuild dengan --no-cache
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app
```

## Deployment Checklist

Saat deploy ke production:

1. ✅ Pull latest code
2. ✅ Rebuild container dengan `--no-cache`
3. ✅ Verify Caddyfile di container (production version)
4. ✅ Run migrations jika ada
5. ✅ Create storage link: `php artisan storage:link`
6. ✅ Fix permissions: `chown -R www-data:www-data /app/public /app/storage`
7. ✅ Test upload dari admin dashboard
8. ✅ Test akses file via URL
9. ✅ Purge Cloudflare cache jika perlu

## Summary

✅ **Storage upload fixed** - File bisa di-upload dan diakses
✅ **Permissions correct** - Owner `www-data:www-data`
✅ **Caddyfile optimized** - Urutan directive benar
✅ **Cloudflare cache cleared** - Response 200 OK
✅ **Production ready** - Semua konfigurasi sudah benar

## Related Documentation
- `FIX_STORAGE_UPLOAD.md` - Storage upload issue dan solusi
- `FIX_STORAGE_403_ORDER.md` - Caddyfile directive order issue
- `FIX_CLOUDFLARE_CACHE.md` - Cloudflare cache issue dan cara purge

