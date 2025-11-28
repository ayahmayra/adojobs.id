# Perbaikan Asset URLs Error

## Masalah

Dari console browser terlihat dua error:

1. **Mixed Content Warning**: Form submit ke HTTP endpoint internal
2. **DNS Resolution Error**: Asset CSS/JS menggunakan hostname internal Docker `adojobs app:8080` yang tidak bisa di-resolve browser

**Error Messages:**
- `Failed to load resource: net::ERR_NAME_NOT_RESOLVED` untuk `app-BfS92mTR.css`
- `Failed to load resource: net::ERR_NAME_NOT_RESOLVED` untuk `adojobs app:8080/build/assets/app-CXDpL9bK.js`

## Root Cause

`APP_URL` tidak ter-set dengan benar atau config cache masih menggunakan URL lama. Laravel `asset()` helper menggunakan `APP_URL` untuk generate absolute URLs untuk assets.

## Solusi

### Langkah 1: Pastikan APP_URL di .env.production

```bash
cd /var/www/adojobs.id

# Cek APP_URL
grep APP_URL .env.production

# Jika tidak ada atau salah, edit:
nano .env.production
# Pastikan ada baris:
# APP_URL=https://adojobs.id
```

### Langkah 2: Clear dan Rebuild Cache

```bash
# Clear semua cache
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear

# Rebuild cache dengan APP_URL yang benar
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache
```

### Langkah 3: Verifikasi APP_URL

```bash
docker exec adojobs_app php artisan tinker --execute="echo config('app.url');"
```

Harus output: `https://adojobs.id`

### Langkah 4: Restart Container (Jika Perlu)

Jika APP_URL masih salah setelah rebuild cache:

```bash
docker-compose -f docker-compose.prod.yml --env-file .env.production restart app

# Tunggu container restart, lalu rebuild cache lagi
docker exec adojobs_app php artisan config:cache
```

### Langkah 5: Test Asset URLs

```bash
# Test asset URL generation
docker exec adojobs_app php artisan tinker --execute="echo asset('build/assets/app.css');"
```

Harus output URL dengan `https://adojobs.id`, bukan `http://adojobs app:8080`

## Script Otomatis

Gunakan script yang sudah dibuat:

```bash
cd /var/www/adojobs.id
./fix-asset-urls-production.sh
```

## Verifikasi di Browser

Setelah fix:

1. **Hard refresh browser**: `Ctrl+Shift+R` (Windows/Linux) atau `Cmd+Shift+R` (Mac)
2. **Cek browser console**: Tidak boleh ada error `ERR_NAME_NOT_RESOLVED`
3. **Cek Network tab**: Asset requests harus ke `https://adojobs.id/build/assets/...`
4. **Cek form action**: Form actions harus ke `https://adojobs.id/...` (bukan HTTP internal)

## Troubleshooting

### Masalah: APP_URL masih salah setelah rebuild

**Solusi:**
1. Pastikan `.env.production` benar-benar berisi `APP_URL=https://adojobs.id`
2. Restart container: `docker-compose -f docker-compose.prod.yml --env-file .env.production restart app`
3. Rebuild cache lagi

### Masalah: Assets masih tidak load

**Solusi:**
1. Cek apakah assets ada di `public/build/assets/`:
   ```bash
   docker exec adojobs_app ls -la /app/public/build/assets/
   ```
2. Cek permissions:
   ```bash
   docker exec adojobs_app chmod -R 755 /app/public/build
   ```
3. Rebuild assets jika perlu:
   ```bash
   docker exec adojobs_app npm run build
   ```

### Masalah: Mixed Content masih muncul

**Solusi:**
1. Pastikan semua form actions menggunakan `route()` helper (bukan hardcoded URL)
2. Pastikan `APP_URL` menggunakan HTTPS
3. Clear view cache: `docker exec adojobs_app php artisan view:clear`

## Catatan Penting

1. **APP_URL harus HTTPS**: Karena Cloudflare/NPM menangani SSL, `APP_URL` harus `https://adojobs.id`
2. **Config Cache**: Setelah mengubah `.env.production`, selalu clear dan rebuild config cache
3. **Browser Cache**: Hard refresh browser untuk clear browser cache
4. **Asset URLs**: Laravel `asset()` helper otomatis menggunakan `APP_URL` untuk generate absolute URLs

## Status

✅ Script fix sudah dibuat
✅ Dokumentasi lengkap tersedia
⏳ Perlu dijalankan di production server

