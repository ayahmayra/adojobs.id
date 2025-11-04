# 👨‍💻 Development Guide - JobMaker Project

Panduan lengkap untuk development workflow, hot reload, dan best practices.

---

## 📋 Table of Contents

- [Hot Reload & Live Changes](#hot-reload--live-changes)
- [Development Workflow](#development-workflow)
- [When to Rebuild](#when-to-rebuild)
- [Common Tasks](#common-tasks)
- [Debugging](#debugging)
- [Best Practices](#best-practices)
- [Tips & Tricks](#tips--tricks)

---

## 🔥 Hot Reload & Live Changes

### ✅ Apa yang Langsung Terimplementasi?

Karena menggunakan **volume binding** (`./src:/app`), sebagian besar perubahan **LANGSUNG aktif tanpa rebuild**!

#### 1. **PHP Files (Controllers, Models, Services)** ✅
```bash
# Edit controller
nano src/app/Http/Controllers/JobController.php

# Langsung refresh browser → Perubahan terlihat!
# ❌ Tidak perlu restart
# ❌ Tidak perlu rebuild
```

**Contoh:**
```php
// Edit src/app/Http/Controllers/JobController.php
public function index()
{
    // Tambah filter baru
    $jobs = Job::where('status', 'published')
        ->latest()
        ->paginate(20); // Ubah dari 10 ke 20
    
    return view('jobs.index', compact('jobs'));
}
```
**Save → Refresh browser → Done!** ✅

---

#### 2. **Blade Templates/Views** ✅
```bash
# Edit view
nano src/resources/views/jobs/index.blade.php

# Langsung refresh browser → Perubahan terlihat!
```

**Contoh:**
```blade
{{-- Edit src/resources/views/jobs/index.blade.php --}}
<div class="job-card">
    <h2>{{ $job->title }}</h2>
    <p>{{ $job->company->name }}</p>
    {{-- Tambah informasi baru --}}
    <span class="badge">{{ $job->job_type }}</span>
</div>
```
**Save → Refresh browser → Done!** ✅

---

#### 3. **Routes** ✅
```bash
# Edit routes
nano src/routes/web.php

# Langsung bisa diakses!
```

**Contoh:**
```php
// Tambah route baru
Route::get('/jobs/featured', [JobController::class, 'featured'])
    ->name('jobs.featured');
```
**Save → Akses http://localhost:8080/jobs/featured → Done!** ✅

---

#### 4. **Public Assets (CSS/JS/Images)** ✅
```bash
# Edit CSS
nano src/public/css/custom.css

# Edit JS
nano src/public/js/app.js

# Langsung refresh browser → Perubahan terlihat!
```

**Contoh:**
```css
/* Edit src/public/css/custom.css */
.job-card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px; /* Ubah padding */
}
```
**Save → Refresh browser (Ctrl+F5 untuk hard refresh) → Done!** ✅

---

### ⚠️ Yang Perlu Action Tambahan (Tidak Perlu Rebuild)

#### 1. **Composer Dependencies**
```bash
# Setelah edit composer.json atau install package baru
docker-compose exec app composer install

# Atau dengan Makefile
make composer ARGS="install"

# Langsung bisa digunakan di code!
```

**Contoh:**
```bash
# Install package baru
docker-compose exec app composer require intervention/image

# Gunakan di code
use Intervention\Image\Facades\Image;
```

---

#### 2. **Config Files**
```bash
# Setelah edit file di src/config/
docker-compose exec app php artisan config:clear

# Atau
make clear
```

**Contoh:**
```php
// Edit src/config/services.php
'mailgun' => [
    'domain' => env('MAILGUN_DOMAIN'),
    'secret' => env('MAILGUN_SECRET'),
    'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
],

// Clear config agar perubahan aktif
docker-compose exec app php artisan config:clear
```

---

#### 3. **Environment Variables (.env)**
```bash
# Setelah edit .env
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear

# Atau
make clear
```

**Contoh:**
```env
# Edit src/.env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password

# Clear cache
make clear
```

---

#### 4. **Database Migrations**
```bash
# Buat migration baru
docker-compose exec app php artisan make:migration add_featured_to_jobs

# Edit migration file
nano src/database/migrations/xxxx_add_featured_to_jobs.php

# Run migration
docker-compose exec app php artisan migrate

# Atau dengan Makefile
make migrate
```

---

#### 5. **Database Seeders**
```bash
# Edit seeder
nano src/database/seeders/JobSeeder.php

# Run seeder
docker-compose exec app php artisan db:seed --class=JobSeeder

# Atau dengan Makefile
make seed
```

---

### 🔄 Yang Perlu REBUILD Container

#### 1. **Dockerfile Changes**
```bash
# Setelah edit Dockerfile
docker-compose down
docker-compose build app
docker-compose up -d
```

**Kapan perlu rebuild Dockerfile:**
- ✅ Tambah PHP extension baru
- ✅ Install system package (imagemagick, ffmpeg, dll)
- ✅ Ubah PHP configuration
- ✅ Ubah base image

**Contoh:**
```dockerfile
# Edit Dockerfile - tambah extension baru
RUN pecl install imagick \
    && docker-php-ext-enable imagick

# Rebuild required!
docker-compose build app
```

---

#### 2. **Docker Compose Changes**
```bash
# Setelah edit docker-compose.yml
docker-compose down
docker-compose up -d
```

**Kapan perlu restart:**
- ✅ Ubah port mapping
- ✅ Ubah environment variables di docker-compose
- ✅ Tambah service baru
- ✅ Ubah volume mapping

---

#### 3. **Caddyfile/Server Config**
```bash
# Setelah edit docker/frankenphp/Caddyfile
docker-compose restart app

# Atau rebuild jika perubahan besar
docker-compose build app
docker-compose restart app
```

---

## 📊 Quick Reference Table

| Jenis Perubahan | Hot Reload? | Action Required | Rebuild? |
|----------------|-------------|-----------------|----------|
| **Controllers** | ✅ Yes | None | ❌ No |
| **Models** | ✅ Yes | None | ❌ No |
| **Views (Blade)** | ✅ Yes | None | ❌ No |
| **Routes** | ✅ Yes | None | ❌ No |
| **Public Assets** | ✅ Yes | Hard refresh | ❌ No |
| **Composer Packages** | ✅ Yes | `composer install` | ❌ No |
| **Config Files** | ⚠️ Partial | `config:clear` | ❌ No |
| **.env File** | ⚠️ Partial | `cache:clear` | ❌ No |
| **Migrations** | ⚠️ Manual | `migrate` | ❌ No |
| **Seeders** | ⚠️ Manual | `db:seed` | ❌ No |
| **Dockerfile** | ❌ No | Rebuild | ✅ Yes |
| **docker-compose.yml** | ❌ No | Restart | ✅ Yes |
| **Caddyfile** | ❌ No | Restart/Rebuild | ✅ Yes |

---

## 🎯 Development Workflow

### Workflow 1: Membuat Fitur Baru

```bash
# 1. Buat branch baru (best practice)
git checkout -b feature/job-filtering

# 2. Generate controller, model, migration
docker-compose exec app php artisan make:controller FilterController
docker-compose exec app php artisan make:model Filter -m

# 3. Edit migration
nano src/database/migrations/xxxx_create_filters_table.php

# 4. Run migration
docker-compose exec app php artisan migrate

# 5. Edit controller
nano src/app/Http/Controllers/FilterController.php

# 6. Tambah routes
nano src/routes/web.php

# 7. Buat view
nano src/resources/views/filters/index.blade.php

# 8. Test di browser
# http://localhost:8080/filters

# 9. Jika ada error, lihat logs
docker-compose logs -f app

# 10. Commit changes
git add .
git commit -m "Add job filtering feature"
```

---

### Workflow 2: Debugging Issue

```bash
# 1. Lihat logs real-time
docker-compose logs -f app

# 2. Check Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# 3. Tambah debugging code
# Edit controller dan tambah dd() atau Log::info()

# 4. Test di browser

# 5. Clear cache jika perlu
docker-compose exec app php artisan cache:clear

# 6. Restart container jika masih error
docker-compose restart app
```

---

### Workflow 3: Database Changes

```bash
# 1. Buat migration
docker-compose exec app php artisan make:migration add_status_to_applications

# 2. Edit migration
nano src/database/migrations/xxxx_add_status_to_applications.php

# 3. Run migration
docker-compose exec app php artisan migrate

# 4. Jika error, rollback
docker-compose exec app php artisan migrate:rollback

# 5. Fix migration, run again
docker-compose exec app php artisan migrate

# 6. Update model jika perlu
nano src/app/Models/Application.php
```

---

### Workflow 4: Install Package Baru

```bash
# 1. Install via composer
docker-compose exec app composer require vendor/package

# 2. Publish config jika perlu
docker-compose exec app php artisan vendor:publish --provider="VendorServiceProvider"

# 3. Edit config
nano src/config/package.php

# 4. Clear cache
docker-compose exec app php artisan config:clear

# 5. Gunakan di code
# Langsung bisa digunakan!
```

---

## 🐛 Debugging

### 1. Laravel Telescope (Recommended)

```bash
# Install Telescope
docker-compose exec app composer require laravel/telescope --dev

# Install & migrate
docker-compose exec app php artisan telescope:install
docker-compose exec app php artisan migrate

# Access at: http://localhost:8080/telescope
```

### 2. Laravel Debugbar

```bash
# Install Debugbar
docker-compose exec app composer require barryvdh/laravel-debugbar --dev

# Clear config
docker-compose exec app php artisan config:clear

# Debugbar akan muncul di browser
```

### 3. dd() & dump()

```php
// Di controller
public function index()
{
    $jobs = Job::all();
    
    // Debug output
    dd($jobs); // Die and dump
    // atau
    dump($jobs); // Dump saja, lanjut eksekusi
    
    return view('jobs.index', compact('jobs'));
}
```

### 4. Log::debug()

```php
use Illuminate\Support\Facades\Log;

public function store(Request $request)
{
    Log::info('Job application submitted', [
        'job_id' => $request->job_id,
        'user_id' => auth()->id(),
    ]);
    
    // ... rest of code
}

// Lihat di storage/logs/laravel.log
```

### 5. Query Debugging

```php
// Enable query log
DB::enableQueryLog();

$jobs = Job::where('status', 'published')->get();

// Lihat queries
dd(DB::getQueryLog());
```

---

## ✅ Best Practices

### 1. **Keep Containers Running**
```bash
# Jangan stop/start terus-menerus
# Biarkan running saat development

# Check status
docker-compose ps

# Jika ada yang error, restart specific container
docker-compose restart app
```

### 2. **Clear Cache Regularly**
```bash
# Setiap kali ubah .env atau config
make clear

# Atau manual
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### 3. **Monitor Logs**
```bash
# Terminal 1: Development
nano src/app/Http/Controllers/JobController.php

# Terminal 2: Logs
docker-compose logs -f app

# Lihat error real-time
```

### 4. **Use Git Branches**
```bash
# Fitur baru = branch baru
git checkout -b feature/new-feature

# Develop di branch
# Test
# Merge ke main
```

### 5. **Backup Database Regularly**
```bash
# Backup sebelum migration besar
docker-compose exec db mysqldump -u jobmaker_user -pjobmaker_password jobmaker_db > backup.sql

# Restore jika perlu
cat backup.sql | docker-compose exec -T db mysql -u jobmaker_user -pjobmaker_password jobmaker_db
```

---

## 💡 Tips & Tricks

### 1. **Auto-save di VS Code**
```json
// settings.json
{
  "files.autoSave": "afterDelay",
  "files.autoSaveDelay": 1000
}
```

### 2. **Multiple Terminal Setup**
```bash
# Terminal 1: Logs
docker-compose logs -f app

# Terminal 2: Commands
docker-compose exec app bash

# Terminal 3: Editor
code .
```

### 3. **Laravel Tinker untuk Testing**
```bash
# Masuk ke Tinker
docker-compose exec app php artisan tinker

# Test code
>>> $jobs = App\Models\Job::count();
>>> echo $jobs;

# Test relationships
>>> $job = App\Models\Job::first();
>>> $job->employer->company_name;
```

### 4. **Quick Database Reset**
```bash
# Reset database dengan data fresh
docker-compose exec app php artisan migrate:fresh --seed

# Atau dengan Makefile
make fresh
```

### 5. **Artisan Shortcuts**
```bash
# Buat controller + model + migration sekaligus
docker-compose exec app php artisan make:model JobAlert -mcr
# m = migration
# c = controller
# r = resource controller

# Clear everything
docker-compose exec app php artisan optimize:clear
```

### 6. **Live Asset Compilation (Optional)**
```bash
# Install Node dependencies
docker-compose exec app npm install

# Run dev server (auto-compile assets)
docker-compose exec app npm run dev

# Atau build untuk production
docker-compose exec app npm run build
```

---

## 🚀 Performance Tips

### Development Mode

```bash
# Disable unnecessary caching
# Already in .env:
APP_ENV=local
APP_DEBUG=true
CACHE_DRIVER=redis # Fast enough for dev

# Enable query logging
DB_QUERY_LOG=true
```

### Faster Composer Install

```bash
# Use cache
docker-compose exec app composer install --prefer-dist

# Parallel downloads
docker-compose exec app composer install --prefer-dist --optimize-autoloader
```

### Faster Docker Builds

```bash
# Use build cache
docker-compose build

# Only rebuild when necessary
# Don't use --no-cache unless required
```

---

## ⚡ Common Issues & Solutions

### Issue 1: Changes Not Reflecting

**Problem:** Edit file tapi tidak ada perubahan di browser

**Solutions:**
```bash
# 1. Hard refresh browser
Ctrl + F5 (Windows/Linux)
Cmd + Shift + R (Mac)

# 2. Clear Laravel cache
docker-compose exec app php artisan cache:clear

# 3. Clear browser cache
Settings → Clear browsing data

# 4. Check file was saved
ls -la src/app/Http/Controllers/JobController.php

# 5. Restart container as last resort
docker-compose restart app
```

---

### Issue 2: Composer Changes Not Working

**Problem:** Install package tapi error "Class not found"

**Solutions:**
```bash
# 1. Make sure package installed
docker-compose exec app composer show | grep package-name

# 2. Regenerate autoload
docker-compose exec app composer dump-autoload

# 3. Clear config
docker-compose exec app php artisan config:clear

# 4. Reinstall if needed
docker-compose exec app composer install
```

---

### Issue 3: Migration Errors

**Problem:** Migration failed dengan error constraint

**Solutions:**
```bash
# 1. Rollback last migration
docker-compose exec app php artisan migrate:rollback

# 2. Fix migration file
nano src/database/migrations/xxxx_problem_migration.php

# 3. Try again
docker-compose exec app php artisan migrate

# 4. If still error, check foreign keys
# Make sure parent table exists first

# 5. Last resort: fresh migration (DANGER: deletes data!)
docker-compose exec app php artisan migrate:fresh --seed
```

---

### Issue 4: Permission Errors

**Problem:** "Permission denied" saat write files

**Solutions:**
```bash
# Fix storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache

# Verify
docker-compose exec app ls -la storage
```

---

## 📚 Additional Resources

### Laravel Documentation
- [Controllers](https://laravel.com/docs/controllers)
- [Routing](https://laravel.com/docs/routing)
- [Blade Templates](https://laravel.com/docs/blade)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Migrations](https://laravel.com/docs/migrations)

### Docker Documentation
- [Docker Volumes](https://docs.docker.com/storage/volumes/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Best Practices](https://docs.docker.com/develop/dev-best-practices/)

### Development Tools
- [Laravel Telescope](https://laravel.com/docs/telescope)
- [Laravel Debugbar](https://github.com/barryvdh/laravel-debugbar)
- [VS Code Laravel Extension](https://marketplace.visualstudio.com/items?itemName=amiralizadeh9480.laravel-extra-intellisense)

---

## 🎯 Summary

### ✅ Hot Reload Works For:
- PHP files (Controllers, Models, Services)
- Blade templates
- Routes
- Public assets
- Config files (with cache clear)

### ⚠️ Requires Action For:
- Composer packages (install)
- Environment variables (clear cache)
- Migrations (run migrate)
- Seeders (run seed)

### ❌ Requires Rebuild For:
- Dockerfile changes
- PHP extensions
- System packages
- Server configuration

### 🚀 Best Practice:
1. Keep containers running
2. Monitor logs in separate terminal
3. Clear cache when needed
4. Use git branches for features
5. Backup before big changes

---

**Happy Coding! 🎉**

Need help? Check other documentation:
- [QUICK_START.md](QUICK_START.md) - Setup guide
- [INSTALLATION.md](INSTALLATION.md) - Installation details
- [README.md](README.md) - Project overview

---

*Last Updated: October 2025*

