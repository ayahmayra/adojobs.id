# 🔧 Solusi Masalah Deployment - AdoJobs.id

## 🚨 Masalah yang Anda Hadapi

```
✘ Container adojobs_db               Error
dependency failed to start: container adojobs_db is unhealthy
WARN[0000] The "DB_PASSWORD" variable is not set. Defaulting to a blank string.
```

## ✅ Solusi Cepat (5 Menit)

### **Option 1: Fix Otomatis (Recommended)**

```bash
# Jalankan script fix otomatis
chmod +x fix-deployment.sh
./fix-deployment.sh
```

Script ini akan:
1. ✅ Membuat `.env.production` jika belum ada
2. ✅ Memastikan password sudah di-set
3. ✅ Stop dan restart containers dengan benar
4. ✅ Menunggu database ready

### **Option 2: Fix Manual**

#### Step 1: Pastikan .env.production Ada

```bash
# Cek apakah file ada
ls -la .env.production

# Jika tidak ada, buat dari template
cp env.production.template .env.production

# Verifikasi isi file (pastikan ada password)
cat .env.production | grep DB_PASSWORD
# Harus menampilkan: DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y
```

#### Step 2: Stop Semua Containers

```bash
docker-compose -f docker-compose.prod.yml down
```

#### Step 3: Hapus Volume Database (Jika Perlu)

**⚠️ HATI-HATI:** Ini akan menghapus semua data database!

```bash
# Hanya jika benar-benar perlu fresh start
docker volume rm adojobsid_mariadb_data
```

#### Step 4: Start Database Dulu

```bash
# Start database container saja
docker-compose -f docker-compose.prod.yml up -d db

# Tunggu 30-60 detik untuk database initialize
sleep 30

# Cek status
docker-compose -f docker-compose.prod.yml ps db
# Harus menampilkan: "Up (healthy)"
```

#### Step 5: Start Semua Services

```bash
# Start semua containers
docker-compose -f docker-compose.prod.yml up -d

# Monitor logs
docker-compose -f docker-compose.prod.yml logs -f
```

#### Step 6: Verifikasi

```bash
# Cek status semua containers
docker-compose -f docker-compose.prod.yml ps

# Semua harus "Up" atau "Up (healthy)"
```

---

## 🔍 Troubleshooting Detail

### Masalah 1: .env.production Tidak Terbaca

**Gejala:**
```
WARN[0000] The "DB_PASSWORD" variable is not set.
```

**Penyebab:**
- File `.env.production` tidak ada di root directory
- File ada tapi tidak readable
- Ada spasi/tab di awal baris

**Solusi:**

```bash
# 1. Pastikan file ada di root (sama dengan docker-compose.prod.yml)
pwd
# Harus di: /var/www/adojobs.id atau /opt/adojobs

ls -la .env.production
# Harus menampilkan file

# 2. Cek permission
chmod 644 .env.production

# 3. Verifikasi format (tidak ada spasi di awal)
cat .env.production | head -5
# Harus:
# DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y
# BUKAN:
#   DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y

# 4. Jika ada masalah format, buat ulang
cp env.production.template .env.production
```

### Masalah 2: Database Container Unhealthy

**Gejala:**
```
✘ Container adojobs_db               Error
dependency failed to start: container adojobs_db is unhealthy
```

**Penyebab:**
- Password kosong
- Volume database corrupt
- MySQL config file tidak ada
- Database tidak sempat initialize

**Solusi:**

```bash
# 1. Cek logs database
docker-compose -f docker-compose.prod.yml logs db --tail=50

# 2. Cek apakah password sudah di-set
cat .env.production | grep DB_PASSWORD
cat .env.production | grep DB_ROOT_PASSWORD

# 3. Jika password kosong, update:
nano .env.production
# Pastikan:
# DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y
# DB_ROOT_PASSWORD=Gb43QutnerQYnEvB8Yc2y3nPccEI7LcI

# 4. Hapus volume dan start ulang (HATI-HATI: hapus data!)
docker-compose -f docker-compose.prod.yml down
docker volume rm adojobsid_mariadb_data
docker-compose -f docker-compose.prod.yml up -d db

# 5. Tunggu lebih lama (60 detik)
sleep 60
docker-compose -f docker-compose.prod.yml ps db
```

### Masalah 3: MySQL Config File Missing

**Cek:**
```bash
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

---

## 🎯 Complete Fix Workflow

```bash
# 1. Masuk ke directory project
cd /var/www/adojobs.id
# atau
cd /opt/adojobs

# 2. Pastikan .env.production ada dan benar
cp env.production.template .env.production
cat .env.production | grep DB_PASSWORD
# Harus menampilkan password

# 3. Stop semua
docker-compose -f docker-compose.prod.yml down

# 4. Hapus volume database (jika perlu fresh start)
docker volume rm adojobsid_mariadb_data

# 5. Start database dulu
docker-compose -f docker-compose.prod.yml up -d db

# 6. Tunggu 60 detik
echo "Waiting for database..."
sleep 60

# 7. Cek database healthy
docker-compose -f docker-compose.prod.yml ps db

# 8. Start semua
docker-compose -f docker-compose.prod.yml up -d

# 9. Monitor logs
docker-compose -f docker-compose.prod.yml logs -f
```

---

## 📋 Verification Commands

Setelah fix, verifikasi dengan:

```bash
# 1. Semua containers running
docker-compose -f docker-compose.prod.yml ps
# Semua harus "Up" atau "Up (healthy)"

# 2. Environment variables ter-load
docker-compose -f docker-compose.prod.yml exec app env | grep DB_
# Harus menampilkan:
# DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y
# DB_DATABASE=adojobs_prod
# DB_USERNAME=adojobs_user

# 3. Test database connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
# Lalu ketik: DB::connection()->getPdo();
# Harus return PDO object tanpa error

# 4. Test aplikasi
curl http://10.10.10.33
# Atau buka browser: http://10.10.10.33
```

---

## 🆘 Jika Masih Bermasalah

### Run Troubleshooting Script

```bash
./troubleshoot-deployment.sh
```

### Check Logs Detail

```bash
# Database logs
docker-compose -f docker-compose.prod.yml logs db --tail=100

# App logs
docker-compose -f docker-compose.prod.yml logs app --tail=100

# All logs
docker-compose -f docker-compose.prod.yml logs --tail=100
```

### Contact Support

Jika masih bermasalah, siapkan informasi berikut:
1. Output dari: `docker-compose -f docker-compose.prod.yml ps`
2. Output dari: `docker-compose -f docker-compose.prod.yml logs db --tail=50`
3. Output dari: `cat .env.production | grep DB_`
4. Output dari: `ls -la docker/mysql/my.cnf`

---

## ✅ Quick Checklist

Sebelum menjalankan `docker-compose up`:

- [ ] `.env.production` ada di root directory
- [ ] `.env.production` berisi `DB_PASSWORD=Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y`
- [ ] `.env.production` berisi `DB_ROOT_PASSWORD=Gb43QutnerQYnEvB8Yc2y3nPccEI7LcI`
- [ ] `docker/mysql/my.cnf` ada
- [ ] Tidak ada container yang conflict
- [ ] Docker daemon running

---

**Good luck! 🚀**

