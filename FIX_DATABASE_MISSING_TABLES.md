# Perbaikan Error: Table 'settings' doesn't exist

## Masalah
Error 500 dengan pesan:
```
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'adojobs_prod.settings' doesn't exist
```

## Root Cause
Database belum di-migrate atau seeder belum dijalankan di production server.

## Solusi

### Opsi 1: Menggunakan Script Otomatis (Recommended)

```bash
cd /var/www/adojobs.id
./fix-database-production.sh
```

Script ini akan:
1. ✅ Check database connection
2. ✅ Run migrations
3. ✅ Run seeders
4. ✅ Clear caches
5. ✅ Optimize for production
6. ✅ Test application

### Opsi 2: Manual Steps

```bash
cd /var/www/adojobs.id

# 1. Check database connection
docker exec adojobs_app php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database OK';"

# 2. Run migrations
docker exec adojobs_app php artisan migrate --force

# 3. Run seeders
docker exec adojobs_app php artisan db:seed --force

# 4. Clear caches
docker exec adojobs_app php artisan config:clear
docker exec adojobs_app php artisan cache:clear
docker exec adojobs_app php artisan route:clear
docker exec adojobs_app php artisan view:clear

# 5. Optimize for production
docker exec adojobs_app php artisan config:cache
docker exec adojobs_app php artisan route:cache
docker exec adojobs_app php artisan view:cache

# 6. Test application
docker exec adojobs_app curl -f http://localhost:8080/
```

## Verifikasi

Setelah menjalankan script, cek:

1. **Application responds:**
   ```bash
   docker exec adojobs_app curl -f http://localhost:8080/
   ```
   Harus mengembalikan HTML response (200 OK)

2. **Check from browser/IP:**
   ```bash
   curl http://10.10.10.33/
   ```
   Harus mengembalikan halaman homepage

3. **Check database tables:**
   ```bash
   docker exec adojobs_app php artisan tinker --execute="echo DB::table('settings')->count() . ' settings found';"
   ```

4. **Check logs (no errors):**
   ```bash
   docker logs adojobs_app --tail 20
   ```

## Catatan Penting

1. **Migrations**: `--force` flag diperlukan karena `APP_ENV=production`
2. **Seeders**: `--force` flag diperlukan untuk production
3. **Caches**: Harus di-clear setelah migrations/seeders untuk memastikan config terbaru ter-load
4. **Optimization**: Setelah clear, rebuild caches untuk production performance

## Troubleshooting

Jika masih ada error setelah menjalankan script:

1. **Check migration status:**
   ```bash
   docker exec adojobs_app php artisan migrate:status
   ```

2. **Check specific table:**
   ```bash
   docker exec adojobs_app php artisan tinker --execute="DB::select('SHOW TABLES');"
   ```

3. **Check seeder output:**
   ```bash
   docker exec adojobs_app php artisan db:seed --force --verbose
   ```

4. **Check Laravel logs:**
   ```bash
   docker exec adojobs_app tail -50 /app/storage/logs/laravel.log
   ```

## Status

✅ Root cause identified: Missing database tables
✅ Solution provided: Migration and seeding script
✅ Ready to run

