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

### Mendapatkan Setting Value
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
```blade
<!-- Get site name -->
{{ Setting::get('site_name', 'AdoJobs.id') }}

<!-- Display logo -->
@if($logo = Setting::get('site_logo'))
    <img src="{{ asset('storage/' . $logo) }}" alt="Logo">
@endif

<!-- Display favicon -->
@if($favicon = Setting::get('site_favicon'))
    <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
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

## Roadmap

Fitur yang akan ditambahkan:
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

