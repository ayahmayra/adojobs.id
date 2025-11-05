# 🚀 Production Deployment - AdoJobs.id

## Quick Start

```bash
# 1. Setup environment
cp env.production.template .env.production

# 2. Update email for SSL (REQUIRED)
nano docker/caddy/Caddyfile  # Line 7: your-email@example.com

# 3. Deploy!
./deploy-production.sh

# 4. Generate APP_KEY (first time)
./generate-app-key.sh
# Then update APP_KEY in .env.production and restart
```

## 📋 Configuration Summary

**Server:** 10.10.10.33  
**Domain:** https://adojobs.id  
**Database:** Pre-configured with strong passwords  
**SSL:** Automatic via Caddy + Let's Encrypt

## 🔐 Database Credentials

- **DB:** `adojobs_prod`
- **User:** `adojobs_user`
- **Password:** `Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y`
- **Root:** `Gb43QutnerQYnEvB8Yc2y3nPccEI7LcI`

## 📚 Documentation

- **Quick Start:** `PRODUCTION_QUICK_START.md`
- **Full Guide:** `PRODUCTION_DEPLOYMENT.md`
- **Setup Summary:** `PRODUCTION_SETUP_SUMMARY.md`

## ⚠️ Before Deployment

1. ✅ Point DNS `adojobs.id` → `10.10.10.33`
2. ✅ Update email in `docker/caddy/Caddyfile`
3. ✅ Open firewall ports 80, 443
4. ✅ Ensure Docker & Docker Compose installed

## 🎯 One Command Deployment

```bash
docker-compose -f docker-compose.prod.yml up -d
```

That's it! 🎉

