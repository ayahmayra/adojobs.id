# 🔄 Development vs Production Consistency Guide

**Last Updated:** November 4, 2025  
**Purpose:** Memastikan development environment sama dengan production untuk menghindari "works on my machine" issues

---

## 🎯 Philosophy: Maximum Consistency

Prinsip yang digunakan:
> **"Perbedaan antara development dan production hanya pada environment variables dan volume mounting, BUKAN pada runtime behavior"**

---

## ✅ Yang SAMA antara Development & Production

### 1. **Runtime Environment** ✅
| Component | Development | Production | Status |
|-----------|-------------|------------|--------|
| **Base Image** | `dunglas/frankenphp:latest-php8.3` | `dunglas/frankenphp:latest-php8.3` | ✅ SAMA |
| **PHP Version** | 8.3 | 8.3 | ✅ SAMA |
| **PHP Extensions** | Same set | Same set | ✅ SAMA |
| **OPcache** | Enabled | Enabled | ✅ SAMA |
| **Memory Limit** | 256M | 256M | ✅ SAMA |

### 2. **FrankenPHP Configuration** ✅
| Setting | Development | Production | Status |
|---------|-------------|------------|--------|
| **Caddyfile** | `Caddyfile` | `Caddyfile` (same file) | ✅ SAMA |
| **Workers** | 2 | 2 | ✅ SAMA |
| **Threads** | 8 | 8 | ✅ SAMA |
| **Worker Mode** | Enabled | Enabled | ✅ SAMA |
| **Compression** | gzip + zstd | gzip + zstd | ✅ SAMA |
| **Security Headers** | Enabled | Enabled | ✅ SAMA |
| **Static Caching** | Enabled | Enabled | ✅ SAMA |
| **Error Handling** | Same | Same | ✅ SAMA |

### 3. **Database Configuration** ✅
| Setting | Development | Production | Status |
|---------|-------------|------------|--------|
| **Image** | mariadb:11.2 | mariadb:11.2 | ✅ SAMA |
| **Character Set** | utf8mb4 | utf8mb4 | ✅ SAMA |
| **Collation** | utf8mb4_unicode_ci | utf8mb4_unicode_ci | ✅ SAMA |
| **Health Check** | Yes | Yes | ✅ SAMA |
| **Custom Config** | my.cnf | my.cnf | ✅ SAMA |

### 4. **Redis Configuration** ✅
| Setting | Development | Production | Status |
|---------|-------------|------------|--------|
| **Image** | redis:7-alpine | redis:7-alpine | ✅ SAMA |
| **AOF** | Enabled | Enabled | ✅ SAMA |
| **Max Memory** | 256mb | 256mb | ✅ SAMA |
| **Eviction** | allkeys-lru | allkeys-lru | ✅ SAMA |
| **Health Check** | Yes | Yes | ✅ SAMA |

### 5. **Container Configuration** ✅
| Setting | Development | Production | Status |
|---------|-------------|------------|--------|
| **Port** | 8282:8080 | 8282:8080 | ✅ SAMA |
| **Network** | bridge | bridge | ✅ SAMA |
| **Restart Policy** | unless-stopped | unless-stopped | ✅ SAMA |
| **Health Checks** | Yes | Yes | ✅ SAMA |
| **Depends On** | With conditions | With conditions | ✅ SAMA |

---

## 🔀 Perbedaan yang DISENGAJA (by Design)

### 1. **Environment Variables**

**Development (`docker-compose.yml`):**
```yaml
environment:
  - APP_ENV=local
  - APP_DEBUG=true
  - CACHE_DRIVER=file
  - SESSION_DRIVER=file
  - QUEUE_CONNECTION=sync
```

**Production (`docker-compose.prod.yml`):**
```yaml
environment:
  - APP_ENV=production
  - APP_DEBUG=false
  - CACHE_DRIVER=redis
  - SESSION_DRIVER=redis
  - QUEUE_CONNECTION=redis
```

**Why?**
- Development perlu debug output untuk troubleshooting
- Production harus aman (no debug info leak)
- Development uses file cache untuk simplicity (no Redis dependency)
- Production uses Redis untuk performance dan scale

### 2. **Volume Mounting**

**Development:**
```yaml
volumes:
  - ./src:/app                          # Full app mounted (hot reload)
  - frankenphp_cache:/data/caddy
```

**Production:**
```yaml
volumes:
  - ./src/storage/app:/app/storage/app  # Only storage (security)
  - ./src/storage/logs:/app/storage/logs
  - frankenphp_cache:/data/caddy
```

**Why?**
- Development needs hot reload (code changes instant)
- Production mounts only storage (security, immutability)
- Code is baked into image in production

### 3. **Build Target**

**Development:**
```yaml
build:
  target: development
```

**Production:**
```yaml
build:
  target: production
```

**Why?**
- Production stage includes optimizations (composer, artisan cache)
- Production includes built assets (npm run build)
- Development stage skips optimizations (faster builds)

### 4. **Optional Services**

**Development:**
- ✅ PHPMyAdmin (port 8281) - for easy DB management

**Production:**
- ❌ No PHPMyAdmin - security (access via CLI only)

---

## 🧪 Testing Consistency

### Local Testing dengan Production Config

Anda bisa test production config di local:

```bash
# 1. Stop development
docker-compose down

# 2. Start production config
docker-compose -f docker-compose.prod.yml up -d --build

# 3. Test
curl http://localhost:8282

# 4. Compare behavior dengan development
docker-compose -f docker-compose.prod.yml logs app

# 5. Stop production
docker-compose -f docker-compose.prod.yml down

# 6. Back to development
docker-compose up -d
```

### Testing Checklist

Test ini di development DAN production (local prod config):

- [ ] Homepage loads
- [ ] User registration works
- [ ] User login works
- [ ] Database queries work
- [ ] File uploads work
- [ ] Static assets load
- [ ] Job listings display
- [ ] Search works
- [ ] Messages work
- [ ] Profile updates work

**Jika semua ✅ di kedua environment, maka production deploy akan aman!**

---

## 📊 Configuration Matrix

| Aspect | Development | Local Prod Test | Remote Production |
|--------|-------------|-----------------|-------------------|
| **Compose File** | `docker-compose.yml` | `docker-compose.prod.yml` | `docker-compose.prod.yml` |
| **Build Target** | `development` | `production` | `production` |
| **Caddyfile** | `Caddyfile` | `Caddyfile` | `Caddyfile` |
| **APP_ENV** | `local` | `production` | `production` |
| **APP_DEBUG** | `true` | `false` | `false` |
| **Cache** | `file` | `redis` | `redis` |
| **Session** | `file` | `redis` | `redis` |
| **Queue** | `sync` | `redis` | `redis` |
| **Volume Mount** | Full `/app` | Storage only | Storage only |
| **Code Location** | Host mounted | Docker image | Docker image |
| **PHPMyAdmin** | Yes (8281) | No | No |
| **Database** | Same | Same | Same |
| **Redis** | Same | Same | Same |
| **FrankenPHP** | Same | Same | Same |
| **Port** | 8282 | 8282 | 8282 (or 80/443) |

---

## 🔍 How to Verify Consistency

### 1. Check PHP Configuration

**Development:**
```bash
docker-compose exec app php -i | grep -E "memory_limit|opcache"
```

**Production (local):**
```bash
docker-compose -f docker-compose.prod.yml exec app php -i | grep -E "memory_limit|opcache"
```

**Should be IDENTICAL**

### 2. Check FrankenPHP Workers

**Development:**
```bash
docker-compose exec app ps aux | grep frankenphp
```

**Production (local):**
```bash
docker-compose -f docker-compose.prod.yml exec app ps aux | grep frankenphp
```

**Should show same worker count (2 workers)**

### 3. Check Database Version

**Development:**
```bash
docker-compose exec db mysql --version
```

**Production (local):**
```bash
docker-compose -f docker-compose.prod.yml exec db mysql --version
```

**Should be IDENTICAL (mariadb 11.2)**

### 4. Check Redis Version

**Development:**
```bash
docker-compose exec redis redis-cli INFO server | grep redis_version
```

**Production (local):**
```bash
docker-compose -f docker-compose.prod.yml exec redis redis-cli INFO server | grep redis_version
```

**Should be IDENTICAL (redis 7.x)**

### 5. Check Installed PHP Extensions

**Development:**
```bash
docker-compose exec app php -m
```

**Production (local):**
```bash
docker-compose -f docker-compose.prod.yml exec app php -m
```

**Should be IDENTICAL**

---

## 🎯 Best Practices

### 1. Test Production Config Locally FIRST

```bash
# Always test production config locally before deploying to server
docker-compose -f docker-compose.prod.yml up -d --build

# Run full test suite
docker-compose -f docker-compose.prod.yml exec app php artisan test

# Manual testing
curl http://localhost:8282

# Check logs
docker-compose -f docker-compose.prod.yml logs -f app
```

### 2. Use Same .env Keys

Ensure `.env.development` and `.env.production` use same keys:

```bash
# Development (.env or docker-compose.yml)
APP_NAME=AdoJobs
APP_ENV=local
APP_DEBUG=true
DB_HOST=db
REDIS_HOST=redis
CACHE_DRIVER=file

# Production (.env.production)
APP_NAME=AdoJobs
APP_ENV=production        # ← Only change
APP_DEBUG=false           # ← Only change
DB_HOST=db                # ← Same
REDIS_HOST=redis          # ← Same
CACHE_DRIVER=redis        # ← Only change (performance)
```

### 3. Document Any Differences

If you must add a difference, document it in this file with:
- **What** is different
- **Why** it's different
- **Impact** on behavior

### 4. Keep Dependencies Locked

```bash
# Lock PHP dependencies
composer.lock  # ← Commit this!

# Lock JS dependencies
package-lock.json  # ← Commit this!

# Lock Docker images
# Use specific tags, not :latest
mariadb:11.2  # ✅ Good
mariadb:latest  # ❌ Bad
```

---

## 🚨 Red Flags (Things to AVOID)

### ❌ Different PHP Versions
```yaml
# ❌ DON'T DO THIS
development: php:8.3
production: php:8.2  # Different!
```

### ❌ Different Database Versions
```yaml
# ❌ DON'T DO THIS
development: mariadb:11.2
production: mariadb:10.6  # Different!
```

### ❌ Different FrankenPHP Configuration
```caddyfile
# ❌ DON'T DO THIS
# Caddyfile.dev
worker { num 2 }

# Caddyfile.prod
worker { num 4 }  # Different!
```

### ❌ Different PHP Extensions
```dockerfile
# ❌ DON'T DO THIS
RUN if [ "$APP_ENV" = "production" ]; then \
    docker-php-ext-install extra-extension; \
fi
```

---

## ✅ Consistency Checklist

Before deploying to production, verify:

- [ ] Same Docker base images (versions locked)
- [ ] Same Caddyfile used in dev and prod
- [ ] Same FrankenPHP worker configuration
- [ ] Same PHP version and extensions
- [ ] Same database version and charset
- [ ] Same Redis version
- [ ] Composer.lock committed
- [ ] Package-lock.json committed
- [ ] Production tested locally first
- [ ] All tests pass in both environments
- [ ] No hard-coded environment-specific values

---

## 📈 Benefits of This Approach

### 1. **Predictability** 🎯
```
If it works in development → It works in production
```

### 2. **Faster Debugging** 🔍
```
Bug in production? Reproduce locally with prod config instantly!
```

### 3. **Confidence** 💪
```
No surprises on deployment day
```

### 4. **Easier Onboarding** 👥
```
New developer setup = Production-like environment
```

### 5. **Cost Reduction** 💰
```
No expensive production-only bugs
```

---

## 🔧 Troubleshooting Inconsistencies

### Issue: "Works in dev, breaks in prod"

**Diagnosis Steps:**

1. **Test production config locally:**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d --build
   ```

2. **Compare configurations:**
   ```bash
   # Check PHP version
   docker-compose exec app php -v
   docker-compose -f docker-compose.prod.yml exec app php -v
   
   # Check environment
   docker-compose exec app env | sort
   docker-compose -f docker-compose.prod.yml exec app env | sort
   ```

3. **Check logs:**
   ```bash
   docker-compose -f docker-compose.prod.yml logs app
   ```

4. **Compare file permissions:**
   ```bash
   docker-compose exec app ls -la storage/
   docker-compose -f docker-compose.prod.yml exec app ls -la storage/
   ```

5. **Check cache differences:**
   - Dev uses file cache → Check `storage/framework/cache`
   - Prod uses Redis → Check `redis-cli KEYS *`

---

## 📝 Summary

### Consistency Achieved ✅

| Category | Status | Notes |
|----------|--------|-------|
| **Runtime** | ✅ 100% Same | PHP 8.3, same extensions, same limits |
| **FrankenPHP** | ✅ 100% Same | Same Caddyfile, workers, config |
| **Database** | ✅ 100% Same | MariaDB 11.2, same settings |
| **Redis** | ✅ 100% Same | Redis 7, same configuration |
| **Environment** | ⚠️ Intentionally Different | APP_DEBUG, cache drivers only |
| **Volumes** | ⚠️ Intentionally Different | Dev = full mount, Prod = storage only |

### Key Principle

```
┌─────────────────────────────────────────────────┐
│  Same Runtime + Same Config + Different ENV     │
│  = Predictable, Consistent Behavior             │
└─────────────────────────────────────────────────┘
```

---

## 🚀 Deployment Workflow

```bash
# 1. Develop
docker-compose up -d

# 2. Test locally
docker-compose exec app php artisan test

# 3. Test production config locally
docker-compose -f docker-compose.prod.yml up -d --build
docker-compose -f docker-compose.prod.yml exec app php artisan test

# 4. If all tests pass → Deploy to server
git push origin main
ssh production-server
./deploy.sh

# 5. Monitor
make -f Makefile.prod logs
```

---

**Conclusion:** 🎉

Development dan Production sekarang **konsisten secara maksimal**, dengan perbedaan hanya pada:
1. Environment variables (intentional, for behavior differences)
2. Volume mounting (intentional, for security)

Runtime environment, FrankenPHP config, database, dan semua dependencies **100% sama**!


