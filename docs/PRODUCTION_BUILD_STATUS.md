# 🏗️ Production Build Status

**Last Updated:** November 4, 2025
**Status:** ⚠️ Build Successful, Runtime Issue Detected

---

## ✅ What's Working

### 1. Docker Build
- ✅ Multi-stage Dockerfile successfully builds
- ✅ Node.js/npm installed and working
- ✅ Frontend assets built successfully (`public/build/`)
- ✅ PHP 8.3.7 with all required extensions
- ✅ Redis extension installed
- ✅ Composer dependencies installed

### 2. Database
- ✅ MariaDB container starts healthy
- ✅ Redis container starts healthy
- ✅ Database connection works from artisan commands
- ✅ All migrations run successfully (20 migrations)

### 3. Laravel Application
- ✅ `php artisan about` works
- ✅ `php artisan migrate` works
- ✅ Environment: production
- ✅ Debug mode: OFF
- ✅ Storage linked

### 4. Configuration
- ✅ Docker Compose `env_file` configured
- ✅ Environment variables loaded from `.env.production`
- ✅ Database credentials working
- ✅ Redis credentials working

---

## ⚠️ Current Issue

**Problem:** FrankenPHP workers continuously restarting

```
adojobs_app  | {"level":"info","ts":1762270628,"msg":"restarting","worker":"/app/public/index.php"}
```

**Symptoms:**
- HTTP requests timeout (no response after 2+ minutes)
- Workers restart every ~20ms
- No errors in Laravel logs (or logs not being written)
- Application works fine in artisan commands

---

## 🔍 Diagnostic Steps Completed

1. ✅ Checked PHP modules - Redis present
2. ✅ Tested database connection - Works
3. ✅ Ran migrations - Success
4. ✅ Checked built assets - Present
5. ✅ Verified environment variables - Loaded
6. ✅ Checked Laravel configuration - Correct

---

## 🐛 Potential Causes

### 1. Storage Volume Mount Conflict
**Issue:** `docker-compose.prod.yml` mounts local storage:
```yaml
volumes:
  - ./src/storage/app:/app/storage/app
  - ./src/storage/logs:/app/storage/logs
```

**Problem:** This overrides built-in storage from Docker image, potentially causing:
- Permission mismatches
- Old/corrupted log files
- Missing subdirectories

### 2. FrankenPHP Worker Mode Issue
**Worker Configuration:**
```caddyfile
frankenphp {
    num_threads 8
    worker {
        file /app/public/index.php
        num 2
    }
}
```

**Possible Issue:** Worker mode might be incompatible with current setup

### 3. Application Code Issue
- Possible unhandled exception during bootstrap
- Missing cache directories
- Session storage issues

---

## 🔧 Recommended Fixes

### Option 1: Remove Storage Volume Mounts (Recommended)

**Modify `docker-compose.prod.yml`:**
```yaml
volumes:
  # Remove these lines:
  # - ./src/storage/app:/app/storage/app
  # - ./src/storage/logs:/app/storage/logs
  
  # Keep only Caddy cache:
  - frankenphp_cache:/data/caddy
```

**Pros:**
- Uses built-in storage from image
- No permission issues
- Clean slate

**Cons:**
- Need to re-upload files after rebuild
- Logs won't be accessible from host

### Option 2: Fix Storage Permissions

```bash
# On host
sudo chown -R 1000:1000 src/storage
sudo chmod -R 775 src/storage

# Rebuild
docker-compose -f docker-compose.prod.yml down -v
docker-compose -f docker-compose.prod.yml up -d
```

### Option 3: Disable Worker Mode Temporarily

**Test without workers to isolate issue:**

Create `docker/frankenphp/Caddyfile.no-workers`:
```caddyfile
{
    frankenphp
    order php_server before file_server
}

:8080 {
    root * /app/public
    encode gzip zstd
    php_server
    file_server
}
```

**Rebuild with:**
```dockerfile
# In Dockerfile production stage
COPY docker/frankenphp/Caddyfile.no-workers /etc/caddy/Caddyfile
```

### Option 4: Add Detailed Logging

**Modify Caddyfile to add debug logging:**
```caddyfile
{
    debug  # Enable debug logs
    frankenphp {
        num_threads 8
        worker {
            file /app/public/index.php
            num 2
        }
    }
}
```

Then check logs:
```bash
docker-compose -f docker-compose.prod.yml logs -f app
```

---

## 📋 Next Steps

1. **Try Option 1 first** (remove volume mounts)
2. If still fails, try **Option 3** (disable workers)
3. If works without workers, investigate worker-specific issue
4. If still fails, add **Option 4** (debug logging)

---

## 🚀 Quick Test Commands

```bash
# Clean rebuild
docker-compose -f docker-compose.prod.yml down -v
rm .env  # Don't forget!
cp .env.production .env
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d

# Wait for startup
sleep 30

# Check status
docker-compose -f docker-compose.prod.yml ps
docker-compose -f docker-compose.prod.yml logs --tail=50 app

# Test HTTP
curl -v http://localhost:8282

# Test Laravel
docker-compose -f docker-compose.prod.yml exec app php artisan about
```

---

## 📊 Build Metrics

- **Docker Build Time:** ~15 minutes (with --no-cache)
- **Image Size:** ~500MB (estimated)
- **Startup Time:** ~30 seconds (services)
- **Memory Usage:** TBD (not tested yet)

---

## ✍️ Notes

- Development environment works perfectly
- Production build completes successfully
- Issue is runtime-specific (FrankenPHP worker mode)
- Laravel itself is healthy (artisan commands work)
- Database and Redis connections are fine

---

**Conclusion:** The issue is NOT with the build process or Laravel configuration, but specifically with FrankenPHP worker mode runtime behavior. Likely related to storage permissions or code conflicts between mounted volumes and built image.


