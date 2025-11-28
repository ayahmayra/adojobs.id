# Perbaikan Caddyfile Syntax Error

## Error

```
Error: adapting config using caddyfile: parsing caddyfile tokens for 'handle': directive 'log' is not an ordered HTTP handler, so it cannot be used here
```

## Root Cause

Directive `log` tidak bisa digunakan di dalam `handle` block di Caddy. `log` harus di-level server (di dalam server block tapi di luar `handle` blocks).

## Solusi

Caddyfile sudah diperbaiki:
- ✅ `log` directive dipindahkan ke level server (di dalam `:80` block)
- ✅ `log` dihapus dari semua `handle` blocks
- ✅ Struktur tetap sama, hanya posisi `log` yang diubah

## Langkah di Production Server

```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Restart proxy container
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy

# Tunggu 5 detik
sleep 5

# Verifikasi Caddyfile
docker exec adojobs_proxy caddy validate --config /etc/caddy/Caddyfile

# Test
curl -H "Host: adojobs.id" http://10.10.10.33/
```

## Verifikasi

Setelah restart:

```bash
# Cek proxy logs (tidak boleh ada error)
docker logs adojobs_proxy --tail 20

# Test aplikasi
curl -H "Host: adojobs.id" http://10.10.10.33/

# Cek container status
docker ps | grep adojobs_proxy
```

## Status

✅ Caddyfile sudah diperbaiki
✅ Syntax error sudah diatasi
⏳ Perlu restart proxy container

