# Perbaikan Error: Uninitialized string offset 0 di Symfony Request

## Masalah

Error terjadi di `Symfony\HttpFoundation\Request::getPort()` line 879:
```
Uninitialized string offset 0 at /app/vendor/symfony/http-foundation/Request.php:879
```

Error ini terjadi saat Laravel mencoba generate URL (`Request::fullUrl()`) dan memanggil `getPort()` yang mencoba mengakses string offset yang tidak ada.

## Root Cause

Error terjadi karena:
1. **Host header tidak valid atau kosong**: Symfony `getPort()` mencoba parse port dari Host header
2. **X-Forwarded-Port tidak ter-set**: Laravel mencoba mengambil port dari forwarded headers tapi tidak ada
3. **Port parsing gagal**: Ketika Host header tidak mengandung port (misalnya hanya `adojobs.id` tanpa `:443`), Symfony mencoba mengakses offset 0 yang tidak ada

## Solusi

Caddyfile sudah diperbaiki untuk:
- ✅ Set `X-Forwarded-Port 443` untuk domain (HTTPS dari NPM)
- ✅ Set `X-Forwarded-Port 80` untuk IP access (HTTP)
- ✅ Set Host header selalu valid (`adojobs.id` bukan kosong)
- ✅ Set X-Forwarded-Host secara eksplisit

## Langkah di Production Server

```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Restart proxy container
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy

# Tunggu 5 detik
sleep 5

# Test aplikasi
curl -H "Host: adojobs.id" http://10.10.10.33/
```

## Verifikasi

Setelah restart:

```bash
# Test dengan Host header
curl -v -H "Host: adojobs.id" http://10.10.10.33/

# Cek logs (tidak boleh ada error)
docker logs adojobs_app --tail 20 | grep -i error
```

## Catatan Penting

1. **X-Forwarded-Port**: Sekarang selalu ter-set (443 untuk domain, 80 untuk IP)
2. **Host header**: Selalu ter-set ke valid domain (`adojobs.id`)
3. **X-Forwarded-Host**: Di-set secara eksplisit untuk konsistensi

## Status

✅ Caddyfile sudah diperbaiki
✅ X-Forwarded-Port ditambahkan
✅ Host header selalu valid
⏳ Perlu restart proxy container

