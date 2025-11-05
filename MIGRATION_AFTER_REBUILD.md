# Apakah Perlu Migrate Setelah Rebuild Container?

## Jawaban Singkat

**TIDAK perlu migrate ulang** jika rebuild container saja, karena:
- Database volume tetap ada (tidak terhapus)
- Data database tetap utuh
- Migration sudah pernah dijalankan sebelumnya

**PERLU migrate** hanya jika:
- Database volume ikut dihapus (`docker-compose down -v`)
- Database container baru dibuat
- Database kosong/tidak ada tabel

## Cara Cek Apakah Perlu Migrate

### Opsi 1: Gunakan Script Check

```bash
cd /var/www/adojobs.id
./check-database-status.sh
```

Script ini akan cek:
1. ✅ Database connection
2. ✅ Apakah migrations table ada
3. ✅ Apakah tabel-tabel sudah ada
4. ✅ Migration status

### Opsi 2: Manual Check

```bash
# 1. Cek apakah database connect
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';"

# 2. Cek apakah migrations table ada
docker exec adojobs_app php artisan tinker --execute="DB::table('migrations')->count(); echo ' tables in migrations';"

# 3. Cek migration status
docker exec adojobs_app php artisan migrate:status

# 4. Cek apakah tabel penting ada (misalnya settings)
docker exec adojobs_app php artisan tinker --execute="DB::table('settings')->count(); echo ' settings found';"
```

## Kapan Perlu Migrate?

### ✅ TIDAK Perlu Migrate Jika:

1. **Rebuild container saja** (tanpa `-v` flag):
   ```bash
   docker-compose -f docker-compose.prod.yml build --no-cache app
   docker-compose -f docker-compose.prod.yml up -d
   ```
   Database volume tetap ada, data tetap utuh.

2. **Restart container**:
   ```bash
   docker-compose -f docker-compose.prod.yml restart app
   ```
   Tidak ada perubahan pada database.

3. **Update code/config saja**:
   ```bash
   git pull
   docker-compose -f docker-compose.prod.yml up -d --build app
   ```
   Database tidak terpengaruh.

### ❌ PERLU Migrate Jika:

1. **Rebuild dengan hapus volume**:
   ```bash
   docker-compose -f docker-compose.prod.yml down -v
   docker-compose -f docker-compose.prod.yml up -d
   ```
   Database volume terhapus, perlu migrate dan seed.

2. **Database container baru dibuat**:
   - Database kosong, tidak ada tabel
   - Perlu migrate dari awal

3. **Ada migration baru**:
   - Jika ada migration file baru di codebase
   - Perlu run `php artisan migrate` untuk apply migration baru

## Langkah Jika Perlu Migrate

```bash
cd /var/www/adojobs.id

# 1. Run migrations
docker exec adojobs_app php artisan migrate --force

# 2. Run seeders (jika diperlukan)
docker exec adojobs_app php artisan db:seed --force

# 3. Verify
docker exec adojobs_app php artisan migrate:status
```

## Verifikasi Setelah Rebuild

Untuk memastikan semuanya OK setelah rebuild:

```bash
# 1. Cek database connection
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';"

# 2. Cek apakah settings table ada (penting untuk aplikasi)
docker exec adojobs_app php artisan tinker --execute="echo DB::table('settings')->count() . ' settings found';"

# 3. Cek aplikasi bisa diakses
curl http://10.10.10.33/
```

## Troubleshooting

### Masalah: Database connection failed setelah rebuild

**Kemungkinan**: Database container tidak running atau volume terhapus.

**Solusi**:
```bash
# Cek database container
docker ps | grep adojobs_db

# Jika tidak running, start
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d db

# Tunggu database ready (30 detik)
sleep 30

# Test connection
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo();"
```

### Masalah: Migrations table tidak ada

**Kemungkinan**: Database volume terhapus atau database baru.

**Solusi**:
```bash
# Run migrations
docker exec adojobs_app php artisan migrate --force

# Run seeders
docker exec adojobs_app php artisan db:seed --force
```

### Masalah: Error "Base table or view not found"

**Kemungkinan**: Migration belum dijalankan atau tabel tidak ada.

**Solusi**:
```bash
# Check migration status
docker exec adojobs_app php artisan migrate:status

# Run pending migrations
docker exec adojobs_app php artisan migrate --force
```

## Catatan Penting

1. **Database Volume**: Volume `mariadb_data` menyimpan data database. Volume ini tidak terhapus saat rebuild container kecuali menggunakan `-v` flag.

2. **Migration Status**: Cek dengan `php artisan migrate:status` untuk melihat migration mana yang sudah dijalankan.

3. **Backup**: Sebelum rebuild dengan `-v`, pastikan backup database jika data penting.

4. **Force Flag**: Di production, gunakan `--force` flag untuk migrate/seeder karena `APP_ENV=production`.

## Kesimpulan

**Untuk rebuild container biasa (tanpa `-v`)**: 
- ✅ TIDAK perlu migrate
- Database volume tetap ada
- Data tetap utuh
- Cukup verify database connection

**Untuk rebuild dengan hapus volume (`-v`)**:
- ❌ PERLU migrate
- ❌ PERLU seed
- Database volume terhapus
- Data hilang (perlu backup dulu)

