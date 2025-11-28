# 🚀 Production Deployment Guide - JobMaker

Panduan lengkap untuk deploy JobMaker Job Portal System ke production server.

---

## 📋 Prerequisites Server

### Minimum Requirements
- **OS:** Ubuntu 20.04+ / Debian 11+ / CentOS 8+
- **RAM:** 4GB (minimum), 8GB (recommended)
- **Storage:** 40GB SSD (minimum), 100GB (recommended)
- **CPU:** 2 vCPU (minimum), 4 vCPU (recommended)
- **Network:** Public IP address
- **Domain:** Domain name (untuk SSL)

### Software Requirements
- Docker Engine 20.x+
- Docker Compose 2.x+
- Nginx (reverse proxy) - optional
- SSL Certificate (Let's Encrypt recommended)

---

## 🔧 Server Preparation

### 1. Update System

```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y

# CentOS/RHEL
sudo yum update -y
```

### 2. Install Docker

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Add user to docker group
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Verify installation
docker --version
docker-compose --version
```

### 3. Configure Firewall

```bash
# Allow HTTP, HTTPS, SSH
sudo ufw allow 22/tcp    # SSH
sudo ufw allow 80/tcp    # HTTP
sudo ufw allow 443/tcp   # HTTPS
sudo ufw enable

# Verify
sudo ufw status
```

---

## 📦 Application Deployment

### Step 1: Clone Repository

```bash
# Create application directory
sudo mkdir -p /var/www/jobmaker
cd /var/www/jobmaker

# Clone repository (gunakan deploy key untuk private repo)
git clone <your-repository-url> .

# Set ownership
sudo chown -R $USER:$USER /var/www/jobmaker
```

### Step 2: Configure Environment

```bash
# Copy environment file
cp src/.env.example src/.env

# Edit environment for production
nano src/.env
```

**Production `.env` Configuration:**

```env
# Application
APP_NAME=JobMaker
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Generate strong key with: php artisan key:generate
APP_KEY=base64:STRONG_RANDOM_KEY_HERE

# Database (gunakan password yang kuat!)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=jobmaker_db
DB_USERNAME=jobmaker_user
DB_PASSWORD=STRONG_DATABASE_PASSWORD_HERE

# Redis (gunakan password!)
REDIS_HOST=redis
REDIS_PASSWORD=STRONG_REDIS_PASSWORD_HERE
REDIS_PORT=6379

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail Configuration (gunakan SMTP server)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=error

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

**IMPORTANT SECURITY:**
- ❌ **NEVER** set `APP_DEBUG=true` di production
- ✅ Generate strong `APP_KEY`
- ✅ Use strong passwords untuk DB dan Redis
- ✅ Set `SESSION_SECURE_COOKIE=true` untuk HTTPS

### Step 3: Update Docker Compose untuk Production

Buat file `docker-compose.prod.yml`:

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
      target: production
    container_name: jobmaker_app
    restart: unless-stopped
    working_dir: /app
    volumes:
      - ./src:/app
      - ./src/storage:/app/storage
      - ./src/bootstrap/cache:/app/bootstrap/cache
    ports:
      - "8080:8080"
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    networks:
      - jobmaker_network
    depends_on:
      - db
      - redis
    healthcheck:
      test: ["CMD", "curl", "-f", "http://localhost:8080"]
      interval: 30s
      timeout: 10s
      retries: 3
      start_period: 40s

  db:
    image: mariadb:11.2
    container_name: jobmaker_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
      - ./docker/mysql/my.cnf:/etc/mysql/conf.d/custom.cnf:ro
    networks:
      - jobmaker_network
    command: --max_connections=200 --innodb-buffer-pool-size=512M

  redis:
    image: redis:7-alpine
    container_name: jobmaker_redis
    restart: unless-stopped
    command: redis-server --requirepass ${REDIS_PASSWORD}
    volumes:
      - redis_data:/data
    networks:
      - jobmaker_network

  # REMOVE phpmyadmin di production untuk security
  # phpmyadmin:
  #   ...

volumes:
  db_data:
    driver: local
  redis_data:
    driver: local

networks:
  jobmaker_network:
    driver: bridge
```

### Step 4: Build dan Start Production Containers

```bash
# Build production image
docker-compose -f docker-compose.prod.yml build --no-cache

# Start containers
docker-compose -f docker-compose.prod.yml up -d

# Verify
docker-compose -f docker-compose.prod.yml ps
```

### Step 5: Install Dependencies & Setup Database

```bash
# Install dependencies (production only, no dev dependencies)
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# Generate application key
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate

# Run migrations (WITHOUT seeding di production!)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Create admin account manually
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
```

Di Tinker, buat admin:
```php
$admin = \App\Models\User::create([
    'name' => 'Administrator',
    'email' => 'admin@yourdomain.com',
    'password' => bcrypt('STRONG_PASSWORD_HERE'),
    'role' => 'admin',
    'is_active' => true,
    'email_verified_at' => now(),
]);
echo "Admin created: " . $admin->email;
exit;
```

### Step 6: Optimize untuk Production

```bash
# Optimize Laravel
docker-compose -f docker-compose.prod.yml exec app php artisan optimize
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# Set proper permissions
docker-compose -f docker-compose.prod.yml exec app chown -R www-data:www-data storage bootstrap/cache
docker-compose -f docker-compose.prod.yml exec app chmod -R 775 storage bootstrap/cache
```

---

## 🔐 Setup Nginx Reverse Proxy

### 1. Install Nginx

```bash
sudo apt install nginx -y
```

### 2. Configure Nginx

Create `/etc/nginx/sites-available/jobmaker`:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    
    # Redirect to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    
    # SSL Configuration (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;
    
    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    
    # Proxy to Docker container
    location / {
        proxy_pass http://localhost:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_redirect off;
        
        # WebSocket support (if needed)
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
    
    # Logs
    access_log /var/log/nginx/jobmaker_access.log;
    error_log /var/log/nginx/jobmaker_error.log;
}
```

### 3. Enable Site

```bash
# Create symbolic link
sudo ln -s /etc/nginx/sites-available/jobmaker /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## 🔒 Setup SSL dengan Let's Encrypt

### 1. Install Certbot

```bash
# Ubuntu/Debian
sudo apt install certbot python3-certbot-nginx -y

# CentOS
sudo yum install certbot python3-certbot-nginx -y
```

### 2. Obtain SSL Certificate

```bash
# Get certificate (auto-configure Nginx)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

### 3. Auto-renewal Cron

Certificate auto-renews, tapi verifikasi cron job:

```bash
# Check renewal timer
sudo systemctl status certbot.timer

# Manual renewal test
sudo certbot renew
```

---

## 🔄 Setup Auto-start & Monitoring

### 1. Enable Docker Auto-start

```bash
# Enable Docker service
sudo systemctl enable docker

# Enable containers restart on boot
# Already configured with: restart: unless-stopped
```

### 2. Setup Laravel Schedule (Cron)

```bash
# Edit crontab
crontab -e

# Add this line:
* * * * * cd /var/www/jobmaker && docker-compose -f docker-compose.prod.yml exec -T app php artisan schedule:run >> /dev/null 2>&1
```

### 3. Setup Queue Worker (Supervisor)

Install Supervisor:

```bash
sudo apt install supervisor -y
```

Create `/etc/supervisor/conf.d/jobmaker-worker.conf`:

```ini
[program:jobmaker-worker]
process_name=%(program_name)s_%(process_num)02d
command=docker-compose -f /var/www/jobmaker/docker-compose.prod.yml exec -T app php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/jobmaker/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start jobmaker-worker:*
```

---

## 💾 Database Backup Strategy

### 1. Automated Backup Script

Create `/var/www/jobmaker/backup.sh`:

```bash
#!/bin/bash

# Configuration
BACKUP_DIR="/var/backups/jobmaker"
CONTAINER="jobmaker_db"
DB_NAME="jobmaker_db"
DB_USER="jobmaker_user"
DB_PASS="YOUR_DB_PASSWORD"
RETENTION_DAYS=7

# Create backup directory
mkdir -p $BACKUP_DIR

# Generate backup filename
BACKUP_FILE="$BACKUP_DIR/jobmaker_$(date +%Y%m%d_%H%M%S).sql.gz"

# Create backup
docker exec $CONTAINER mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_FILE

# Remove old backups
find $BACKUP_DIR -name "jobmaker_*.sql.gz" -mtime +$RETENTION_DAYS -delete

echo "Backup completed: $BACKUP_FILE"
```

Make executable and schedule:

```bash
chmod +x /var/www/jobmaker/backup.sh

# Add to crontab (daily at 2 AM)
crontab -e

# Add line:
0 2 * * * /var/www/jobmaker/backup.sh >> /var/log/jobmaker_backup.log 2>&1
```

### 2. Restore from Backup

```bash
# Restore database
gunzip < /var/backups/jobmaker/jobmaker_20241014_020000.sql.gz | \
docker exec -i jobmaker_db mysql -ujobmaker_user -pYOUR_PASSWORD jobmaker_db
```

---

## 📊 Monitoring & Logging

### 1. Application Logs

```bash
# View application logs
docker-compose -f docker-compose.prod.yml logs -f app

# View specific log files
tail -f src/storage/logs/laravel.log
```

### 2. System Monitoring

Install monitoring tools:

```bash
# Install htop for resource monitoring
sudo apt install htop -y

# Monitor Docker containers
docker stats

# Monitor disk usage
df -h
```

### 3. Setup Laravel Telescope (Optional)

```bash
# Install Telescope
docker-compose -f docker-compose.prod.yml exec app composer require laravel/telescope

# Publish assets
docker-compose -f docker-compose.prod.yml exec app php artisan telescope:install
docker-compose -f docker-compose.prod.yml exec app php artisan migrate

# Restrict access in AppServiceProvider
```

---

## 🔐 Security Hardening

### 1. Change Default Passwords

✅ Database password  
✅ Redis password  
✅ Admin account password  
✅ SSH password/keys  

### 2. Disable Unused Services

```bash
# Remove PHPMyAdmin in production
# Commented in docker-compose.prod.yml
```

### 3. Setup Fail2Ban (SSH Protection)

```bash
sudo apt install fail2ban -y
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 4. Regular Updates

```bash
# Update system monthly
sudo apt update && sudo apt upgrade -y

# Update Docker images
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d
```

---

## 🔄 Deployment Workflow

### Update Application

```bash
# 1. Pull latest code
cd /var/www/jobmaker
git pull origin main

# 2. Update dependencies
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev

# 3. Run migrations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 4. Clear & optimize caches
docker-compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker-compose -f docker-compose.prod.yml exec app php artisan optimize

# 5. Restart containers (if needed)
docker-compose -f docker-compose.prod.yml restart app
```

---

## 🆘 Emergency Recovery

### Rollback Deployment

```bash
# 1. Git rollback
git revert HEAD
# or
git reset --hard <previous-commit-hash>

# 2. Rebuild
docker-compose -f docker-compose.prod.yml up -d --build

# 3. Rollback database (if needed)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:rollback --force
```

### Restore from Backup

```bash
# Stop application
docker-compose -f docker-compose.prod.yml down

# Restore database
gunzip < /var/backups/jobmaker/jobmaker_YYYYMMDD_HHMMSS.sql.gz | \
docker exec -i jobmaker_db mysql -ujobmaker_user -pYOUR_PASSWORD jobmaker_db

# Start application
docker-compose -f docker-compose.prod.yml up -d
```

---

## ✅ Production Checklist

Before going live:

- [ ] Environment set to `production`
- [ ] Debug mode disabled (`APP_DEBUG=false`)
- [ ] Strong passwords for all services
- [ ] SSL certificate installed and working
- [ ] Database backups automated
- [ ] Firewall configured properly
- [ ] PHPMyAdmin disabled in production
- [ ] Queue workers running
- [ ] Cron jobs configured
- [ ] Monitoring setup
- [ ] Error logging configured
- [ ] Email service configured
- [ ] Admin account created
- [ ] Testing completed on staging
- [ ] Domain DNS configured
- [ ] Nginx reverse proxy working
- [ ] Auto-restart enabled for containers

---

## 📚 Additional Resources

- [Docker Production Best Practices](https://docs.docker.com/develop/dev-best-practices/)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [Nginx Configuration](https://nginx.org/en/docs/)
- [Let's Encrypt](https://letsencrypt.org/getting-started/)

---

**🚀 Good luck with your deployment!**

For questions or issues, refer to [INSTALLATION.md](INSTALLATION.md) or open an issue.

---

*Last Updated: October 2025*

