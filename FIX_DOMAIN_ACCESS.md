# Perbaikan Domain Access - IP Bekerja, Domain Tidak

## Masalah

Aplikasi bekerja dengan baik menggunakan IP `10.10.10.33`, tapi tidak bisa dengan domain `adojobs.id`.

## Kemungkinan Penyebab

1. **Caddyfile tidak match dengan domain**: Konfigurasi domain mungkin tidak tepat
2. **NPM belum dikonfigurasi**: NPM belum forward request ke Caddy
3. **DNS belum pointing**: Domain belum pointing ke server
4. **Host header mismatch**: Caddy tidak match dengan Host header dari NPM

## Solusi

### Langkah 1: Update Caddyfile

Caddyfile sudah di-update untuk:
- ✅ Handle explicit domain `http://adojobs.id`
- ✅ Handle www subdomain `http://www.adojobs.id`
- ✅ Default server `:80` juga handle domain via Host header matching

### Langkah 2: Restart Proxy

```bash
cd /var/www/adojobs.id
./fix-domain-access.sh
```

Atau manual:

```bash
cd /var/www/adojobs.id

# Pull perubahan terbaru
sudo git pull origin main

# Restart proxy
docker-compose -f docker-compose.prod.yml --env-file .env.production restart proxy

# Tunggu 5 detik
sleep 5
```

### Langkah 3: Verifikasi Konfigurasi NPM

Pastikan NPM sudah dikonfigurasi dengan benar:

1. **Login ke NPM**: `http://10.10.10.42:81`
2. **Cek Proxy Host** untuk `adojobs.id`:
   - Domain Names: `adojobs.id`, `www.adojobs.id`
   - Scheme: `http`
   - Forward Hostname/IP: `10.10.10.33`
   - Forward Port: `80`
3. **SSL Tab**: Request SSL certificate jika belum

### Langkah 4: Test dari NPM Server

```bash
# Test dengan Host header
curl -H "Host: adojobs.id" http://10.10.10.33/

# Test dengan domain langsung (jika DNS sudah pointing)
curl http://adojobs.id/
```

## Troubleshooting

### Masalah: Domain masih tidak bisa diakses

**Cek 1: DNS sudah pointing?**

```bash
# Test DNS resolution
nslookup adojobs.id
# atau
dig adojobs.id

# Harus resolve ke: 103.130.82.202 (NAT IP)
```

**Cek 2: NPM sudah dikonfigurasi?**

- Login ke NPM: `http://10.10.10.42:81`
- Cek apakah ada Proxy Host untuk `adojobs.id`
- Cek apakah forward ke `10.10.10.33:80`

**Cek 3: Caddy logs**

```bash
docker logs adojobs_proxy --tail 50
```

Cari request dengan domain `adojobs.id` - apakah ada error?

**Cek 4: Test dari NPM server**

```bash
# Test dengan Host header (simulasi request dari NPM)
curl -v -H "Host: adojobs.id" http://10.10.10.33/
```

Jika ini bekerja, berarti Caddy OK, masalahnya di NPM atau DNS.

### Masalah: Caddyfile validation error

```bash
docker exec adojobs_proxy caddy validate --config /etc/caddy/Caddyfile
```

Jika ada error, perbaiki sesuai error message.

### Masalah: Request tidak sampai ke Caddy

**Solusi**: 
1. Cek NPM logs di NPM dashboard
2. Cek firewall tidak block port 80
3. Test connection dari NPM server ke Caddy:
   ```bash
   telnet 10.10.10.33 80
   ```

## Konfigurasi NPM yang Benar

Pastikan NPM dikonfigurasi seperti ini:

**Details Tab:**
- Domain Names: `adojobs.id`, `www.adojobs.id`
- Scheme: `http` (bukan https)
- Forward Hostname/IP: `10.10.10.33`
- Forward Port: `80`
- Cache Assets: ✅ (optional)
- Block Common Exploits: ✅
- Websockets Support: ✅

**SSL Tab:**
- SSL Certificate: Request a new SSL Certificate
- Force SSL: ✅
- HTTP/2 Support: ✅
- Email: `nothing4ll@gmail.com`

**Advanced Tab (optional):**
Bisa kosong, atau tambahkan custom headers jika perlu.

## Verifikasi Lengkap

Setelah fix, test:

1. **IP access**: `http://10.10.10.33/` ✅ (sudah bekerja)
2. **Domain via NPM**: `https://adojobs.id` ✅ (harus bekerja)
3. **www subdomain**: `https://www.adojobs.id` ✅ (harus bekerja)
4. **Test dari NPM server**: `curl -H "Host: adojobs.id" http://10.10.10.33/` ✅

## Status

✅ Caddyfile sudah di-update untuk handle domain dengan benar
✅ Default server juga handle domain via Host header matching
✅ Script fix sudah dibuat
⏳ Perlu restart proxy dan verifikasi NPM configuration

