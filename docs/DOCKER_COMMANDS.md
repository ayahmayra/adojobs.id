# Docker Commands Reference untuk JobMaker Project

## 🐳 Docker Container Status

### Cek Status Container
```bash
docker-compose ps
```

**Output:**
```
NAME                  IMAGE                 COMMAND                  SERVICE      STATUS
jobmaker_app          jobmakerproject-app   "docker-php-entrypoi…"   app          Up 19 hours (healthy)
jobmaker_db           mariadb:11.2          "docker-entrypoint.s…"   db           Up 19 hours
jobmaker_phpmyadmin   phpmyadmin:latest     "/docker-entrypoint.…"   phpmyadmin   Up 19 hours
jobmaker_redis        redis:7-alpine        "docker-entrypoint.s…"   redis        Up 19 hours
```

## 🚀 Laravel Artisan Commands via Docker

### Format Umum
```bash
docker-compose exec app php artisan [command]
```

### Contoh Commands

#### 1. **Update Category Icons**
```bash
docker-compose exec app php artisan categories:update-icons
```

#### 2. **Run Migration**
```bash
docker-compose exec app php artisan migrate
```

#### 3. **Run Specific Migration**
```bash
docker-compose exec app php artisan migrate --path=/database/migrations/2025_10_17_014152_update_category_icons_to_emoji.php
```

#### 4. **Rollback Migration**
```bash
docker-compose exec app php artisan migrate:rollback
```

#### 5. **Run Seeder**
```bash
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan db:seed --class=UpdateCategoryIconsSeeder
```

#### 6. **Clear Cache**
```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

#### 7. **Tinker (Laravel REPL)**
```bash
docker-compose exec app php artisan tinker
```

**Atau execute langsung:**
```bash
docker-compose exec app php artisan tinker --execute="use App\Models\Category; Category::all(['id', 'name', 'icon'])->each(function(\$cat) { echo \$cat->id . ': ' . \$cat->name . ' -> ' . \$cat->icon . PHP_EOL; });"
```

#### 8. **Create Migration**
```bash
docker-compose exec app php artisan make:migration create_something_table
```

#### 9. **Create Controller**
```bash
docker-compose exec app php artisan make:controller SomethingController
```

#### 10. **Create Model**
```bash
docker-compose exec app php artisan make:model Something -m
```

## 🗄️ Database Commands

### 1. **Access MySQL/MariaDB**
```bash
docker-compose exec db mysql -u root -p
# Password: root
```

### 2. **Execute SQL Query**
```bash
docker-compose exec db mysql -u root -proot jobmaker -e "SELECT id, name, icon FROM categories;"
```

### 3. **Export Database**
```bash
docker-compose exec db mysqldump -u root -proot jobmaker > backup.sql
```

### 4. **Import Database**
```bash
docker-compose exec -T db mysql -u root -proot jobmaker < backup.sql
```

### 5. **Check Categories Data**
```bash
docker-compose exec db mysql -u root -proot jobmaker -e "SELECT id, name, icon, LENGTH(icon) as icon_length FROM categories;"
```

## 🔧 Container Management

### 1. **Start Containers**
```bash
docker-compose up -d
```

### 2. **Stop Containers**
```bash
docker-compose down
```

### 3. **Restart Containers**
```bash
docker-compose restart
```

### 4. **Restart Specific Service**
```bash
docker-compose restart app
docker-compose restart db
```

### 5. **View Logs**
```bash
# All containers
docker-compose logs -f

# Specific container
docker-compose logs -f app
docker-compose logs -f db
```

### 6. **Enter Container Shell**
```bash
# App container (PHP)
docker-compose exec app bash

# Database container
docker-compose exec db bash

# Redis container
docker-compose exec redis sh
```

## 📦 Composer Commands

### 1. **Install Dependencies**
```bash
docker-compose exec app composer install
```

### 2. **Update Dependencies**
```bash
docker-compose exec app composer update
```

### 3. **Add Package**
```bash
docker-compose exec app composer require package/name
```

### 4. **Remove Package**
```bash
docker-compose exec app composer remove package/name
```

### 5. **Dump Autoload**
```bash
docker-compose exec app composer dump-autoload
```

## 🧪 Testing Commands

### 1. **Run PHPUnit Tests**
```bash
docker-compose exec app php artisan test
```

### 2. **Run Specific Test**
```bash
docker-compose exec app php artisan test --filter=CategoryTest
```

### 3. **Run with Coverage**
```bash
docker-compose exec app php artisan test --coverage
```

## 🔍 Debugging Commands

### 1. **Check PHP Version**
```bash
docker-compose exec app php -v
```

### 2. **Check Laravel Version**
```bash
docker-compose exec app php artisan --version
```

### 3. **List All Routes**
```bash
docker-compose exec app php artisan route:list
```

### 4. **Check Environment**
```bash
docker-compose exec app php artisan env
```

### 5. **Check Database Connection**
```bash
docker-compose exec app php artisan db:show
```

## 🎯 Emoji Icon Fix Commands

### 1. **Update Icons dari Text ke Emoji**
```bash
docker-compose exec app php artisan categories:update-icons
```

**Output:**
```
Updating category icons from text to emoji...
Updated 1 categories from 'computer' to '💻'
Updated 1 categories from 'megaphone' to '📢'
Updated 1 categories from 'palette' to '🎨'
Updated 1 categories from 'calculator' to '🧮'
Updated 1 categories from 'users' to '👥'
Updated 1 categories from 'cog' to '⚙️'
Updated 1 categories from 'heart' to '❤️'
Updated 1 categories from 'briefcase' to '💼'
Updated 1 categories from 'book' to '📚'
Total categories updated: 9
Category icon update completed!
```

### 2. **Verify Icons**
```bash
docker-compose exec app php artisan tinker --execute="use App\Models\Category; Category::all(['id', 'name', 'icon'])->each(function(\$cat) { echo \$cat->id . ': ' . \$cat->name . ' -> ' . \$cat->icon . PHP_EOL; });"
```

**Output:**
```
1: Information Technology -> 💻
2: Marketing & Sales -> 📢
3: Design & Creative -> 🎨
4: Finance & Accounting -> 🧮
5: Human Resources -> 👥
6: Engineering -> ⚙️
7: Healthcare & Medical -> ❤️
8: Education & Training -> 📚
9: Customer Service -> 👨‍💻
10: Administrative -> 💼
```

### 3. **Run Migration untuk Update Icons**
```bash
docker-compose exec app php artisan migrate --path=/database/migrations/2025_10_17_014152_update_category_icons_to_emoji.php
```

## 🌐 Access URLs

- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Redis**: localhost:6379
- **MariaDB**: localhost:3306

## 📝 Common Issues & Solutions

### Issue 1: Container tidak running
```bash
# Check status
docker-compose ps

# Start containers
docker-compose up -d
```

### Issue 2: Database connection error
```bash
# Restart database
docker-compose restart db

# Check database logs
docker-compose logs db
```

### Issue 3: Permission issues
```bash
# Fix permissions
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chmod -R 775 /var/www/storage
```

### Issue 4: Cache issues
```bash
# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

## 🔐 Database Credentials

**From Docker Compose:**
```
DB_HOST=db
DB_PORT=3306
DB_DATABASE=jobmaker
DB_USERNAME=root
DB_PASSWORD=root
```

## 💡 Tips

1. **Always use `docker-compose exec app`** untuk menjalankan Laravel commands
2. **Jangan lupa `-d` flag** saat menjalankan `docker-compose up` untuk detached mode
3. **Use `-f` flag** dengan `logs` untuk follow/real-time logs
4. **Backup database** secara regular menggunakan `mysqldump`
5. **Check logs** jika ada error: `docker-compose logs -f app`

## 🎉 Quick Start Checklist

- [ ] `docker-compose ps` - Check container status
- [ ] `docker-compose exec app php artisan migrate` - Run migrations
- [ ] `docker-compose exec app php artisan categories:update-icons` - Update icons
- [ ] Visit http://localhost:8080/admin/categories - Verify icons
- [ ] `docker-compose logs -f app` - Monitor logs

---

**Last Updated**: 17 Oktober 2025  
**Status**: ✅ All commands tested with Docker
