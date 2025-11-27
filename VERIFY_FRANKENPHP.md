# Verifikasi FrankenPHP - AdoJobs.id

Panduan lengkap untuk memverifikasi bahwa sistem AdoJobs.id menggunakan FrankenPHP sebagai web server.

---

## 🔍 Metode Verifikasi

### 1. **Cek Dockerfile**

File: `Dockerfile`

```dockerfile
# Multi-stage Dockerfile for Laravel with FrankenPHP

# Stage 1: Base image with FrankenPHP
FROM dunglas/frankenphp:latest-php8.3 AS base
```

✅ **Bukti:** Menggunakan base image `dunglas/frankenphp:latest-php8.3`

---

### 2. **Cek Caddyfile Configuration**

File: `docker/frankenphp/Caddyfile`

```caddy
{
    frankenphp {
        # Worker configuration
        num_threads 8
        worker {
            file /app/public/index.php
            num 2
        }
    }
    order php_server before file_server
}

:8080 {
    root * /app/public
    
    # PHP FrankenPHP with worker mode
    php_server
    
    # ... rest of configuration
}
```

✅ **Bukti:** 
- Konfigurasi `frankenphp` block
- Worker mode enabled dengan 2 workers
- Directive `php_server` (FrankenPHP-specific)

---

### 3. **Cek Docker Compose**

File: `docker-compose.yml`

```bash
cat docker-compose.yml | grep -A 10 "app:"
```

Atau lihat langsung:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: development
    # ... menggunakan Dockerfile yang berisi FrankenPHP
```

---

### 4. **Verifikasi di Container yang Berjalan**

#### A. Cek Process yang Berjalan

```bash
# Masuk ke container
docker-compose exec app sh

# Cek process
ps aux | grep frankenphp
```

**Expected output:**
```
/usr/local/bin/frankenphp run --config /etc/caddy/Caddyfile
```

#### B. Cek Binary FrankenPHP

```bash
# Di dalam container
which frankenphp
# Output: /usr/local/bin/frankenphp

# Cek versi
frankenphp version
# Output: FrankenPHP vX.X.X
```

#### C. Cek PHP SAPI

```bash
# Di dalam container
php -r "echo php_sapi_name();"
# Output: frankenphp atau cli-server
```

#### D. Cek Server Headers

```bash
# Dari host machine
curl -I http://localhost:8282
```

**Expected headers:**
```
HTTP/2 200
server: Caddy
# Note: Server header mungkin hidden untuk security
```

---

### 5. **Cek Response Headers via Browser**

1. Buka browser
2. Akses `http://localhost:8282`
3. Buka Developer Tools (F12)
4. Tab **Network**
5. Refresh halaman
6. Klik request pertama
7. Lihat **Response Headers**

**Yang harus dicari:**
- `Server: Caddy` (FrankenPHP menggunakan Caddy)
- HTTP/2 support (jika HTTPS)
- Fast response time (worker mode)

---

### 6. **Cek Logs**

```bash
# Lihat logs container
docker-compose logs app | grep -i frankenphp
```

**Expected output:**
```
app_1  | Starting FrankenPHP...
app_1  | FrankenPHP started successfully
```

---

### 7. **Verifikasi Worker Mode**

FrankenPHP worker mode adalah fitur unik yang membedakannya dari PHP-FPM.

#### A. Cek Konfigurasi Worker

File: `docker/frankenphp/Caddyfile`

```caddy
frankenphp {
    worker {
        file /app/public/index.php
        num 2  # 2 worker processes
    }
}
```

#### B. Test Performance (Worker Mode vs Non-Worker)

```bash
# Install Apache Bench
sudo apt-get install apache2-utils

# Test dengan worker mode (current setup)
ab -n 1000 -c 10 http://localhost:8282/

# Hasil akan menunjukkan:
# - Requests per second: ~500-1000 (dengan worker)
# - vs ~100-200 (tanpa worker)
```

---

### 8. **Cek PHP Extensions (FrankenPHP-specific)**

```bash
# Masuk ke container
docker-compose exec app sh

# Cek loaded extensions
php -m

# Cek apakah ada Caddy/FrankenPHP modules
php -i | grep -i frankenphp
```

---

### 9. **Verifikasi HTTP/2 Support**

FrankenPHP mendukung HTTP/2 out-of-the-box.

```bash
# Test HTTP/2 support
curl -I --http2 http://localhost:8282

# Atau gunakan online tool:
# https://tools.keycdn.com/http2-test
```

---

### 10. **Bandingkan dengan PHP-FPM**

Jika Anda ingin membandingkan, berikut perbedaan utama:

| Feature | FrankenPHP | PHP-FPM + Nginx |
|---------|------------|-----------------|
| **Process** | `frankenphp` | `php-fpm` + `nginx` |
| **Config File** | `Caddyfile` | `nginx.conf` + `php-fpm.conf` |
| **Worker Mode** | ✅ Built-in | ❌ Perlu Swoole/RoadRunner |
| **HTTP/2** | ✅ Native | ✅ Via Nginx |
| **HTTPS** | ✅ Auto (Let's Encrypt) | ⚠️ Manual setup |
| **Memory** | ~50-100MB | ~100-200MB |

---

## 🧪 Script Verifikasi Otomatis

Buat file `verify-frankenphp.sh`:

```bash
#!/bin/bash

echo "==================================="
echo "FrankenPHP Verification Script"
echo "==================================="
echo ""

# 1. Check Dockerfile
echo "1. Checking Dockerfile..."
if grep -q "dunglas/frankenphp" Dockerfile; then
    echo "   ✅ Dockerfile uses FrankenPHP base image"
else
    echo "   ❌ FrankenPHP not found in Dockerfile"
fi
echo ""

# 2. Check Caddyfile
echo "2. Checking Caddyfile..."
if [ -f "docker/frankenphp/Caddyfile" ]; then
    echo "   ✅ Caddyfile exists"
    if grep -q "frankenphp {" docker/frankenphp/Caddyfile; then
        echo "   ✅ FrankenPHP configuration found"
    fi
    if grep -q "worker {" docker/frankenphp/Caddyfile; then
        echo "   ✅ Worker mode enabled"
    fi
else
    echo "   ❌ Caddyfile not found"
fi
echo ""

# 3. Check if container is running
echo "3. Checking running container..."
if docker-compose ps | grep -q "app.*Up"; then
    echo "   ✅ App container is running"
    
    # Check process inside container
    echo "   Checking FrankenPHP process..."
    if docker-compose exec -T app ps aux | grep -q frankenphp; then
        echo "   ✅ FrankenPHP process is running"
    else
        echo "   ❌ FrankenPHP process not found"
    fi
else
    echo "   ⚠️  App container is not running"
    echo "   Run: docker-compose up -d"
fi
echo ""

# 4. Check FrankenPHP binary
echo "4. Checking FrankenPHP binary..."
if docker-compose exec -T app which frankenphp > /dev/null 2>&1; then
    echo "   ✅ FrankenPHP binary found"
    VERSION=$(docker-compose exec -T app frankenphp version 2>/dev/null | head -n 1)
    echo "   Version: $VERSION"
else
    echo "   ❌ FrankenPHP binary not found"
fi
echo ""

# 5. Test HTTP response
echo "5. Testing HTTP response..."
if curl -s -o /dev/null -w "%{http_code}" http://localhost:8282 | grep -q "200\|302"; then
    echo "   ✅ Application is responding"
    
    # Check server header
    SERVER_HEADER=$(curl -s -I http://localhost:8282 | grep -i "server:" | cut -d' ' -f2-)
    if [ ! -z "$SERVER_HEADER" ]; then
        echo "   Server: $SERVER_HEADER"
    fi
else
    echo "   ❌ Application is not responding"
fi
echo ""

echo "==================================="
echo "Verification Complete!"
echo "==================================="
```

**Cara menggunakan:**

```bash
# Buat file executable
chmod +x verify-frankenphp.sh

# Jalankan
./verify-frankenphp.sh
```

---

## 📊 Expected Output

Jika sistem menggunakan FrankenPHP dengan benar, Anda akan melihat:

```
===================================
FrankenPHP Verification Script
===================================

1. Checking Dockerfile...
   ✅ Dockerfile uses FrankenPHP base image

2. Checking Caddyfile...
   ✅ Caddyfile exists
   ✅ FrankenPHP configuration found
   ✅ Worker mode enabled

3. Checking running container...
   ✅ App container is running
   Checking FrankenPHP process...
   ✅ FrankenPHP process is running

4. Checking FrankenPHP binary...
   ✅ FrankenPHP binary found
   Version: FrankenPHP v1.x.x

5. Testing HTTP response...
   ✅ Application is responding
   Server: Caddy

===================================
Verification Complete!
===================================
```

---

## 🔧 Troubleshooting

### FrankenPHP Process Not Found

```bash
# Restart container
docker-compose restart app

# Check logs
docker-compose logs app --tail=50
```

### Worker Mode Not Working

```bash
# Verify Caddyfile syntax
docker-compose exec app frankenphp validate --config /etc/caddy/Caddyfile

# Reload configuration
docker-compose exec app frankenphp reload --config /etc/caddy/Caddyfile
```

### Performance Issues

```bash
# Check worker configuration
cat docker/frankenphp/Caddyfile | grep -A 5 "worker {"

# Increase workers if needed (edit Caddyfile)
worker {
    file /app/public/index.php
    num 4  # Increase from 2 to 4
}
```

---

## 📚 Additional Resources

- **FrankenPHP Official Docs:** https://frankenphp.dev/
- **Caddy Documentation:** https://caddyserver.com/docs/
- **Laravel + FrankenPHP:** https://frankenphp.dev/docs/laravel/

---

## ✅ Quick Verification Checklist

- [ ] Dockerfile menggunakan `dunglas/frankenphp` image
- [ ] Caddyfile ada di `docker/frankenphp/`
- [ ] Worker mode enabled di Caddyfile
- [ ] Container berjalan dengan process `frankenphp`
- [ ] Binary `frankenphp` tersedia di container
- [ ] Application merespons dengan cepat
- [ ] Server header menunjukkan `Caddy`

---

**Last Updated:** 2025-11-27  
**System:** AdoJobs.id
