# Sinkronisasi Konfigurasi Production dengan Development

## Perubahan yang Dilakukan

### 1. Health Check Configuration ✅
**Sebelum:**
- Endpoint: `/up`
- Start period: 60s

**Sesudah (disamakan dengan development):**
- Endpoint: `/` (root endpoint, sama seperti development)
- Start period: 40s (sama seperti development)
- Interval: 30s ✅
- Timeout: 10s ✅
- Retries: 3 ✅

### 2. Konfigurasi yang Tetap Sama (Sudah Benar)

#### Database (MariaDB)
- ✅ Image: `mariadb:11.2`
- ✅ Health check: `healthcheck.sh --connect --innodb_initialized`
- ✅ Interval: 10s
- ✅ Timeout: 5s
- ✅ Retries: 5
- ✅ Start period: 30s
- ✅ Character set: `utf8mb4_unicode_ci`

#### Redis
- ✅ Image: `redis:7-alpine`
- ✅ Health check: `redis-cli ping`
- ✅ Interval: 10s
- ✅ Timeout: 3s
- ✅ Retries: 5
- ✅ Command: `--appendonly yes --maxmemory 256mb --maxmemory-policy allkeys-lru`

#### Network
- ✅ Network name: `adojobs_network`
- ✅ Driver: `bridge`

#### Volumes
- ✅ `mariadb_data` - untuk database
- ✅ `redis_data` - untuk Redis
- ✅ `frankenphp_cache` - untuk FrankenPHP cache

### 3. Perbedaan yang Dipertahankan (Karena Production Requirements)

#### App Container
- **Development**: Full code mounting (`./src:/app`)
- **Production**: Hanya mount storage (`./src/storage/app:/app/storage/app`, `./src/storage/logs:/app/storage/logs`)
  - ✅ Ini benar karena kode sudah di-build ke dalam image

#### FrankenPHP Configuration
- **Development**: Tanpa worker mode (untuk hot reload)
- **Production**: Dengan worker mode (untuk performance)
  - ✅ Worker mode tetap dipertahankan untuk production performance

#### Environment Variables
- **Development**: `APP_ENV=local`, `APP_DEBUG=true`
- **Production**: `APP_ENV=production`, `APP_DEBUG=false`
  - ✅ Ini benar untuk security dan performance

#### Cache & Session Drivers
- **Development**: `CACHE_DRIVER=file`, `SESSION_DRIVER=file`
- **Production**: `CACHE_DRIVER=redis`, `SESSION_DRIVER=redis`
  - ✅ Redis lebih baik untuk production performance

#### Reverse Proxy
- **Development**: Tidak ada (langsung expose port 8282)
- **Production**: Caddy reverse proxy untuk SSL/TLS
  - ✅ Diperlukan untuk HTTPS di production

## Langkah Deployment di Production

Setelah perubahan ini, di production server:

```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Rebuild container dengan health check baru
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build app

# Monitor status
watch -n 2 'docker ps --filter "name=adojobs_app"'
```

## Verifikasi

Setelah rebuild, container `adojobs_app` seharusnya menjadi healthy dalam 40-70 detik (start_period 40s + beberapa retries jika perlu).

## Catatan Penting

1. **Health Check Endpoint**: Kembali menggunakan `/` (root) seperti development yang sudah terbukti bekerja
2. **Start Period**: Dikurangi dari 60s ke 40s untuk konsistensi dengan development
3. **Konfigurasi Lain**: Tetap dipertahankan sesuai kebutuhan production (worker mode, Redis, SSL, dll)

## Troubleshooting

Jika masih ada masalah setelah sinkronisasi:

1. **Cek logs**:
   ```bash
   docker logs adojobs_app --tail 100
   ```

2. **Test health check manual**:
   ```bash
   docker exec adojobs_app curl -f http://localhost:8080/
   ```

3. **Cek database connection**:
   ```bash
   docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo();"
   ```

4. **Cek Redis connection**:
   ```bash
   docker exec adojobs_redis redis-cli ping
   ```

5. **Gunakan script diagnosa**:
   ```bash
   ./check-app-health.sh
   ```

