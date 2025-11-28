# Production Quick Start - AdoJobs.id

## 🚀 One-Command Deployment

```bash
# 1. Copy environment file
cp env.production.template .env.production

# 2. Update email in Caddyfile (for SSL)
nano docker/caddy/Caddyfile  # Line 6: Update email

# 3. Deploy!
./deploy-production.sh
```

## 📋 Pre-configured Credentials

**Database:**
- DB: `adojobs_prod`
- User: `adojobs_user`  
- Password: `Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y`
- Root: `Gb43QutnerQYnEvB8Yc2y3nPccEI7LcI`

**Access:**
- Domain: `https://adojobs.id`
- IP: `http://10.10.10.33`

## ⚙️ First Time Setup

After deployment, generate APP_KEY:

```bash
# Get the key
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate --show

# Update .env.production with the key
nano .env.production

# Restart app
docker-compose -f docker-compose.prod.yml restart app
```

## 🔧 Common Commands

```bash
# View logs
docker-compose -f docker-compose.prod.yml logs -f

# Restart all
docker-compose -f docker-compose.prod.yml restart

# Stop all
docker-compose -f docker-compose.prod.yml down

# Status
docker-compose -f docker-compose.prod.yml ps
```

## 📝 Important Files

- `env.production.template` - Environment template with passwords
- `.env.production` - Your production config (create from template)
- `docker-compose.prod.yml` - Docker Compose config
- `docker/caddy/Caddyfile` - Reverse proxy & SSL config
- `deploy-production.sh` - Deployment script

## ⚠️ Before Going Live

1. ✅ Update DNS: Point `adojobs.id` → `10.10.10.33`
2. ✅ Update email in `docker/caddy/Caddyfile` (line 6)
3. ✅ Set APP_KEY in `.env.production`
4. ✅ Verify firewall allows ports 80, 443

## 📚 Full Documentation

See `PRODUCTION_DEPLOYMENT.md` for complete guide.

