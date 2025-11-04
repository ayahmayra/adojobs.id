# Welcome Page Documentation

## Overview
Halaman welcome/homepage yang modern dan profesional untuk JobMaker.ID, didesain dengan menggunakan Tailwind CSS dan mengikuti best practices UI/UX untuk job portal.

## File yang Dibuat/Dimodifikasi

### 1. `src/resources/views/welcome.blade.php`
**File Baru** - Landing page utama dengan fitur:

#### Sections:
1. **Header**
   - Logo JobMaker.ID
   - Navigation menu (Beranda, Lowongan, Pengusaha, Kandidat, Blog)
   - Login/Register buttons
   - Job Post CTA button
   - Sticky header dengan shadow

2. **Hero Section**
   - Headline utama dengan search form
   - Job title search input
   - Category filter dropdown
   - Search button
   - Popular keywords tags
   - Hero image dengan floating cards (statistics)
   - Gradient background dengan pattern

3. **Popular Categories**
   - Grid 3 kolom (responsive)
   - 6 kategori pekerjaan populer
   - Icon untuk setiap kategori
   - Jumlah posisi tersedia
   - Hover effects dengan transform
   - Link "Lihat Semua Kategori"

4. **Featured Jobs**
   - Grid 2 kolom (responsive)
   - 6 job listings terfeatured
   - Company logo placeholder
   - Job details (location, salary, time posted)
   - Job type badges (Full Time, Featured, Urgent)
   - Category tags
   - Bookmark button
   - "Load More" button

5. **Statistics Section**
   - 3 kolom statistik
   - Background gradient indigo
   - Large numbers dengan deskripsi:
     - 4M+ Daily Active Users
     - 12k+ Open Job Positions  
     - 20M+ Stories Shared

6. **Testimonials**
   - Grid 3 kolom (responsive)
   - 3 testimonial cards
   - Avatar placeholders
   - Name, role, dan review
   - Clean card design

7. **Call-to-Action (CTA)**
   - Recruitment section
   - Gradient background
   - White card dengan rounded corners
   - "Mulailah Rekrutmen Sekarang" button

8. **Footer**
   - 4 kolom informasi
   - Company info dengan contact
   - Links untuk Candidates
   - Links untuk Employers
   - Links About Us
   - Social media icons
   - Copyright information

### 2. `src/routes/web.php`
**Modified** - Homepage route diubah untuk menggunakan view welcome:

```php
// Before
Route::get('/', [HomeController::class, 'index'])->name('home');

// After
Route::get('/', function () {
    return view('welcome');
})->name('home');
```

## Design Features

### Color Scheme
- **Primary:** Indigo (#4F46E5)
- **Secondary:** Purple (#9333EA)
- **Accent:** Blue (#3B82F6)
- **Text:** Gray shades
- **Background:** White, Gray-50

### Typography
- **Font Family:** Jost (via Bunny Fonts)
- **Weights:** 400 (regular), 500 (medium), 600 (semibold), 700 (bold)

### Components & Effects
- **Rounded Corners:** 
  - Small: `rounded-lg` (8px)
  - Medium: `rounded-xl` (12px)
  - Large: `rounded-2xl` (16px)
  - Extra Large: `rounded-3xl` (24px)

- **Shadows:**
  - Small: `shadow-sm`
  - Medium: `shadow-lg`
  - Large: `shadow-xl`
  - Extra Large: `shadow-2xl`
  - Colored: `shadow-indigo-200`

- **Transitions:** Smooth transitions pada hover states
- **Transforms:** Subtle scale dan translate effects
- **Gradients:** Linear gradients untuk backgrounds

### Responsive Design
- **Mobile First:** Base styles untuk mobile
- **Breakpoints:**
  - `sm:` 640px
  - `md:` 768px
  - `lg:` 1024px
  - `xl:` 1280px

## Usage

### Accessing the Page
```
URL: http://localhost:8080/
Route Name: home
```

### Navigation Links
- **Browse Jobs:** `{{ route('jobs.index') }}`
- **Login:** `{{ route('login') }}`
- **Register:** `{{ route('register') }}`
- **Dashboard:** `{{ url('/dashboard') }}`

## Customization

### Mengubah Warna Tema
Edit di file `welcome.blade.php`, cari dan replace warna:
- `indigo-600` → warna primary baru
- `purple-600` → warna secondary baru

### Mengubah Hero Image
Line 140 di `welcome.blade.php`:
```html
<img src="https://images.unsplash.com/photo-..." alt="Professional" class="rounded-3xl shadow-2xl">
```
Ganti URL dengan gambar yang diinginkan.

### Menambah/Mengurangi Kategori
Edit array `$categories` di line 240:
```php
@php
$categories = [
    ['name' => 'Nama', 'count' => 'X posisi', 'icon' => 'SVG_PATH', 'color' => 'warna'],
    // tambahkan kategori baru
];
@endphp
```

### Mengubah Featured Jobs
Edit loop `@for($i = 0; $i < 6; $i++)` di line 305 untuk mengubah jumlah job cards yang ditampilkan.

## Future Enhancements

### Phase 1 - Dynamic Data
- [ ] Integrasikan dengan database untuk real job listings
- [ ] Tampilkan kategori dari database
- [ ] Fetch testimonials dari database
- [ ] Update statistics dengan data real

### Phase 2 - Interactivity
- [ ] Implementasi job search functionality
- [ ] Add filter dan sort options
- [ ] Bookmark/favorite jobs feature
- [ ] Job application modal

### Phase 3 - Advanced Features
- [ ] Testimonial slider/carousel
- [ ] Job search dengan autocomplete
- [ ] Advanced filters (location, salary range, etc.)
- [ ] Newsletter subscription
- [ ] Live chat widget

## Notes
- Design mengikuti modern web design trends 2024
- Fully responsive untuk semua device sizes
- SEO-friendly dengan proper heading hierarchy
- Fast loading dengan optimized assets
- Accessibility considerations (semantic HTML)

## Support
Untuk pertanyaan atau bantuan mengenai welcome page:
- Check dokumentasi Tailwind CSS: https://tailwindcss.com
- Check Laravel Blade: https://laravel.com/docs/blade
- Review code di `src/resources/views/welcome.blade.php`

