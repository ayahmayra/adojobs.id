# Fix 403 Forbidden - Cloudflare Cache Issue

## Masalah

Caddyfile sudah diperbaiki dan `file_server` sudah muncul sebelum blocking rules, tetapi masih mendapatkan error 403 Forbidden.

**Analisis Response Headers:**
```
HTTP/2 403
cf-cache-status: HIT
age: 2493
```

**Artinya:**
- `cf-cache-status: HIT` — Cloudflare serving dari cache, bukan dari origin
- `age: 2493` — Response ini sudah di-cache selama 2493 detik (41 menit)

**Root Cause:** Cloudflare masih serving cached 403 response dari sebelum aplikasi diperbaiki.

## Verifikasi Masalah

### Step 0: Check Permissions First (IMPORTANT!)

**Sebelum purge Cloudflare cache, cek ownership dan permissions dulu:**

```bash
cd /var/www/adojobs.id
./check-storage-permissions.sh
```

**Expected ownership:**
- `/app/public`: `www-data:www-data`
- `/app/public/storage` (symlink): `www-data:www-data`
- `/app/storage/app/public`: `www-data:www-data`
- All files: `www-data:www-data`

**Jika ada yang owner-nya `root`:**
```bash
# Fix all permissions and ownership
./fix-all-storage-permissions.sh
```

### Test 1: Bypass Cloudflare (Direct IP)

```bash
cd /var/www/adojobs.id
./fix-cloudflare-cache.sh
```

**Atau manual:**

```bash
# Test direct IP (bypass Cloudflare)
curl -I http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png

# Harus return 200 OK (jika aplikasi sudah diperbaiki)
```

### Test 2: Test dengan Host Header

```bash
# Test dengan Host header (simulate domain access)
curl -I -H "Host: adojobs.id" http://10.10.10.33/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png

# Harus return 200 OK
```

### Analisis Hasil

**Jika test direct IP return 200 OK:**
- ✅ Aplikasi sudah diperbaiki
- ❌ Cloudflare cache masih serving 403 lama
- 🔥 **Solusi: Purge Cloudflare cache**

**Jika test direct IP return 403:**
- ❌ Aplikasi masih bermasalah
- 🔍 Perlu investigasi lebih lanjut

## Solusi: Purge Cloudflare Cache

### Opsi 1: Via Cloudflare Dashboard (Paling Mudah)

1. Login ke **Cloudflare Dashboard**: https://dash.cloudflare.com/
2. Pilih domain **adojobs.id**
3. Go to **Caching** > **Configuration**
4. Click **"Purge Everything"** (purge semua cache)
   
   **Atau** untuk purge spesifik:
   - Click **"Custom Purge"**
   - Pilih **"Purge by URL"**
   - Masukkan:
     ```
     https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png
     ```
   - Click **"Purge"**

5. Tunggu 10-30 detik untuk cache terpurge
6. Test ulang: `curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png`

### Opsi 2: Via Cloudflare API

**Get Zone ID:**
```bash
# Login to Cloudflare dashboard, Zone ID ada di Overview
# Atau via API:
curl -X GET "https://api.cloudflare.com/client/v4/zones?name=adojobs.id" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json"
```

**Purge Everything:**
```bash
curl -X POST "https://api.cloudflare.com/client/v4/zones/YOUR_ZONE_ID/purge_cache" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  --data '{"purge_everything":true}'
```

**Purge Specific Files:**
```bash
curl -X POST "https://api.cloudflare.com/client/v4/zones/YOUR_ZONE_ID/purge_cache" \
  -H "Authorization: Bearer YOUR_API_TOKEN" \
  -H "Content-Type: application/json" \
  --data '{
    "files": [
      "https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png",
      "https://adojobs.id/storage/settings/Vsaz7qkaKfBedLixA604ASSaOK5a1QrlSm2O8zhH.png",
      "https://adojobs.id/storage/settings/bbzTHRsxgkFLRxsikKAWql2T36dmyEqlaT812ccw.png"
    ]
  }'
```

### Opsi 3: Bypass Cache Sementara (Testing)

```bash
# Test dengan bypass cache (add cache buster)
curl -I "https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png?nocache=$(date +%s)"

# Atau gunakan Cloudflare bypass header
curl -I -H "Cache-Control: no-cache" -H "Pragma: no-cache" \
  "https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png"
```

## Verifikasi Setelah Purge

```bash
# 1. Test via domain (should return 200 OK now)
curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png

# 2. Check cache status (should be MISS or EXPIRED, not HIT)
curl -I https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png | grep -i "cf-cache-status"

# 3. Test from browser
# Open: https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png
# Should display image, not 403 error
```

## Prevent Future Cache Issues

### Opsi 1: Exclude Storage from Cloudflare Cache

**Via Cloudflare Dashboard:**
1. Go to **Caching** > **Configuration**
2. Scroll to **"Cache Rules"**
3. Create new rule:
   - **If**: URL Path contains `/storage/`
   - **Then**: Bypass Cache
   - Click **"Save"**

### Opsi 2: Add Cache-Control Headers

**In `docker/frankenphp/Caddyfile.prod`** (already set):
```caddy
@images {
    path *.jpg *.jpeg *.png *.gif *.ico *.svg *.webp
}
header @images Cache-Control "public, max-age=86400"
```

This tells Cloudflare to cache for 24 hours (86400 seconds).

### Opsi 3: Use Development Mode

**Temporary solution for testing:**
1. Go to **Overview** in Cloudflare dashboard
2. Toggle **"Development Mode"** to ON
3. This bypasses cache for 3 hours
4. Test without cache interference

## Summary

✅ **Aplikasi sudah diperbaiki** - Caddyfile sudah benar
❌ **Cloudflare cache masih serving 403 lama** - Perlu di-purge
🔥 **Solusi:** Purge Cloudflare cache via dashboard atau API

Setelah purge cache, file di `/storage/*` seharusnya bisa diakses tanpa error 403.

## Troubleshooting

### Masalah: Setelah purge masih 403

**Kemungkinan:**
- Browser cache masih menyimpan response lama
- Cloudflare purge belum selesai

**Solusi:**
```bash
# 1. Hard refresh browser (Ctrl+Shift+R atau Cmd+Shift+R)
# 2. Test dengan incognito/private mode
# 3. Test dengan curl (bypass browser cache)
curl -I "https://adojobs.id/storage/settings/RLsXEmSVIae6OnhQ841nxfTKuUU0N6IDx8Xx50m8.png?t=$(date +%s)"
```

### Masalah: Purge via API gagal

**Kemungkinan:**
- API token tidak punya permission
- Zone ID salah

**Solusi:**
1. Create new API token dengan permission: **Zone > Cache Purge > Purge**
2. Verify Zone ID: https://dash.cloudflare.com/ > Overview

