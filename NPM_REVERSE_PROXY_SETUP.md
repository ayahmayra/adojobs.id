# Konfigurasi Nginx Proxy Manager untuk AdoJobs.id

## Arsitektur

```
Internet → Cloudflare (SSL/TLS)
    ↓
103.130.82.202 (NAT IP)
    ↓
10.10.10.42 (Nginx Proxy Manager - SSL Termination)
    ↓
10.10.10.33:80 (Caddy - HTTP only)
    ↓
adojobs_app:8080 (FrankenPHP)
```

## Langkah Konfigurasi

### 1. Konfigurasi Cloudflare DNS

1. Login ke Cloudflare Dashboard
2. Pilih domain `adojobs.id`
3. Masuk ke **DNS** → **Records**
4. Tambah/Edit A Record:
   - **Type**: A
   - **Name**: `@` (untuk adojobs.id) dan `www` (untuk www.adojobs.id)
   - **IPv4 address**: `103.130.82.202`
   - **Proxy status**: ✅ Proxied (orange cloud)
   - **TTL**: Auto

### 2. Konfigurasi Nginx Proxy Manager

1. Login ke NPM di `http://10.10.10.42:81`
2. Klik **Add Proxy Host**
3. Isi form:
   - **Domain Names**: `adojobs.id`, `www.adojobs.id`
   - **Scheme**: `http`
   - **Forward Hostname/IP**: `10.10.10.33`
   - **Forward Port**: `80`
   - **Cache Assets**: ✅ (optional)
   - **Block Common Exploits**: ✅ (recommended)
   - **Websockets Support**: ✅ (jika diperlukan untuk real-time features)

4. Klik **SSL** tab:
   - **SSL Certificate**: Request a new SSL Certificate
   - **Force SSL**: ✅
   - **HTTP/2 Support**: ✅
   - **HSTS Enabled**: ✅ (optional)
   - **Email**: `nothing4ll@gmail.com`
   - Klik **Save**

5. **Advanced** tab (opsional):
   ```nginx
   # Custom Nginx Configuration (optional)
   # Untuk menambahkan custom headers atau settings
   ```

### 3. Konfigurasi Caddy (Sudah Diperbarui)

Caddyfile sudah dikonfigurasi untuk:
- ✅ Menerima HTTP request dari NPM
- ✅ Forward X-Forwarded-* headers ke aplikasi
- ✅ Tidak mencoba mendapatkan SSL certificate (karena sudah di NPM)
- ✅ Security headers tetap ada (HSTS akan di-handle NPM/Cloudflare)

### 4. Konfigurasi Laravel TrustProxies (Jika Perlu)

Pastikan Laravel trust proxy dari NPM:

Cek file `src/bootstrap/app.php` atau `src/app/Http/Middleware/TrustProxies.php`:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*'; // Trust all proxies (safe behind NPM)

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
```

Atau di Laravel 11+ (`bootstrap/app.php`):

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');
    // atau
    $middleware->trustProxies(at: ['10.10.10.42', '103.130.82.202']);
});
```

### 5. Restart Caddy Container

Setelah konfigurasi NPM selesai:

```bash
cd /var/www/adojobs.id
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy
```

### 6. Verifikasi

1. **Test dari NPM server:**
   ```bash
   curl -H "Host: adojobs.id" http://10.10.10.33/
   ```

2. **Test dari browser:**
   - Akses `https://adojobs.id`
   - Harus redirect ke HTTPS
   - Harus menampilkan halaman homepage

3. **Cek headers:**
   ```bash
   curl -I https://adojobs.id
   ```
   Harus melihat:
   - `X-Forwarded-For`
   - `X-Forwarded-Proto: https`
   - `X-Real-IP`
   - Security headers

4. **Cek logs:**
   ```bash
   docker logs adojobs_proxy --tail 50
   ```

## Troubleshooting

### Masalah: 502 Bad Gateway

**Penyebab**: NPM tidak bisa connect ke Caddy

**Solusi**:
1. Pastikan Caddy container running: `docker ps | grep adojobs_proxy`
2. Test connection dari NPM server:
   ```bash
   curl http://10.10.10.33/
   ```
3. Pastikan firewall tidak block port 80

### Masalah: SSL Certificate Error

**Penyebab**: NPM tidak bisa mendapatkan SSL certificate

**Solusi**:
1. Pastikan DNS sudah pointing ke `103.130.82.202`
2. Pastikan port 80 dan 443 terbuka di firewall
3. Cek logs NPM untuk error SSL
4. Coba request certificate lagi di NPM

### Masalah: Mixed Content / HTTP instead of HTTPS

**Penyebab**: Laravel tidak detect bahwa request datang dari HTTPS

**Solusi**:
1. Pastikan TrustProxies middleware sudah dikonfigurasi
2. Cek `APP_URL` di `.env.production` harus `https://adojobs.id`
3. Clear config cache:
   ```bash
   docker exec adojobs_app php artisan config:clear
   docker exec adojobs_app php artisan config:cache
   ```

### Masalah: Redirect Loop

**Penyebab**: Konfigurasi proxy yang salah

**Solusi**:
1. Pastikan NPM forward ke `http://10.10.10.33:80` (bukan HTTPS)
2. Pastikan Caddy tidak mencoba redirect ke HTTPS
3. Cek Caddyfile tidak ada konfigurasi SSL

## Catatan Penting

1. **SSL Termination**: NPM menangani SSL, jadi Caddy hanya menerima HTTP
2. **X-Forwarded-* Headers**: NPM akan mengirim headers ini, Caddy akan forward ke aplikasi
3. **TrustProxies**: Laravel perlu dikonfigurasi untuk trust proxy dari NPM
4. **APP_URL**: Harus di-set ke `https://adojobs.id` di production
5. **Firewall**: Pastikan port 80 dan 443 terbuka untuk NPM

## Status

✅ Caddyfile sudah dikonfigurasi untuk menerima request dari NPM
✅ X-Forwarded-* headers sudah di-handle dengan benar
✅ SSL tidak di-handle oleh Caddy (sudah di NPM)
⏳ NPM configuration perlu dilakukan di server NPM
⏳ Laravel TrustProxies perlu dicek/dikonfigurasi

