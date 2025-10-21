# Production Deployment Guide - AdoJobs.id

## 📋 Overview
Panduan lengkap untuk deploy AdoJobs.id ke server production dengan Docker, Nginx reverse proxy, SSL certificate, dan best practices untuk security dan performance.

---

## 🎯 Prerequisites

### **Server Requirements:**
- ✅ **OS**: Ubuntu 20.04 LTS / 22.04 LTS (Recommended)
- ✅ **RAM**: Minimum 2GB (4GB recommended)
- ✅ **Storage**: Minimum 20GB
- ✅ **CPU**: 2 cores minimum
- ✅ **Domain**: Domain name pointing to server IP
- ✅ **SSH Access**: Root or sudo access

### **Software Requirements:**
- ✅ Docker Engine 24.x or higher
- ✅ Docker Compose 2.x or higher
- ✅ Nginx (for reverse proxy)
- ✅ Git

---

## 🚀 Step 1: Prepare Server

### **1.1 Update System**
```bash
# Login to server via SSH
ssh root@your-server-ip

# Update system packages
sudo apt update && sudo apt upgrade -y
```

### **1.2 Install Docker**
```bash
# Install dependencies
sudo apt install -y apt-transport-https ca-certificates curl software-properties-common

# Add Docker GPG key
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg

# Add Docker repository
echo "deb [arch=$(dpkg --print-architecture) signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Install Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io

# Start and enable Docker
sudo systemctl start docker
sudo systemctl enable docker

# Verify installation
docker --version
```

### **1.3 Install Docker Compose**
```bash
# Download Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Make it executable
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker-compose --version
```

### **1.4 Install Nginx**
```bash
# Install Nginx
sudo apt install -y nginx

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx

# Check status
sudo systemctl status nginx
```

### **1.5 Configure Firewall**
```bash
# Allow SSH, HTTP, HTTPS
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Enable firewall
sudo ufw enable

# Check status
sudo ufw status
```

---

## 📦 Step 2: Clone Repository

### **2.1 Create Directory**
```bash
# Create application directory
sudo mkdir -p /var/www
cd /var/www

# Clone repository
sudo git clone https://github.com/yourusername/adojobs.id.git
# Or upload via SFTP/SCP

cd adojobs.id
```

### **2.2 Set Permissions**
```bash
# Set ownership
sudo chown -R $USER:$USER /var/www/adojobs.id

# Set proper permissions
sudo chmod -R 755 /var/www/adojobs.id
```

---

## ⚙️ Step 3: Configure Environment

### **3.1 Create Production Environment File**
```bash
cd /var/www/adojobs.id/src

# Copy example env
cp .env.example .env

# Edit environment file
nano .env
```

### **3.2 Production .env Configuration**
```env
# Application
APP_NAME=AdoJobs.id
APP_ENV=production
APP_KEY=    # Will be generated later
APP_DEBUG=false
APP_URL=https://adojobs.id

# Database
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=adojobs_production
DB_USERNAME=adojobs_user
DB_PASSWORD=your_secure_database_password_here

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail Configuration (Example with Gmail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@adojobs.id
MAIL_FROM_NAME="${APP_NAME}"

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### **3.3 Update docker-compose.yml for Production**
```bash
cd /var/www/adojobs.id

# Create production docker-compose
nano docker-compose.prod.yml
```

**docker-compose.prod.yml:**
```yaml
services:
  # Laravel Application with FrankenPHP
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: production
    container_name: adojobs_app
    ports:
      - "8282:8080"
    volumes:
      - ./src:/app
      - frankenphp_cache:/data/caddy
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - DB_CONNECTION=mysql
      - DB_HOST=db
      - DB_PORT=3306
      - DB_DATABASE=adojobs_production
      - DB_USERNAME=adojobs_user
      - DB_PASSWORD=${DB_PASSWORD}
      - REDIS_HOST=redis
      - REDIS_PASSWORD=null
      - REDIS_PORT=6379
      - CACHE_DRIVER=redis
      - SESSION_DRIVER=redis
      - QUEUE_CONNECTION=redis
    depends_on:
      - db
      - redis
    networks:
      - adojobs_network
    restart: always
    logging:
      driver: "json-file"
      options:
        max-size: "10m"
        max-file: "3"

  # MariaDB Database
  db:
    image: mariadb:11.2
    container_name: adojobs_db
    environment:
      - MYSQL_DATABASE=adojobs_production
      - MYSQL_USER=adojobs_user
      - MYSQL_PASSWORD=${DB_PASSWORD}
      - MYSQL_ROOT_PASSWORD=${DB_ROOT_PASSWORD}
    volumes:
      - mariadb_data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/custom.cnf
    networks:
      - adojobs_network
    restart: always
    command: --character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci
    logging:
      driver: "json-file"
      options:
        max-size: "10m"
        max-file: "3"

  # Redis for Cache and Queue
  redis:
    image: redis:7-alpine
    container_name: adojobs_redis
    volumes:
      - redis_data:/data
    networks:
      - adojobs_network
    restart: always
    command: redis-server --appendonly yes --requirepass ${REDIS_PASSWORD:-null}
    logging:
      driver: "json-file"
      options:
        max-size: "10m"
        max-file: "3"

volumes:
  mariadb_data:
    driver: local
  redis_data:
    driver: local
  frankenphp_cache:
    driver: local

networks:
  adojobs_network:
    driver: bridge
```

**Note**: PHPMyAdmin dihapus untuk production (security).

---

## 🔐 Step 4: SSL Certificate (Let's Encrypt)

### **4.1 Install Certbot**
```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot certonly --nginx -d adojobs.id -d www.adojobs.id

# Follow prompts and enter email
```

### **4.2 Auto-renewal Setup**
```bash
# Test renewal
sudo certbot renew --dry-run

# Certificate will auto-renew via cron
```

---

## 🌐 Step 5: Configure Nginx Reverse Proxy

### **5.1 Create Nginx Configuration**
```bash
sudo nano /etc/nginx/sites-available/adojobs.id
```

**Nginx Configuration:**
```nginx
# Redirect HTTP to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name adojobs.id www.adojobs.id;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

# HTTPS Configuration
server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name adojobs.id www.adojobs.id;

    # SSL Configuration
    ssl_certificate /etc/letsencrypt/live/adojobs.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/adojobs.id/privkey.pem;
    
    # SSL Security
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # SSL Session Cache
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 10m;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Logging
    access_log /var/log/nginx/adojobs_access.log;
    error_log /var/log/nginx/adojobs_error.log;

    # Client Upload Size
    client_max_body_size 20M;

    # Proxy to Docker Container
    location / {
        proxy_pass http://localhost:8282;
        proxy_http_version 1.1;
        
        # Proxy Headers
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_set_header X-Forwarded-Host $host;
        proxy_set_header X-Forwarded-Port $server_port;
        
        # WebSocket Support (if needed)
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        
        # Timeouts
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;
        
        # Buffering
        proxy_buffering off;
    }

    # Static Files Optimization
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        proxy_pass http://localhost:8282;
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Deny access to .env files
    location ~ /\.env {
        deny all;
        return 404;
    }
}
```

### **5.2 Enable Site**
```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/adojobs.id /etc/nginx/sites-enabled/

# Remove default site
sudo rm /etc/nginx/sites-enabled/default

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## 🚀 Step 6: Build and Deploy

### **6.1 Build Docker Images**
```bash
cd /var/www/adojobs.id

# Build production image
docker-compose -f docker-compose.prod.yml build --no-cache
```

### **6.2 Start Containers**
```bash
# Start containers
docker-compose -f docker-compose.prod.yml up -d

# Check status
docker-compose -f docker-compose.prod.yml ps
```

### **6.3 Install Dependencies**
```bash
# Install Composer dependencies
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# Generate application key
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate

# Clear config cache
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
```

### **6.4 Run Migrations**
```bash
# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Run seeders (if needed)
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force
```

### **6.5 Optimize Application**
```bash
# Cache routes
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache

# Cache views
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Cache config
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache

# Optimize autoloader
docker-compose -f docker-compose.prod.yml exec app composer dump-autoload --optimize
```

### **6.6 Set Permissions**
```bash
# Set storage permissions
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 storage bootstrap/cache
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data storage bootstrap/cache
```

---

## ✅ Step 7: Verification

### **7.1 Check Services**
```bash
# Check Docker containers
docker-compose -f docker-compose.prod.yml ps

# Check Nginx
sudo systemctl status nginx

# Check logs
docker-compose -f docker-compose.prod.yml logs -f app
```

### **7.2 Test Application**
```bash
# Test from server
curl -I https://adojobs.id

# Should return: HTTP/2 200
```

### **7.3 Test Database Connection**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

# In tinker:
>>> DB::connection()->getPdo();
>>> exit
```

---

## 🔧 Step 8: Post-Deployment Setup

### **8.1 Setup Cron Jobs (Queue Worker)**
```bash
# Edit crontab
sudo crontab -e

# Add these lines:
* * * * * cd /var/www/adojobs.id && docker-compose -f docker-compose.prod.yml exec -T app php artisan schedule:run >> /dev/null 2>&1
```

### **8.2 Setup Queue Worker (Optional)**
```bash
# Create systemd service
sudo nano /etc/systemd/system/adojobs-queue.service
```

**adojobs-queue.service:**
```ini
[Unit]
Description=AdoJobs Queue Worker
After=docker.service
Requires=docker.service

[Service]
Type=simple
User=root
WorkingDirectory=/var/www/adojobs.id
ExecStart=/usr/local/bin/docker-compose -f docker-compose.prod.yml exec -T app php artisan queue:work --tries=3
Restart=always

[Install]
WantedBy=multi-user.target
```

```bash
# Enable and start service
sudo systemctl enable adojobs-queue
sudo systemctl start adojobs-queue

# Check status
sudo systemctl status adojobs-queue
```

### **8.3 Setup Log Rotation**
```bash
sudo nano /etc/logrotate.d/adojobs
```

**Log Rotation Config:**
```
/var/www/adojobs.id/src/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0640 www-data www-data
    sharedscripts
}
```

---

## 📊 Step 9: Monitoring & Maintenance

### **9.1 Useful Commands**

**View Logs:**
```bash
# Application logs
docker-compose -f docker-compose.prod.yml logs -f app

# Database logs
docker-compose -f docker-compose.prod.yml logs -f db

# Nginx logs
sudo tail -f /var/log/nginx/adojobs_access.log
sudo tail -f /var/log/nginx/adojobs_error.log
```

**Restart Services:**
```bash
# Restart application
docker-compose -f docker-compose.prod.yml restart app

# Restart all containers
docker-compose -f docker-compose.prod.yml restart

# Restart Nginx
sudo systemctl restart nginx
```

**Database Backup:**
```bash
# Create backup
docker-compose -f docker-compose.prod.yml exec db mysqldump -u adojobs_user -p adojobs_production > backup_$(date +%Y%m%d).sql

# Restore backup
docker-compose -f docker-compose.prod.yml exec -T db mysql -u adojobs_user -p adojobs_production < backup_20251021.sql
```

### **9.2 Update Deployment**
```bash
cd /var/www/adojobs.id

# Pull latest changes
git pull origin main

# Rebuild and restart
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml build --no-cache
docker-compose -f docker-compose.prod.yml up -d

# Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Clear cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

---

## 🔒 Security Best Practices

### **1. Environment Security:**
- ✅ Never commit .env to repository
- ✅ Use strong passwords for database
- ✅ Set APP_DEBUG=false in production
- ✅ Use HTTPS only (force SSL)
- ✅ Keep secrets in environment variables

### **2. Server Security:**
- ✅ Keep system updated: `apt update && apt upgrade`
- ✅ Configure firewall (UFW)
- ✅ Use SSH keys (disable password auth)
- ✅ Install fail2ban for brute force protection
- ✅ Regular security audits

### **3. Application Security:**
- ✅ Use Laravel's built-in security features
- ✅ CSRF protection enabled
- ✅ XSS protection enabled
- ✅ SQL injection protection (use Eloquent)
- ✅ Rate limiting on APIs

### **4. Database Security:**
- ✅ Use strong passwords
- ✅ Limit database access (only from app container)
- ✅ Regular backups
- ✅ Encrypted connections

### **5. SSL/TLS:**
- ✅ Use Let's Encrypt certificates
- ✅ Enable HTTPS only
- ✅ Use HSTS header
- ✅ Strong SSL ciphers

---

## 📈 Performance Optimization

### **1. Application:**
```bash
# Enable OPcache in production
# Optimize autoloader
composer dump-autoload --optimize

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### **2. Database:**
- ✅ Use indexes appropriately
- ✅ Optimize queries (use eager loading)
- ✅ Regular maintenance and optimization

### **3. Caching:**
- ✅ Use Redis for cache and sessions
- ✅ Cache database queries
- ✅ Cache views

### **4. CDN (Optional):**
- ✅ Use CDN for static assets
- ✅ Enable browser caching
- ✅ Compress responses (gzip)

---

## 🆘 Troubleshooting

### **Issue: Container won't start**
```bash
# Check logs
docker-compose -f docker-compose.prod.yml logs app

# Check permissions
ls -la /var/www/adojobs.id/src/storage
```

### **Issue: 502 Bad Gateway**
```bash
# Check if app container is running
docker ps | grep adojobs

# Check app logs
docker-compose -f docker-compose.prod.yml logs app

# Restart containers
docker-compose -f docker-compose.prod.yml restart
```

### **Issue: Database connection failed**
```bash
# Check database container
docker-compose -f docker-compose.prod.yml ps db

# Check database logs
docker-compose -f docker-compose.prod.yml logs db

# Test connection
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
```

### **Issue: SSL certificate errors**
```bash
# Renew certificate
sudo certbot renew

# Restart Nginx
sudo systemctl restart nginx
```

---

## 📋 Deployment Checklist

### **Before Deployment:**
- [ ] Server prepared and updated
- [ ] Docker & Docker Compose installed
- [ ] Domain DNS configured
- [ ] SSL certificate obtained
- [ ] Environment variables configured
- [ ] Database credentials set
- [ ] Mail configuration tested

### **During Deployment:**
- [ ] Repository cloned/uploaded
- [ ] Docker images built
- [ ] Containers started successfully
- [ ] Dependencies installed
- [ ] Application key generated
- [ ] Migrations run successfully
- [ ] Seeders run (if needed)
- [ ] Cache optimized
- [ ] Permissions set correctly

### **After Deployment:**
- [ ] Application accessible via HTTPS
- [ ] All pages loading correctly
- [ ] Login functionality working
- [ ] Database connections working
- [ ] Email sending working
- [ ] File uploads working
- [ ] Cron jobs configured
- [ ] Queue workers running
- [ ] Backups configured
- [ ] Monitoring setup

---

## 🎯 Summary

**Deployment Steps:**
1. ✅ Prepare server (Ubuntu + Docker)
2. ✅ Clone repository
3. ✅ Configure environment (.env)
4. ✅ Setup SSL certificate (Let's Encrypt)
5. ✅ Configure Nginx reverse proxy
6. ✅ Build and start Docker containers
7. ✅ Run migrations and seeders
8. ✅ Optimize application
9. ✅ Setup monitoring and backups
10. ✅ Test and verify

**Access:**
- **Website**: https://adojobs.id
- **Admin**: https://adojobs.id/admin/dashboard
- **No PHPMyAdmin** (security)

**AdoJobs.id siap di-deploy ke production dengan Docker, Nginx, dan SSL!** 🚀✨

---

**Updated**: October 21, 2025  
**Author**: AI Assistant  
**Version**: 1.0  
**Status**: ✅ Production Ready

---

🎉 **Production Deployment Guide Complete!**

Follow panduan ini step-by-step untuk deployment yang sukses! 📝✨
