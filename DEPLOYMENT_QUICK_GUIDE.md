# ⚡ Quick Deployment Guide - AdoJobs.id

## 🚀 Fast Track (5 Menit)

```bash
# 1. Clone repository
cd /var/www
git clone https://github.com/ayahmayra/adojobs.id.git adojobs
cd adojobs

# 2. Setup environment
cp env.production.template .env.production

# 3. Update email untuk SSL (PENTING!)
nano docker/caddy/Caddyfile
# Ganti line 7: email your-email@example.com → email admin@adojobs.id

# 4. Berikan permission
chmod +x deploy-production.sh generate-app-key.sh

# 5. Deploy!
./deploy-production.sh

# 6. Generate APP_KEY (setelah container running)
./generate-app-key.sh
# Copy output, lalu:
nano .env.production
# Update APP_KEY dengan key yang di-generate
docker-compose -f docker-compose.prod.yml restart app

# 7. Seed database (optional)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --force
```

## ✅ Verifikasi

```bash
# Cek status
docker-compose -f docker-compose.prod.yml ps

# Test akses
curl http://10.10.10.33
# atau buka browser: http://10.10.10.33
```

## 🔑 Default Admin (Setelah Seeding)

- **Email:** `admin@adojobs.id`
- **Password:** `password123`

⚠️ **Ganti password segera setelah login pertama!**

## 📚 Dokumentasi Lengkap

Lihat `DEPLOYMENT_STEP_BY_STEP.md` untuk panduan detail.

