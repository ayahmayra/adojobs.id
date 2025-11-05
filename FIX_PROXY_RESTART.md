# 🔧 Fix: Proxy Container Restarting

## 🚨 Masalah

Container `adojobs_proxy` terus restart karena:
1. Email di Caddyfile masih placeholder (`your-email@example.com`)
2. DNS belum pointing, sehingga SSL certificate gagal
3. Health check endpoint tidak ada

## ✅ Solusi Cepat

### **Option 1: Gunakan HTTP Only (Sementara)**

Caddyfile sudah di-update untuk menggunakan HTTP only sampai DNS ready.

```bash
# Restart proxy
docker-compose -f docker-compose.prod.yml restart proxy

# Monitor logs
docker-compose -f docker-compose.prod.yml logs -f proxy
```

### **Option 2: Update Email (Jika DNS Sudah Ready)**

```bash
# Edit Caddyfile
nano docker/caddy/Caddyfile

# Uncomment dan update:
# 1. Email section (line 7): email admin@adojobs.id
# 2. HTTPS domain block (line 13-77): Uncomment semua

# Restart proxy
docker-compose -f docker-compose.prod.yml restart proxy
```

## 🔍 Check Logs

```bash
# Cek logs proxy untuk melihat error
docker-compose -f docker-compose.prod.yml logs proxy --tail=50

# Common errors:
# - "email validation failed" → Update email
# - "acme: error obtaining certificate" → DNS belum pointing
# - "no such host" → Container name salah
```

## 📋 Verifikasi

```bash
# 1. Cek status
docker-compose -f docker-compose.prod.yml ps proxy
# Harus: "Up" (tidak restarting)

# 2. Test HTTP
curl http://10.10.10.33
# Harus return HTML dari Laravel

# 3. Test via IP
curl -I http://10.10.10.33
# Harus: HTTP/1.1 200 OK
```

## 🎯 Setelah DNS Ready

Ketika DNS sudah pointing ke `10.10.10.33`:

1. **Update Caddyfile:**
   ```bash
   nano docker/caddy/Caddyfile
   ```
   
2. **Uncomment email:**
   ```caddy
   {
       email admin@adojobs.id
   }
   ```
   
3. **Uncomment HTTPS block:**
   ```caddy
   adojobs.id, www.adojobs.id {
       # ... semua config
   }
   ```
   
4. **Restart proxy:**
   ```bash
   docker-compose -f docker-compose.prod.yml restart proxy
   ```

## 🆘 Jika Masih Restarting

```bash
# 1. Cek logs detail
docker-compose -f docker-compose.prod.yml logs proxy

# 2. Validate Caddyfile
docker-compose -f docker-compose.prod.yml exec proxy caddy validate --config /etc/caddy/Caddyfile

# 3. Test config
docker-compose -f docker-compose.prod.yml exec proxy caddy adapt --config /etc/caddy/Caddyfile

# 4. Jika masih error, gunakan HTTP only
# Caddyfile sudah di-configure untuk HTTP only
```

---

**Quick Fix:** Caddyfile sudah di-update untuk HTTP only. Restart proxy:
```bash
docker-compose -f docker-compose.prod.yml restart proxy
```

