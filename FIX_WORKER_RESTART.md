# Perbaikan Worker Restart Issue

## Masalah
FrankenPHP workers terus restart di production, menyebabkan:
- ❌ HTTP requests timeout
- ❌ Container unhealthy
- ❌ Application tidak bisa diakses
- ❌ Workers restart setiap ~20ms

## Root Cause
Worker mode (`num 2`) di production tidak kompatibel dengan setup saat ini, kemungkinan karena:
1. Storage volume mount conflicts
2. Worker bootstrap issues dengan Laravel
3. Missing dependencies atau permissions

## Solusi
Menyamakan konfigurasi production dengan development yang sudah terbukti bekerja:

### 1. Menonaktifkan Worker Mode ✅
**Sebelum:**
```caddyfile
{
    frankenphp {
        num_threads 8
        worker {
            file /app/public/index.php
            num 2
        }
    }
}
```

**Sesudah (sama seperti development):**
```caddyfile
{
    frankenphp  # NO worker mode
    order php_server before file_server
}
```

### 2. Menyamakan Logging ✅
**Sebelum:** File logging ke `/var/log/caddy/access.log`
**Sesudah:** stdout logging (sama seperti development)

### 3. Menambahkan File Server ✅
Menambahkan `file_server` directive yang ada di development

### 4. Health Check ✅
Sudah disamakan sebelumnya:
- Endpoint: `/` (root)
- Start period: 40s

## Perubahan File

### `docker/frankenphp/Caddyfile.prod`
- ✅ Worker mode dihapus
- ✅ Logging diubah ke stdout
- ✅ File server ditambahkan

### `docker-compose.prod.yml`
- ✅ Health check sudah disamakan (endpoint `/`, start_period 40s)

### `Dockerfile`
- ✅ Health check sudah disamakan

## Langkah Deployment di Production

```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Rebuild container dengan konfigurasi baru (tanpa worker mode)
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build app

# Monitor status (tunggu 1-2 menit)
watch -n 2 'docker ps --filter "name=adojobs_app"'

# Cek logs untuk memastikan tidak ada restart loop
docker logs adojobs_app --tail 50 -f
```

## Verifikasi

Setelah rebuild, cek:

1. **Container Status:**
   ```bash
   docker ps --filter "name=adojobs_app"
   ```
   Status harus: `Up X minutes (healthy)`

2. **Logs (tidak ada restart loop):**
   ```bash
   docker logs adojobs_app --tail 50
   ```
   Tidak boleh ada banyak pesan "restarting" worker

3. **Health Endpoint:**
   ```bash
   docker exec adojobs_app curl -f http://localhost:8080/
   ```
   Harus mengembalikan HTML response (200 OK)

4. **Aplikasi Accessible:**
   ```bash
   curl http://10.10.10.33/
   ```
   Harus mengembalikan halaman homepage

## Catatan Penting

1. **Worker Mode vs Standard Mode:**
   - Worker mode: Pre-loads PHP untuk performance (untuk production skala besar)
   - Standard mode: Traditional PHP request handling (lebih stabil, sama seperti development)
   - Untuk saat ini, standard mode lebih stabil dan sudah terbukti bekerja

2. **Performance:**
   - Standard mode masih sangat cepat untuk production
   - Worker mode bisa diaktifkan kembali nanti jika diperlukan dan setelah masalah storage/permissions teratasi

3. **Konsistensi:**
   - Production sekarang menggunakan konfigurasi yang sama dengan development (yang sudah terbukti bekerja)
   - Ini memudahkan debugging dan maintenance

## Troubleshooting

Jika masih ada masalah setelah rebuild:

1. **Cek logs:**
   ```bash
   docker logs adojobs_app --tail 100
   ```

2. **Test manual:**
   ```bash
   docker exec adojobs_app curl -v http://localhost:8080/
   ```

3. **Cek database:**
   ```bash
   docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo();"
   ```

4. **Cek Redis:**
   ```bash
   docker exec adojobs_redis redis-cli ping
   ```

5. **Gunakan script diagnosa:**
   ```bash
   ./check-app-health.sh
   ```

## Status

✅ Worker mode dinonaktifkan
✅ Konfigurasi disamakan dengan development
✅ Siap untuk rebuild dan testing

