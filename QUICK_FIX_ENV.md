# ⚡ Quick Fix: Environment Variables Issue

## 🎯 Solusi Cepat (30 Detik)

```bash
# 1. Copy .env.production ke .env (Docker Compose baca .env otomatis)
cp .env.production .env

# 2. Stop semua
docker-compose -f docker-compose.prod.yml down

# 3. Start ulang
docker-compose -f docker-compose.prod.yml up -d

# 4. Monitor
docker-compose -f docker-compose.prod.yml logs -f db
```

## ✅ Verifikasi

```bash
# Cek status
docker-compose -f docker-compose.prod.yml ps db
# Harus: "Up (healthy)"

# Test database
docker-compose -f docker-compose.prod.yml exec db mysql -u adojobs_user -p'Jo9Ojq96JeCalRHeNVpm3pGtyUGjjy4y' -e "SELECT 1;"
```

## 📝 Penjelasan

Docker Compose secara default hanya membaca file `.env` untuk variable substitution (bukan `.env.production`).

Solusi:
1. **Copy ke .env** (paling cepat) ✅
2. **Gunakan --env-file** (di semua command)
3. **Export variables** (sebelum docker-compose)

**Recommended:** Copy ke `.env` karena paling sederhana dan tidak perlu ubah semua command.

