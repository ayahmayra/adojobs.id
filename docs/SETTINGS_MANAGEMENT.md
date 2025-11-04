# Panduan Pengaturan Website

## Overview
Fitur pengaturan website memungkinkan admin untuk mengelola informasi dasar website seperti nama, deskripsi, logo, dan favicon secara dinamis melalui dashboard admin.

## Fitur yang Tersedia

### 1. Pengaturan Umum
Kelola informasi dasar website:
- **Nama Website**: Nama yang ditampilkan di seluruh aplikasi
- **Deskripsi Website**: Deskripsi singkat tentang website (max 500 karakter)
- **Logo Website**: Upload logo dalam format PNG, JPG, JPEG, atau SVG (max 2MB)
- **Favicon**: Upload favicon dalam format PNG, ICO, JPG, atau JPEG (max 1MB)

## Cara Menggunakan

### Mengakses Halaman Pengaturan
1. Login sebagai admin
2. Buka Dashboard Admin
3. Klik menu **"Pengaturan"** di sidebar
4. Atau akses langsung melalui URL: `/admin/settings`

### Mengubah Pengaturan Umum

#### Update Nama dan Deskripsi
1. Edit field **"Nama Website"** dan **"Deskripsi Website"**
2. Klik tombol **"Simpan Perubahan"**
3. Sistem akan menampilkan pesan sukses

#### Upload Logo
1. Klik tombol **"Choose File"** di bagian **"Logo Website"**
2. Pilih file logo (PNG, JPG, JPEG, atau SVG)
3. Maksimal ukuran file: 2MB
4. Klik **"Simpan Perubahan"**
5. Logo akan tersimpan dan ditampilkan sebagai preview

#### Upload Favicon
1. Klik tombol **"Choose File"** di bagian **"Favicon"**
2. Pilih file favicon (PNG, ICO, JPG, atau JPEG)
3. Ukuran ideal: 32x32 pixel
4. Maksimal ukuran file: 1MB
5. Klik **"Simpan Perubahan"**
6. Favicon akan tersimpan dan ditampilkan sebagai preview

#### Menghapus Logo atau Favicon
1. Jika sudah ada logo/favicon yang ter-upload, akan muncul preview
2. Klik tombol **"Hapus"** di samping preview
3. Konfirmasi penghapusan
4. File akan dihapus dari sistem

## Struktur Database

### Tabel: `settings`
```sql
- id: bigint (primary key)
- key: string (unique) - Kunci pengaturan
- value: text - Nilai pengaturan
- type: string - Tipe data (string, boolean, integer, json, dll)
- group: string - Grup pengaturan (general, email, seo, dll)
- description: text - Deskripsi pengaturan
- created_at: timestamp
- updated_at: timestamp
```

### Settings Keys
```
- site_name: Nama website
- site_description: Deskripsi website
- site_logo: Path ke file logo
- site_favicon: Path ke file favicon
```

## File Storage

### Lokasi Penyimpanan
File logo dan favicon disimpan di:
```
storage/app/public/settings/
```

### Akses Public
File dapat diakses melalui:
```
{{ asset('storage/settings/filename.ext') }}
```

## Validasi

### Nama Website
- **Required**: Ya
- **Type**: String
- **Max Length**: 255 karakter

### Deskripsi Website
- **Required**: Ya
- **Type**: String/Text
- **Max Length**: 500 karakter

### Logo Website
- **Required**: Tidak (optional)
- **Type**: Image
- **Allowed Formats**: PNG, JPG, JPEG, SVG
- **Max Size**: 2MB (2048KB)

### Favicon
- **Required**: Tidak (optional)
- **Type**: Image
- **Allowed Formats**: PNG, ICO, JPG, JPEG
- **Max Size**: 1MB (1024KB)
- **Recommended Size**: 32x32 pixel

## API Endpoints

### View Settings Page
```
GET /admin/settings
```

### Update General Settings
```
POST /admin/settings/general
Content-Type: multipart/form-data

Parameters:
- site_name (required): string
- site_description (required): string
- site_logo (optional): file
- site_favicon (optional): file
```

### Delete File (Logo/Favicon)
```
DELETE /admin/settings/file
Content-Type: application/json

Body:
{
    "type": "logo" | "favicon"
}

Response:
{
    "success": true,
    "message": "Logo berhasil dihapus."
}
```

## Penggunaan dalam Code

### Helper Functions
Aplikasi menyediakan helper functions untuk akses mudah ke settings:

```php
// Get site name
$name = site_name(); // Returns: "AdoJobs.id"

// Get site description
$desc = site_description(); // Returns: "Platform pencarian kerja..."

// Get logo URL (returns null if not set)
$logo = site_logo(); // Returns: "http://example.com/storage/settings/logo.png"

// Get favicon URL (returns null if not set)
$favicon = site_favicon(); // Returns: "http://example.com/storage/settings/favicon.ico"

// Get any setting
$value = setting('site_name', 'Default Value');
```

### Mendapatkan Setting Value (Direct Model)
```php
use App\Models\Setting;

// Get single setting
$siteName = Setting::get('site_name', 'Default Name');

// Get all settings in a group
$generalSettings = Setting::getByGroup('general');
```

### Set Setting Value
```php
use App\Models\Setting;

Setting::set('site_name', 'AdoJobs.id', 'string', 'general');
```

### Menggunakan di Blade Template

#### Dengan Helper Functions (Recommended)
```blade
<!-- Display site name -->
<h1>{{ site_name() }}</h1>

<!-- Display logo with fallback -->
@if(site_logo())
    <img src="{{ site_logo() }}" alt="{{ site_name() }}">
@else
    <span>{{ site_name() }}</span>
@endif

<!-- Display favicon -->
@if(site_favicon())
    <link rel="icon" href="{{ site_favicon() }}" type="image/x-icon">
@endif

<!-- Display description -->
<meta name="description" content="{{ site_description() }}">
```

#### Dengan Model (Alternative)
```blade
<!-- Get site name -->
{{ Setting::get('site_name', 'AdoJobs.id') }}

<!-- Display logo -->
@if($logo = Setting::get('site_logo'))
    <img src="{{ asset('storage/' . $logo) }}" alt="Logo">
@endif
```

## Seeder

### Menjalankan Seeder
Untuk menginisialisasi pengaturan default:

```bash
# Development
docker exec adojobs_dev_app php artisan db:seed --class=GeneralSettingsSeeder

# Production
docker-compose -f docker-compose.prod.yml exec app php artisan db:seed --class=GeneralSettingsSeeder --force
```

### Default Values
```
- site_name: "AdoJobs.id"
- site_description: "Platform pencarian kerja terbaik di Pulau Bengkalis..."
- site_logo: null
- site_favicon: null
```

## Troubleshooting

### Logo/Favicon tidak muncul setelah upload
1. Cek apakah symlink storage sudah dibuat:
   ```bash
   php artisan storage:link
   ```
2. Cek permission folder storage:
   ```bash
   chmod -R 775 storage/
   chown -R www-data:www-data storage/
   ```

### Error saat upload file
1. Cek konfigurasi `upload_max_filesize` dan `post_max_size` di `php.ini`
2. Pastikan folder `storage/app/public/settings` memiliki permission write

### Setting tidak tersimpan
1. Cek koneksi database
2. Cek tabel `settings` sudah ter-migrate
3. Jalankan seeder untuk inisialisasi

## Best Practices

1. **Logo**: Gunakan format PNG atau SVG untuk transparansi
2. **Favicon**: Gunakan ukuran 32x32px atau 64x64px untuk hasil terbaik
3. **Deskripsi**: Tulis deskripsi yang jelas dan informatif (80-160 karakter ideal untuk SEO)
4. **Backup**: Selalu backup file logo/favicon sebelum mengganti
5. **Optimize**: Compress gambar sebelum upload untuk performa lebih baik

## Security

1. Validasi file type dan size di server-side
2. CSRF protection aktif pada semua form
3. File disimpan dengan nama random untuk mencegah overwrite
4. Admin-only access dengan middleware

## Implementation Details

### Helper Functions Location
File: `app/helpers.php`

Helper functions yang tersedia:
- `setting($key, $default)` - Get any setting value
- `site_name()` - Get site name
- `site_description()` - Get site description  
- `site_logo()` - Get logo URL
- `site_favicon()` - Get favicon URL

### Autoload Configuration
Helper functions di-autoload melalui `composer.json`:
```json
"autoload": {
    "files": [
        "app/helpers.php"
    ]
}
```

### Layout Integration

Logo dan favicon telah diintegrasikan ke semua layout:

**1. Main Layout** (`components/layouts/main.blade.php`)
- Favicon di `<head>`
- Title menggunakan `site_name()`

**2. Dashboard Layout** (`components/layouts/dashboard.blade.php`)
- Favicon di `<head>`
- Logo di top navigation
- Title menggunakan `site_name()`

**3. Guest Layout** (`components/layouts/guest.blade.php`)
- Favicon di `<head>`
- Logo di login/register page
- Title menggunakan `site_name()`

**4. Header Component** (`components/header.blade.php`)
- Logo di main navigation
- Fallback ke SVG + text jika logo tidak ada

**5. Footer Component** (`components/footer.blade.php`)
- Logo di footer dengan filter `brightness-0 invert` untuk dark background
- Fallback ke SVG + text jika logo tidak ada

## Roadmap

Fitur yang akan ditambahkan:
- [x] General Settings (nama, deskripsi, logo, favicon)
- [x] Helper functions untuk settings
- [x] Integration ke semua layouts
- [ ] Email Settings (SMTP configuration)
- [ ] SEO Settings (meta tags, keywords)
- [ ] Security Settings (password policy, 2FA)
- [ ] Notification Settings
- [ ] System Settings (maintenance mode, cache)
- [ ] Social Media Links
- [ ] Contact Information
- [ ] Theme Customization

## Support

Jika menemui masalah, silakan:
1. Cek dokumentasi ini terlebih dahulu
2. Periksa log di `storage/logs/laravel.log`
3. Contact developer team

