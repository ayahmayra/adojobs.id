# Production Migration & Seeding Guide

## 📋 Overview
Panduan untuk menjalankan migrations dan seeders di production server dengan penanganan error yang umum terjadi.

---

## 🎯 Migration Order (Important!)

### **Urutan Migration yang Benar:**

```bash
# 1. Base tables first
0001_01_01_000000_create_users_table.php
0001_01_01_000001_create_cache_table.php
0001_01_01_000002_create_jobs_table.php

# 2. Application tables
2024_10_14_000001_create_categories_table.php
2024_10_14_000002_create_seekers_table.php
2024_10_14_000003_create_employers_table.php
2024_10_14_000004_create_jobs_table.php
2024_10_14_000005_create_applications_table.php
2024_10_14_000006_create_saved_jobs_table.php
2024_10_14_000007_create_settings_table.php

# 3. Feature additions
2025_10_15_013455_add_avatar_to_users_table.php
2025_10_15_033128_add_resume_slug_to_users_table.php
2025_10_15_075055_create_conversations_table.php
2025_10_15_075104_create_messages_table.php
2025_10_17_014152_update_category_icons_to_emoji.php
2025_10_17_022027_add_slug_to_employers_table.php
2025_10_21_014056_add_admin_support_to_conversations_table.php
2025_10_21_021819_create_articles_table.php
2025_10_21_031106_remove_views_count_from_articles_table.php
2025_10_21_060604_add_phone_address_to_users_table.php  # ⚠️ IMPORTANT
```

---

## 🚀 Step-by-Step Production Migration

### **Step 1: Check Current Migration Status**
```bash
# SSH to production server
ssh user@your-server-ip

# Navigate to project
cd /var/www/adojobs.id

# Check migration status
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

### **Step 2: Run Fresh Migration (First Time)**
```bash
# ⚠️ WARNING: This will DROP all tables and recreate them
# Only use on first deployment or when database is empty

docker-compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --force
```

### **Step 3: Run Incremental Migration (Updates)**
```bash
# For production updates (safe, won't drop data)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Check what was migrated
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:status
```

### **Step 4: Verify Table Structure**
```bash
# Check if phone and address columns exist
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

# In tinker:
>>> Schema::hasColumn('users', 'phone');     // Should return true
>>> Schema::hasColumn('users', 'address');   // Should return true
>>> Schema::getColumnListing('users');       // List all columns
>>> exit
```

---

## 🌱 Seeding Production Database

### **Step 1: Check Admin User Exists**
```bash
# Before seeding, ensure admin user exists
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

# In tinker:
>>> \App\Models\User::where('role', 'admin')->count();
# If 0, you need to create admin first
>>> exit
```

### **Step 2: Create Admin User (if needed)**
```bash
# Option 1: Via tinker
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

>>> $admin = \App\Models\User::create([
...     'name' => 'Admin AdoJobs',
...     'email' => 'admin@adojobs.id',
...     'password' => bcrypt('YourSecurePassword123!'),
...     'role' => 'admin',
...     'email_verified_at' => now(),
... ]);
>>> exit

# Option 2: Via seeder
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=UserSeeder --force
```

### **Step 3: Run Local Data Seeders**
```bash
# Seed local categories
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalCategorySeeder --force

# Seed local seekers
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalSeekerSeeder --force

# Seed local employers
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalEmployerSeeder --force

# Seed local jobs
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalJobSeeder --force

# Seed local articles
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalArticleSeeder --force
```

### **Step 4: Run All Local Seeders at Once**
```bash
# Run all local seeders via LocalDataSeeder
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force
```

### **Step 5: Verify Seeded Data**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

# Verify data:
>>> \App\Models\Category::count();   // Should show categories
>>> \App\Models\User::where('role', 'seeker')->count();   // Should show seekers
>>> \App\Models\User::where('role', 'employer')->count(); // Should show employers
>>> \App\Models\Job::count();        // Should show jobs
>>> \App\Models\Article::count();    // Should show articles
>>> exit
```

---

## 🆘 Common Errors & Solutions

### **Error 1: Column not found 'phone'**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'phone' in 'INSERT INTO'
```

**Solution:**
```bash
# Migration not run yet
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Verify
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> Schema::hasColumn('users', 'phone');
```

### **Error 2: Column not found 'address'**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'address' in 'INSERT INTO'
```

**Solution:**
```bash
# Same as above, run migration
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### **Error 3: Column already exists**
```
SQLSTATE[42S21]: Column already exists: 1060 Duplicate column name 'phone'
```

**Solution:**
```bash
# Migration already run, safe to ignore
# Or rollback and re-run if needed:
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:rollback --step=1
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### **Error 4: No admin user found**
```
No admin user found. Please create an admin user first.
```

**Solution:**
```bash
# Create admin user first
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

>>> \App\Models\User::create([
...     'name' => 'Admin AdoJobs',
...     'email' => 'admin@adojobs.id',
...     'password' => bcrypt('YourSecurePassword123!'),
...     'role' => 'admin',
...     'email_verified_at' => now(),
... ]);
>>> exit

# Then run seeders
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force
```

### **Error 5: Foreign key constraint fails**
```
SQLSTATE[23000]: Integrity constraint violation
```

**Solution:**
```bash
# Check foreign key references exist
# Make sure parent records exist before creating child records
# Run seeders in correct order:

1. Categories (no dependencies)
2. Users (no dependencies)
3. Seekers (depends on Users)
4. Employers (depends on Users)
5. Jobs (depends on Employers, Categories)
6. Articles (depends on Users/admin)
```

---

## 🔧 Migration Best Practices

### **1. Always Check Column Existence**
```php
// In migration up() method
if (!Schema::hasColumn('users', 'phone')) {
    $table->string('phone')->nullable()->after('role');
}
```

### **2. Always Use --force Flag in Production**
```bash
# Production requires --force flag
php artisan migrate --force
php artisan db:seed --force
```

### **3. Backup Before Migration**
```bash
# Always backup before major migrations
docker-compose -f docker-compose.prod.yml exec db \
    mysqldump -u adojobs_user -p adojobs_production > backup_before_migration_$(date +%Y%m%d).sql
```

### **4. Test Migrations in Staging First**
```bash
# Test in local/staging environment first
docker-compose exec app php artisan migrate:fresh --seed

# If successful, then deploy to production
```

---

## 📊 Complete Production Setup Workflow

### **Fresh Installation (New Server):**

```bash
# 1. Clone repository
cd /var/www
git clone <repo-url> adojobs.id
cd adojobs.id

# 2. Configure environment
cp src/.env.example src/.env
nano src/.env  # Set production values

# 3. Build and start
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# 4. Install dependencies
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# 5. Generate key
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate --force

# 6. Link storage
docker-compose -f docker-compose.prod.yml exec app php artisan storage:link

# 7. Run migrations (FRESH - first time only)
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --force

# 8. Create admin user manually
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> \App\Models\User::create(['name' => 'Admin', 'email' => 'admin@adojobs.id', 'password' => bcrypt('SecurePassword123!'), 'role' => 'admin', 'email_verified_at' => now()]);
>>> exit

# 9. Run seeders
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force

# 10. Optimize
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# 11. Verify
curl -I http://localhost:8282
```

### **Update Existing Installation:**

```bash
# 1. Backup first!
docker-compose -f docker-compose.prod.yml exec db \
    mysqldump -u adojobs_user -p adojobs_production > backup_$(date +%Y%m%d).sql

# 2. Pull latest code
git pull origin main

# 3. Rebuild if Dockerfile changed
docker-compose -f docker-compose.prod.yml build --no-cache app

# 4. Stop and start
docker-compose -f docker-compose.prod.yml down
docker-compose -f docker-compose.prod.yml up -d

# 5. Update dependencies
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader

# 6. Run new migrations only
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# 7. Clear cache
docker-compose -f docker-compose.prod.yml exec app php artisan cache:clear
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache

# 8. Restart queue workers
sudo systemctl restart adojobs-queue
```

---

## 🔍 Verification Commands

### **Check Database Structure:**
```bash
# List all tables
docker-compose -f docker-compose.prod.yml exec db mysql -u adojobs_user -p adojobs_production -e "SHOW TABLES;"

# Check users table structure
docker-compose -f docker-compose.prod.yml exec db mysql -u adojobs_user -p adojobs_production -e "DESCRIBE users;"

# Check specific columns
docker-compose -f docker-compose.prod.yml exec app php artisan tinker
>>> Schema::getColumnListing('users');
```

### **Check Seeded Data:**
```bash
docker-compose -f docker-compose.prod.yml exec app php artisan tinker

# Check counts:
>>> \App\Models\User::count();
>>> \App\Models\Category::count();
>>> \App\Models\Job::count();
>>> \App\Models\Employer::count();
>>> \App\Models\Seeker::count();
>>> \App\Models\Article::count();

# Check specific data:
>>> \App\Models\User::where('role', 'admin')->first();
>>> \App\Models\Category::where('slug', 'pertanian-perkebunan')->first();
>>> \App\Models\Job::where('status', 'published')->count();
```

---

## 📚 Required Migrations for Seeders

### **LocalDataSeeder Requires:**

1. ✅ **Users table with:**
   - `id`, `name`, `email`, `password`, `role`
   - `phone` (nullable) ← **Migration: 2025_10_21_060604**
   - `address` (nullable) ← **Migration: 2025_10_21_060604**
   - `resume_slug` (nullable) ← **Migration: 2025_10_15_033128**
   - `avatar` (nullable) ← **Migration: 2025_10_15_013455**

2. ✅ **Categories table** (all fields from base migration)

3. ✅ **Seekers table** (depends on users)

4. ✅ **Employers table with:**
   - All base fields
   - `slug` ← **Migration: 2025_10_17_022027**

5. ✅ **Jobs table** (all fields from base migration)

6. ✅ **Articles table:**
   - All base fields
   - **Without** `views_count` ← **Migration: 2025_10_21_031106 removed it**

---

## 🎯 Seeder Execution Order

### **Correct Order:**
```bash
# 1. Categories (no dependencies)
LocalCategorySeeder

# 2. Seekers (depends on: users table structure)
LocalSeekerSeeder
  └─ Creates: Users (role: seeker)
  └─ Creates: Seeker profiles

# 3. Employers (depends on: users table structure)
LocalEmployerSeeder
  └─ Creates: Users (role: employer)
  └─ Creates: Employer profiles

# 4. Jobs (depends on: employers, categories)
LocalJobSeeder
  └─ Requires: Employers must exist
  └─ Requires: Categories must exist

# 5. Articles (depends on: admin user)
LocalArticleSeeder
  └─ Requires: Admin user must exist
```

---

## 🔧 Troubleshooting Migrations

### **Issue: Migration table not found**
```bash
# Create migrations table
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:install
```

### **Issue: Migration already run but want to re-run**
```bash
# Rollback specific migration
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:rollback --step=1

# Re-run
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### **Issue: Need to reset all migrations**
```bash
# ⚠️ WARNING: This will drop all tables
docker-compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --force

# Then re-seed
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=LocalDataSeeder --force
```

### **Issue: Specific migration fails**
```bash
# Check error message
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force

# Fix migration file
nano src/database/migrations/xxxx_migration_name.php

# Re-run
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

---

## 📋 Pre-Migration Checklist

**Before running migrations:**
- [ ] Backup database
- [ ] Check migration files syntax
- [ ] Test migrations in local/staging
- [ ] Review migration order
- [ ] Check dependencies (foreign keys)
- [ ] Verify .env configuration
- [ ] Check database connection

**During migration:**
- [ ] Monitor logs for errors
- [ ] Check migration status after each step
- [ ] Verify table structure
- [ ] Test application functionality

**After migration:**
- [ ] Clear cache (config, route, view)
- [ ] Test critical features
- [ ] Monitor application logs
- [ ] Check database integrity

---

## 🎯 Production Migration Commands Quick Reference

```bash
# Check status
php artisan migrate:status

# Run pending migrations
php artisan migrate --force

# Rollback last batch
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=1

# Fresh migration (⚠️ drops all tables)
php artisan migrate:fresh --force

# Fresh with seed
php artisan migrate:fresh --seed --force

# Specific seeder
php artisan db:seed --class=LocalDataSeeder --force

# Check migrations table
php artisan tinker
>>> DB::table('migrations')->get();
```

---

## 💡 Best Practices

### **1. Always Backup Before Migration:**
```bash
# Automated backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
docker-compose -f docker-compose.prod.yml exec db \
    mysqldump -u adojobs_user -p adojobs_production \
    > /var/www/adojobs.id/backups/pre_migration_$DATE.sql
```

### **2. Test Migrations Locally First:**
```bash
# Local testing
docker-compose exec app php artisan migrate:fresh --seed

# If successful, deploy to production
```

### **3. Use Transactions When Possible:**
```php
// In migration
public function up(): void
{
    DB::transaction(function () {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable();
        });
    });
}
```

### **4. Add Column Existence Checks:**
```php
// Safe migration
if (!Schema::hasColumn('users', 'phone')) {
    $table->string('phone')->nullable();
}
```

### **5. Document Breaking Changes:**
```bash
# In commit message
git commit -m "feat: Add phone and address to users table

BREAKING CHANGE: Requires migration before seeding
Run: php artisan migrate --force"
```

---

## 🎯 Summary

**Migration Process:**
1. ✅ Backup database
2. ✅ Run migrations with --force
3. ✅ Verify table structure
4. ✅ Run seeders in correct order
5. ✅ Verify seeded data
6. ✅ Clear cache
7. ✅ Test application

**Key Points:**
- ✅ Always use --force in production
- ✅ Check column existence before adding
- ✅ Backup before migrations
- ✅ Test locally first
- ✅ Run seeders in correct order
- ✅ Verify data after seeding

**Seeder Order:**
1. LocalCategorySeeder (no deps)
2. LocalSeekerSeeder (needs: phone, address in users)
3. LocalEmployerSeeder (needs: phone, address in users)
4. LocalJobSeeder (needs: employers, categories)
5. LocalArticleSeeder (needs: admin user)

---

**Updated**: October 21, 2025  
**Author**: AI Assistant  
**Version**: 1.0  
**Status**: ✅ Production Ready

---

🎉 **Production Migration & Seeding Guide Complete!**

Gunakan panduan ini untuk menjalankan migrations dan seeders di production dengan aman! 📝✨
