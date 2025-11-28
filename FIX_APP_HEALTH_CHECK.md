# Perbaikan Health Check App Container

## Masalah
Container `adojobs_app` menunjukkan status "unhealthy" di production server.

## Penyebab
Health check sebelumnya menggunakan endpoint root `/` yang mungkin membutuhkan waktu lebih lama untuk merespons. Laravel sudah menyediakan endpoint `/up` yang lebih ringan untuk health check.

## Solusi

### 1. Update Health Check Configuration

Health check sudah diperbaiki untuk menggunakan endpoint `/up`:
- ✅ `docker-compose.prod.yml` - menggunakan `/up` endpoint
- ✅ `Dockerfile` - menggunakan `/up` endpoint
- ✅ `start_period` ditingkatkan menjadi 60 detik (memberikan waktu lebih untuk aplikasi initialize)

### 2. Di Production Server

**Opsi A: Rebuild Container (Recommended)**
```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Rebuild container dengan health check baru
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d --build app

# Monitor status
docker ps --filter "name=adojobs_app"
```

**Opsi B: Restart Container (Quick Fix)**
```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Restart container (akan menggunakan health check baru jika sudah di-rebuild)
docker-compose -f docker-compose.prod.yml --env-file .env.production restart app

# Monitor status
watch -n 2 'docker ps --filter "name=adojobs_app"'
```

**Opsi C: Manual Health Check Test**
```bash
# Test endpoint secara manual
docker exec adojobs_app curl -f http://localhost:8080/up

# Jika berhasil, output akan menunjukkan JSON response
# Jika gagal, cek log untuk error
docker logs adojobs_app --tail 50
```

### 3. Verifikasi

Setelah restart/rebuild, tunggu sekitar 60-90 detik, lalu cek status:

```bash
docker ps --filter "name=adojobs_app"
```

Status harus berubah dari `(unhealthy)` menjadi `(healthy)`.

### 4. Troubleshooting

Jika masih unhealthy setelah 2-3 menit:

**A. Cek Logs**
```bash
docker logs adojobs_app --tail 100
```

**B. Cek Database Connection**
```bash
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo();"
```

**C. Cek Redis Connection**
```bash
docker exec adojobs_app php artisan tinker --execute="Redis::connection()->ping();"
```

**D. Cek Environment Variables**
```bash
docker exec adojobs_app env | grep -E "APP_ENV|APP_DEBUG|DB_HOST|REDIS_HOST"
```

**E. Test Health Endpoint Manually**
```bash
# Di dalam container
docker exec adojobs_app curl -v http://localhost:8080/up

# Dari luar (via proxy)
curl -v http://10.10.10.33/up
```

### 5. Script Otomatis

Gunakan script `check-app-health.sh` untuk diagnosa lengkap:

```bash
cd /var/www/adojobs.id
./check-app-health.sh
```

## Catatan Penting

1. **Start Period**: Health check membutuhkan waktu 60 detik untuk mulai (`start_period: 60s`). Ini normal dan memberikan waktu untuk aplikasi Laravel untuk fully initialize.

2. **Endpoint `/up`**: Endpoint ini lebih ringan daripada root `/` karena tidak memuat full page, hanya melakukan basic checks (database, cache, dll).

3. **Monitoring**: Setelah restart, monitor container selama 2-3 menit untuk memastikan health check berhasil.

## Perubahan yang Dilakukan

1. ✅ Health check endpoint diubah dari `/` ke `/up`
2. ✅ Timeout ditingkatkan dari 3s ke 10s
3. ✅ Start period ditingkatkan dari 40s ke 60s
4. ✅ Retries tetap 3 (cukup untuk transient errors)

Setelah perubahan ini diterapkan, container `adojobs_app` seharusnya menjadi healthy dalam 1-2 menit setelah restart.

