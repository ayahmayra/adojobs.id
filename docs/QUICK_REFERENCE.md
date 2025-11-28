# 🚀 Quick Reference - JobMaker Project

## 🐳 Docker Commands (Most Used)

```bash
# Check container status
docker-compose ps

# Start all containers
docker-compose up -d

# Stop all containers
docker-compose down

# Restart containers
docker-compose restart

# View logs
docker-compose logs -f app
```

## 🎯 Laravel Commands via Docker

```bash
# Run migrations
docker-compose exec app php artisan migrate

# Run seeder
docker-compose exec app php artisan db:seed

# Clear cache
docker-compose exec app php artisan cache:clear

# Update category icons
docker-compose exec app php artisan categories:update-icons

# Tinker (Laravel REPL)
docker-compose exec app php artisan tinker
```

## 🗄️ Database Quick Commands

```bash
# Access MySQL
docker-compose exec db mysql -u root -proot jobmaker

# Check categories
docker-compose exec db mysql -u root -proot jobmaker -e "SELECT id, name, icon FROM categories;"

# Backup database
docker-compose exec db mysqldump -u root -proot jobmaker > backup.sql
```

## 🌐 Access URLs

| Service     | URL                        | Credentials        |
|-------------|----------------------------|-------------------|
| Application | http://localhost:8080      | N/A               |
| phpMyAdmin  | http://localhost:8081      | root/root         |
| MariaDB     | localhost:3306             | root/root         |
| Redis       | localhost:6379             | N/A               |

## 📋 Admin URLs

| Feature              | URL                           |
|---------------------|-------------------------------|
| Admin Dashboard     | /admin/dashboard              |
| Category Management | /admin/categories             |
| Add Category        | /admin/categories/create      |
| Edit Category       | /admin/categories/{id}/edit   |

## 🎨 Category Icons Reference

| Category Type        | Emoji | Code      |
|---------------------|-------|-----------|
| IT & Technology     | 💻    | computer  |
| Marketing & Sales   | 📢    | megaphone |
| Design & Creative   | 🎨    | palette   |
| Finance & Accounting| 🧮    | calculator|
| Human Resources     | 👥    | users     |
| Engineering         | ⚙️    | cog       |
| Healthcare          | ❤️    | heart     |
| Education           | 📚    | book      |
| Administrative      | 💼    | briefcase |

## 🔧 Troubleshooting Quick Fixes

### Issue: Icons showing as text
```bash
# Fix 1: Update icons in database
docker-compose exec app php artisan categories:update-icons

# Fix 2: Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan view:clear

# Fix 3: Check browser console for JS errors
# Open browser DevTools > Console
```

### Issue: Database connection error
```bash
# Restart database
docker-compose restart db

# Check database is running
docker-compose ps

# Check database logs
docker-compose logs db
```

### Issue: Application error
```bash
# Check logs
docker-compose logs -f app

# Clear all caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Restart app
docker-compose restart app
```

## 📚 Documentation Files

| File                              | Description                          |
|----------------------------------|--------------------------------------|
| `DOCKER_COMMANDS.md`             | Complete Docker commands reference   |
| `EMOJI_ICON_TROUBLESHOOTING.md`  | Emoji icon troubleshooting guide     |
| `EMOJI_ICON_FIX_SUMMARY.md`      | Summary of emoji icon fix            |
| `ADMIN_CATEGORIES.md`            | Category management documentation    |
| `NOTIFICATION_FIX.md`            | Notification system fix              |
| `QUICK_REFERENCE.md`             | This file                            |

## 🎯 Common Tasks

### Create New Category
1. Go to `/admin/categories`
2. Click "Add New Category"
3. Fill form:
   - Name: Category name
   - Icon: Emoji (e.g., 💻)
   - Description: Short description
   - Order: Display order
   - Is Active: Checkbox
4. Click "Create Category"

### Update Category Icon
1. Go to `/admin/categories`
2. Click "Edit" on category
3. Change icon field to emoji
4. Click "Update Category"

### Bulk Update Icons (Database)
```bash
# Run artisan command
docker-compose exec app php artisan categories:update-icons

# Or run migration
docker-compose exec app php artisan migrate --path=/database/migrations/2025_10_17_014152_update_category_icons_to_emoji.php
```

## 🔍 Debugging Commands

```bash
# Check PHP version
docker-compose exec app php -v

# Check Laravel version
docker-compose exec app php artisan --version

# List routes
docker-compose exec app php artisan route:list

# Check database connection
docker-compose exec app php artisan db:show

# Run tinker to query data
docker-compose exec app php artisan tinker
```

## ⚡ Performance

```bash
# Optimize application
docker-compose exec app php artisan optimize

# Cache config
docker-compose exec app php artisan config:cache

# Cache routes
docker-compose exec app php artisan route:cache

# Cache views
docker-compose exec app php artisan view:cache
```

## 🧪 Testing

```bash
# Run all tests
docker-compose exec app php artisan test

# Run specific test
docker-compose exec app php artisan test --filter=CategoryTest

# Run with coverage
docker-compose exec app php artisan test --coverage
```

## 📦 Package Management

```bash
# Install dependencies
docker-compose exec app composer install

# Update dependencies
docker-compose exec app composer update

# Add new package
docker-compose exec app composer require vendor/package

# Dump autoload
docker-compose exec app composer dump-autoload
```

## 🛡️ Security

```bash
# Generate app key
docker-compose exec app php artisan key:generate

# Clear sensitive cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
```

## 💾 Backup & Restore

```bash
# Backup database
docker-compose exec db mysqldump -u root -proot jobmaker > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore database
docker-compose exec -T db mysql -u root -proot jobmaker < backup.sql

# Backup entire project
tar -czf jobmaker_backup_$(date +%Y%m%d_%H%M%S).tar.gz /Users/hermansyah/dev/jobmakerproject
```

## 🔗 Useful Links

- Laravel Docs: https://laravel.com/docs
- Docker Docs: https://docs.docker.com
- Tailwind CSS: https://tailwindcss.com/docs
- Emoji Reference: https://emojipedia.org

---

**Last Updated**: 17 Oktober 2025  
**Project**: JobMaker  
**Stack**: Laravel + Docker + Tailwind CSS
