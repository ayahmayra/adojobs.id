# Force Fix URL - Rebuild Container

## Masalah
URL masih menggunakan `adojobs_app:8080` meskipun sudah clear cache. Ini berarti:
1. Environment variables tidak ter-load dengan benar
2. Container perlu di-rebuild untuk memastikan APP_URL ter-load
3. Atau Laravel masih mengambil URL dari request headers

## Solusi: Rebuild Container

### Langkah 1: Gunakan Script Force Rebuild (Recommended)

```bash
cd /var/www/adojobs.id
./fix-url-force-rebuild.sh
```

Script ini akan:
1. ✅ Cek dan update APP_URL di .env.production
2. ✅ Stop semua containers
3. ✅ Remove old cache files
4. ✅ Start containers dengan fresh environment
5. ✅ Clear dan rebuild semua cache
6. ✅ Verify URL generation

### Langkah 2: Manual Force Rebuild

Jika script tidak bekerja, lakukan manual:

```bash
cd /var/www/adojobs.id

# 1. Pastikan APP_URL di .env.production
echo "APP_URL=https://adojobs.id" >> .env.production
# Atau edit manual: nano .env.production

# 2. Stop semua containers
docker-compose -f docker-compose.prod.yml --env-file .env.production down

# 3. Remove old cache (jika ada)
docker volume rm adojobsid_frankenphp_cache || true

# 4. Pull latest code
sudo git pull origin main

# 5. Rebuild container dengan no-cache
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app

# 6. Start containers
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d

# 7. Tunggu containers ready (30 detik)
sleep 30

# 8. Clear semua cache
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
docker exec adojobs_app php artisan optimize:clear

# 9. Rebuild cache
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache

# 10. Verify
docker exec adojobs_app php artisan tinker --execute="echo route('home');"
# Harus output: https://adojobs.id/
```

### Langkah 3: Verifikasi Environment Variables

```bash
# Cek apakah APP_URL ter-load di container
docker exec adojobs_app env | grep APP_URL

# Cek config Laravel
docker exec adojobs_app php artisan tinker --execute="echo config('app.url');"

# Test route generation
docker exec adojobs_app php artisan tinker --execute="echo route('home');"
```

## Troubleshooting

### Masalah: APP_URL masih tidak ter-load setelah rebuild

**Solusi: Set APP_URL langsung di docker-compose.prod.yml**

File `docker-compose.prod.yml` sudah di-update untuk force APP_URL. Tapi jika masih tidak bekerja:

```bash
# Edit docker-compose.prod.yml
nano docker-compose.prod.yml

# Di bagian environment app, pastikan ada:
#   - APP_URL=https://adojobs.id

# Lalu rebuild
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build app
```

### Masalah: Route masih menggunakan request-based URL

**Kemungkinan**: Laravel URL generator menggunakan request headers meskipun APP_URL sudah di-set.

**Solusi**: Pastikan Laravel menggunakan `config('app.url')` untuk generate URLs. Cek apakah ada middleware atau service provider yang override URL generation.

### Masalah: Cache masih menggunakan URL lama

**Solusi**: 
```bash
# Remove semua cache files secara manual
docker exec adojobs_app rm -rf /app/bootstrap/cache/*.php
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
```

## Catatan Penting

1. **Rebuild dengan --no-cache**: Untuk memastikan environment variables ter-load dengan benar
2. **APP_URL harus absolute**: `https://adojobs.id` (bukan relative)
3. **docker-compose.prod.yml**: Sudah di-update untuk force APP_URL
4. **Environment variables**: Setelah rebuild, cek dengan `docker exec adojobs_app env | grep APP_URL`

## Status

✅ Script force rebuild sudah dibuat
✅ docker-compose.prod.yml sudah di-update
✅ Dokumentasi lengkap tersedia
⏳ Perlu dijalankan di production server

