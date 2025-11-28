# 🔧 Fix Deployment Issues - AdoJobs.id

## Problem: Database Container Unhealthy & Environment Variables Not Set

### ✅ Quick Fix

```bash
# 1. Pastikan .env.production ada
cp env.production.template .env.production

# 2. Verifikasi file berisi password
cat .env.production | grep DB_PASSWORD
# Harus menampilkan: DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y

# 3. Stop semua containers
docker-compose -f docker-compose.prod.yml down

# 4. Cek logs database (jika ada)
docker-compose -f docker-compose.prod.yml logs db

# 5. Hapus volume database jika perlu (HATI-HATI: ini akan menghapus data!)
# docker volume rm adojobsid_mariadb_data

# 6. Start ulang
docker-compose -f docker-compose.prod.yml up -d

# 7. Monitor logs
docker-compose -f docker-compose.prod.yml logs -f db
```

## 🔍 Detailed Troubleshooting

### Issue 1: Environment Variables Not Set

**Error:**
```
WARN[0000] The "APP_KEY" variable is not set. Defaulting to a blank string.
WARN[0000] The "DB_PASSWORD" variable is not set. Defaulting to a blank string.
```

**Solution:**

```bash
# 1. Pastikan .env.production ada di root directory
ls -la .env.production

# 2. Jika tidak ada, copy dari template
cp env.production.template .env.production

# 3. Verifikasi isi file
cat .env.production

# 4. Pastikan tidak ada spasi di awal baris
# File harus dimulai langsung dengan key, contoh:
# DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y
# BUKAN:
#   DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y
```

### Issue 2: Database Container Unhealthy

**Error:**
```
✘ Container adojobs_db               Error
dependency failed to start: container adojobs_db is unhealthy
```

**Solution:**

```bash
# 1. Cek logs database
docker-compose -f docker-compose.prod.yml logs db

# 2. Cek apakah database container running
docker-compose -f docker-compose.prod.yml ps db

# 3. Jika container tidak start, cek:
#    a. Apakah .env.production ada?
#    b. Apakah DB_PASSWORD dan DB_ROOT_PASSWORD sudah di-set?
#    c. Apakah ada conflict dengan volume lama?

# 4. Stop semua dan hapus volume database (jika perlu fresh start)
docker-compose -f docker-compose.prod.yml down
docker volume rm adojobsid_mariadb_data

# 5. Start ulang
docker-compose -f docker-compose.prod.yml up -d db

# 6. Tunggu 30-60 detik untuk database initialize
sleep 30

# 7. Cek status
docker-compose -f docker-compose.prod.yml ps db
```

### Issue 3: MySQL Config File Missing

**Check:**
```bash
# Cek apakah MySQL config file ada
ls -la docker/mysql/my.cnf
```

**Jika tidak ada, buat:**
```bash
mkdir -p docker/mysql
cat > docker/mysql/my.cnf << 'EOF'
[mysqld]
character-set-server=utf8mb4
collation-server=utf8mb4_unicode_ci
default-time-zone='+07:00'
max_connections=200
innodb_buffer_pool_size=256M
EOF
```

## 🔄 Complete Fresh Start

Jika semua cara di atas tidak berhasil, lakukan fresh start:

```bash
# 1. Stop semua containers
docker-compose -f docker-compose.prod.yml down

# 2. Hapus semua volumes (HATI-HATI: ini menghapus semua data!)
docker volume rm adojobsid_mariadb_data adojobsid_redis_data adojobsid_frankenphp_cache adojobsid_caddy_data adojobsid_caddy_config

# 3. Pastikan .env.production ada dan benar
cp env.production.template .env.production
cat .env.production | grep -E "^DB_|^APP_"

# 4. Rebuild dan start
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d

# 5. Monitor logs
docker-compose -f docker-compose.prod.yml logs -f
```

## 🛠️ Run Troubleshooting Script

```bash
# Jalankan script troubleshooting otomatis
./troubleshoot-deployment.sh
```

Script ini akan:
- ✅ Cek apakah .env.production ada
- ✅ Verifikasi environment variables
- ✅ Cek status database container
- ✅ Cek logs database
- ✅ Berikan rekomendasi perbaikan

## 📋 Verification Checklist

Setelah fix, verifikasi:

```bash
# 1. Semua containers running
docker-compose -f docker-compose.prod.yml ps
# Semua harus "Up" atau "Up (healthy)"

# 2. Database healthy
docker-compose -f docker-compose.prod.yml ps db
# Harus menampilkan: "Up (healthy)"

# 3. Test connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Lalu ketik: DB::connection()->getPdo();
# Harus return PDO object tanpa error

# 4. Environment variables ter-load
docker-compose -f docker-compose.prod.yml exec app env | grep DB_
# Harus menampilkan DB_PASSWORD dan DB_DATABASE dengan nilai yang benar
```

## 🎯 Most Common Solution

**99% masalah ini disebabkan oleh:**

1. **`.env.production` tidak ada** → Fix: `cp env.production.template .env.production`
2. **`.env.production` ada tapi kosong/placeholder** → Fix: Verifikasi file berisi password yang benar
3. **Volume database corrupt** → Fix: Hapus volume dan start ulang

**Quick fix command:**
```bash
cp env.production.template .env.production && \
docker-compose -f docker-compose.prod.yml down && \
docker-compose -f docker-compose.prod.yml up -d && \
docker-compose -f docker-compose.prod.yml logs -f db
```

