# Perbaikan HTTPS Redirect Issue

## Masalah
Caddy melakukan 308 Permanent Redirect ke HTTPS padahal SSL sudah di-handle oleh NPM.

## Root Cause
Caddy secara default akan mencoba mendapatkan SSL certificate untuk domain yang disebutkan tanpa prefix `http://`, yang menyebabkan automatic HTTPS redirect.

## Solusi
Menggunakan explicit `http://` prefix untuk domain agar Caddy tidak mencoba mendapatkan SSL certificate.

## Perubahan

**Sebelum:**
```caddyfile
adojobs.id, www.adojobs.id {
    ...
}
```

**Sesudah:**
```caddyfile
http://adojobs.id, http://www.adojobs.id {
    ...
}
```

## Langkah di Production Server

```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Restart proxy container
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy

# Verifikasi Caddyfile
docker exec adojobs_proxy caddy validate --config /etc/caddy/Caddyfile
```

## Verifikasi

Setelah restart, test lagi dari NPM server:

```bash
curl -v -H "Host: adojobs.id" http://10.10.10.33/
```

**Expected result:**
- HTTP Status: 200 (bukan 308)
- Response: HTML content dari aplikasi
- Tidak ada Location header dengan HTTPS

## Catatan

- Caddy sekarang hanya menerima HTTP request (SSL di-handle NPM)
- Tidak akan ada automatic HTTPS redirect
- Domain tetap bisa diakses via HTTPS melalui NPM

