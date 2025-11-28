# Perbaikan URL Generation - Semua Link Berubah ke Internal Docker

## Masalah

Semua link/URL berubah menjadi `http://adojobs_app:8080/kategori/transportasi-logistik` yang merupakan hostname internal Docker yang tidak bisa di-resolve browser.

## Root Cause

Laravel `route()` helper mengambil URL dari request headers (Host header) ketika `APP_URL` tidak ter-set dengan benar atau tidak ter-load. Karena request datang dari NPM dengan Host header internal, Laravel menggunakan hostname tersebut.

## Solusi

### Langkah 1: Pastikan APP_URL di .env.production

```bash
cd /var/www/adojobs.id

# Cek APP_URL
grep APP_URL .env.production

# Harus ada:
# APP_URL=https://adojobs.id
```

Jika tidak ada atau salah, edit `.env.production`:
```bash
nano .env.production
# Tambahkan atau edit baris:
APP_URL=https://adojobs.id
```

### Langkah 2: Gunakan Script Fix (Recommended)

```bash
cd /var/www/adojobs.id
./fix-url-generation-production.sh
```

Script ini akan:
1. ✅ Cek APP_URL di .env.production
2. ✅ Clear semua cache
3. ✅ Rebuild cache dengan APP_URL yang benar
4. ✅ Test route URL generation
5. ✅ Restart container jika perlu

### Langkah 3: Manual Fix (Jika Script Tidak Bekerja)

```bash
cd /var/www/adojobs.id

# 1. Pastikan APP_URL benar
grep APP_URL .env.production
# Harus output: APP_URL=https://adojobs.id

# 2. Clear SEMUA cache
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear
docker exec adojobs_app php artisan optimize:clear

# 3. Restart container untuk reload .env.production
docker-compose -f docker-compose.prod.yml --env-file .env.production restart app

# 4. Tunggu container restart (10-15 detik)
sleep 15

# 5. Rebuild cache
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache

# 6. Verifikasi
docker exec adojobs_app php artisan tinker --execute="echo route('home');"
# Harus output: https://adojobs.id/
```

### Langkah 4: Verifikasi

```bash
# Test route URL generation
docker exec adojobs_app php artisan tinker --execute="echo route('home');"
# Expected: https://adojobs.id/

# Test kategori route
docker exec adojobs_app php artisan tinker --execute="echo route('categories.show', ['category' => 'transportasi-logistik']);"
# Expected: https://adojobs.id/kategori/transportasi-logistik
```

## Troubleshooting

### Masalah: Route URLs masih menggunakan internal hostname setelah fix

**Kemungkinan penyebab:**
1. `.env.production` tidak ter-load dengan benar
2. Container tidak menggunakan `.env.production`
3. Config cache masih menggunakan nilai lama

**Solusi:**
```bash
# 1. Cek apakah container menggunakan .env.production
docker exec adojobs_app env | grep APP_URL

# 2. Jika tidak ada, restart container dengan explicit env-file
docker-compose -f docker-compose.prod.yml --env-file .env.production down
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d

# 3. Clear dan rebuild cache lagi
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan config:cache
```

### Masalah: APP_URL masih salah setelah rebuild

**Solusi:**
```bash
# Force reload environment
docker-compose -f docker-compose.prod.yml --env-file .env.production restart app
sleep 10
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan config:cache

# Test lagi
docker exec adojobs_app php artisan tinker --execute="echo config('app.url');"
```

### Masalah: URLs masih menggunakan HTTP bukan HTTPS

**Solusi:**
1. Pastikan `APP_URL=https://adojobs.id` (bukan `http://`)
2. Clear config cache dan rebuild
3. Pastikan TrustProxies middleware sudah dikonfigurasi (sudah ada di `bootstrap/app.php`)

## Catatan Penting

1. **APP_URL harus absolute URL**: `https://adojobs.id` (bukan relative atau internal)
2. **Config cache**: Setelah mengubah `.env.production`, SELALU clear dan rebuild config cache
3. **Container restart**: Kadang perlu restart container untuk reload environment variables
4. **TrustProxies**: Sudah dikonfigurasi di `bootstrap/app.php` untuk trust semua proxy dari NPM

## Status

✅ Script fix sudah dibuat (`fix-url-generation-production.sh`)
✅ Dokumentasi lengkap tersedia
⏳ Perlu dijalankan di production server

## Verifikasi di Browser

Setelah fix:
1. Hard refresh browser: `Ctrl+Shift+R` atau `Cmd+Shift+R`
2. Inspect element pada link kategori
3. Link harus: `https://adojobs.id/kategori/transportasi-logistik`
4. Bukan: `http://adojobs_app:8080/kategori/transportasi-logistik`

