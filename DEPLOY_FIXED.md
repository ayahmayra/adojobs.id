# 🔧 Fix: Environment Variables Not Loading

## 🚨 Masalah

Docker Compose tidak membaca `.env.production` untuk variable substitution. Error:
```
WARN[0000] The "DB_PASSWORD" variable is not set.
Database is uninitialized and password option is not specified
```

## ✅ Solusi

### **Option 1: Gunakan --env-file Flag (Recommended)**

```bash
# Semua command docker-compose harus menggunakan --env-file
docker-compose -f docker-compose.prod.yml --env-file .env.production up -d
```

### **Option 2: Export Variables (Alternatif)**

```bash
# Load dan export variables
set -a
source .env.production
set +a

# Kemudian jalankan docker-compose normal
docker-compose -f docker-compose.prod.yml up -d
```

### **Option 3: Copy ke .env (Quick Fix)**

```bash
# Copy .env.production ke .env (Docker Compose otomatis baca .env)
cp .env.production .env

# Jalankan docker-compose normal
docker-compose -f docker-compose.prod.yml up -d
```

**⚠️ Catatan:** Pastikan `.env` ada di `.gitignore` agar tidak ter-commit.

---

## 🚀 Quick Fix Command

```bash
# Fix langsung (copy ke .env)
cp .env.production .env

# Stop semua
docker-compose -f docker-compose.prod.yml down

# Start dengan fresh
docker-compose -f docker-compose.prod.yml up -d

# Monitor logs
docker-compose -f docker-compose.prod.yml logs -f db
```

---

## 📝 Update Scripts

Semua script deployment sudah di-update untuk menggunakan `--env-file .env.production`.

Gunakan script yang sudah di-update:
- `./fix-deployment.sh` - Sudah menggunakan --env-file
- `./deploy-production.sh` - Sudah menggunakan --env-file
- `./deploy-with-env.sh` - Export variables sebelum docker-compose

