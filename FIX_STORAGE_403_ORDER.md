# Fix 403 Forbidden - Directive Order Issue

## Masalah

Masih mendapatkan error 403 Forbidden untuk file di `/storage/*` meskipun sudah menghapus `/storage/*` dari `@disallowed`.

## Root Cause

**Urutan directive di Caddyfile salah!**

Dalam Caddy, directive dievaluasi dalam urutan mereka muncul. Jika `respond @disallowed 403` muncul **sebelum** `file_server`, maka request akan di-block sebelum `file_server` bisa melayani file.

**Sebelum (SALAH):**
```caddy
@disallowed {
    path /bootstrap/cache/* /vendor/*
}
respond @disallowed 403  # ← Ini dievaluasi dulu

try_files {path} {path}/ /index.php?{query}
file_server  # ← Ini tidak pernah tercapai untuk /storage/*
```

**Sesudah (BENAR):**
```caddy
try_files {path} {path}/ /index.php?{query}
file_server  # ← Ini dievaluasi dulu, file dilayani

@disallowed {
    path /bootstrap/cache/* /vendor/*
}
respond @disallowed 403  # ← Hanya untuk file yang tidak ada
```

## Solusi

### Langkah 1: Gunakan Script Fix (Recommended)

```bash
cd /var/www/adojobs.id
./fix-storage-403-rebuild.sh
```

**PENTING:** Script ini akan rebuild container dengan `--no-cache` untuk memastikan Caddyfile.prod digunakan, bukan Caddyfile.dev.

Script ini akan:
1. ✅ Pull perubahan terbaru (Caddyfile dengan urutan yang benar)
2. ✅ Rebuild app container dengan `--no-cache` (pastikan perubahan diterapkan)
3. ✅ Restart app container
4. ✅ Verify app health

### Langkah 2: Manual Fix

```bash
cd /var/www/adojobs.id

# 1. Pull changes
sudo git pull origin main

# 2. Rebuild dengan --no-cache (penting!)
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app

# 3. Restart app
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app

# 4. Tunggu 15 detik
sleep 15

# 5. Verify
docker ps --format "{{.Names}} {{.Status}}" | grep adojobs_app
```

### Langkah 3: Verifikasi Caddyfile di Container

Pastikan Caddyfile di dalam container sudah benar:

```bash
# Cek Caddyfile di container
docker exec adojobs_app cat /etc/caddy/Caddyfile | grep -A 10 "file_server"

# Harus muncul:
# try_files {path} {path}/ /index.php?{query}
# file_server
# 
# @blocked {
#     ...
# }
# respond @blocked 403
```

## Perubahan yang Dilakukan

**File: `docker/frankenphp/Caddyfile.prod`**

**Sebelum:**
```caddy
@blocked {
    path *.env* *.log *.sql *.sqlite .git/* .gitignore .gitattributes
}
respond @blocked 403

@disallowed {
    path /bootstrap/cache/* /vendor/*
}
respond @disallowed 403

try_files {path} {path}/ /index.php?{query}
file_server
```

**Sesudah:**
```caddy
try_files {path} {path}/ /index.php?{query}
file_server

@blocked {
    path *.env* *.log *.sql *.sqlite .git/* .gitignore .gitattributes
}
respond @blocked 403

@disallowed {
    path /bootstrap/cache/* /vendor/*
}
respond @disallowed 403
```

## Verifikasi

Setelah rebuild, test:

```bash
# Test dengan file yang ada
curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png

# Harus return 200 OK, bukan 403
```

## Catatan Penting

1. **Urutan directive penting di Caddy** - `file_server` harus muncul sebelum blocking rules
2. **Rebuild dengan `--no-cache`** - Pastikan perubahan diterapkan dengan benar
3. **Verifikasi Caddyfile di container** - Pastikan file di container sudah benar

## Troubleshooting

### Masalah: Masih 403 Setelah Rebuild

**Kemungkinan:**
- Rebuild tidak menggunakan `--no-cache`
- Caddyfile di container masih lama

**Solusi:**
```bash
# Rebuild dengan --no-cache
docker-compose -f docker-compose.prod.yml --env-file .env.production build --no-cache app

# Restart
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d app

# Verifikasi Caddyfile
docker exec adojobs_app cat /etc/caddy/Caddyfile | head -70
```

### Masalah: File Tidak Ditemukan (404)

**Kemungkinan:**
- Storage link tidak ada atau broken
- File tidak ada di storage

**Solusi:**
```bash
# Cek storage link
docker exec adojobs_app ls -la /app/public/ | grep storage

# Cek file
docker exec adojobs_app ls -la /app/storage/app/public/settings/
```

## Summary

✅ **Urutan directive diperbaiki** - `file_server` sekarang muncul sebelum blocking rules
✅ **Rebuild dengan `--no-cache`** - Memastikan perubahan diterapkan
✅ **Verifikasi Caddyfile** - Pastikan file di container sudah benar

Setelah rebuild, file di `/storage/*` seharusnya bisa diakses tanpa error 403.

