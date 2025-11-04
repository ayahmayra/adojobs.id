# Layout Structure Documentation

## Overview

JobMaker.ID menggunakan sistem layout modular dengan Blade Components untuk memastikan konsistensi tampilan di seluruh aplikasi. Semua layouts menggunakan header dan footer yang sama dari halaman welcome sebagai template dasar.

---

## 📁 File Structure

```
src/resources/views/
├── components/
│   ├── header.blade.php          # Header component (sticky navigation)
│   ├── footer.blade.php          # Footer component (dark theme)
│   └── sidebar/
│       ├── admin.blade.php       # Admin sidebar menu
│       ├── employer.blade.php    # Employer sidebar menu
│       └── seeker.blade.php      # Seeker sidebar menu
│
├── layouts/
│   ├── main.blade.php           # Base layout untuk halaman umum
│   ├── guest.blade.php          # Layout untuk halaman auth (login/register)
│   ├── app.blade.php            # Layout untuk authenticated pages
│   └── dashboard.blade.php      # Layout dengan sidebar untuk dashboard
│
└── welcome.blade.php            # Homepage (menggunakan components)
```

---

## 🎨 Components

### 1. Header Component (`components/header.blade.php`)

**Features:**
- Sticky navigation dengan shadow
- Logo dengan link ke homepage
- Navigation menu (Beranda, Lowongan, Pengusaha, Kandidat, Blog)
  - Desktop: Horizontal links (no dropdown)
  - Mobile: Hamburger menu dengan slide-down
- User menu:
  - Desktop: Dropdown menu dengan nama user (Dashboard, Profile, Logout)
  - Mobile: Included dalam hamburger menu
- Mobile responsive dengan hamburger menu
- Alpine.js untuk dropdown dan mobile menu interactivity

**Usage:**
```blade
<x-header />
```

**Dynamic Features:**
- Active route highlighting
- Desktop: Horizontal navigation + User dropdown
- Mobile: Single hamburger menu untuk semua navigasi
- User name display dalam dropdown
- Click-away untuk close dropdown

---

### 2. Footer Component (`components/footer.blade.php`)

**Features:**
- Dark theme (bg-gray-900)
- 4 column layout:
  - Company info dengan contact
  - For Candidates links
  - For Employers links
  - About Us links
- Social media icons (Facebook, Twitter, Instagram, LinkedIn)
- Copyright dengan dynamic year
- Hover transitions

**Usage:**
```blade
<x-footer />
```

---

### 3. Sidebar Components (`components/sidebar/*.blade.php`)

#### Admin Sidebar (`components/sidebar/admin.blade.php`)
**Menu Items:**
- Dashboard
- Users Management
- Jobs Management
- Categories Management
- Reports
- Settings

#### Employer Sidebar (`components/sidebar/employer.blade.php`)
**Menu Items:**
- Dashboard
- My Jobs
- Post New Job (highlighted with indigo background)
- Applications
- Company Profile
- Statistics

#### Seeker Sidebar (`components/sidebar/seeker.blade.php`)
**Menu Items:**
- Dashboard
- Browse Jobs
- My Applications
- Saved Jobs
- My Resume
- Profile Settings
- Job Alerts

**Usage:**
```blade
<x-sidebar.admin />
<x-sidebar.employer />
<x-sidebar.seeker />
```

---

## 📄 Layouts

### 1. Main Layout (`layouts/main.blade.php`)

**Purpose:** Base layout untuk halaman umum dengan header dan footer.

**Features:**
- Full width content area
- Header dan footer menggunakan components
- Stack untuk additional styles dan scripts
- Alpine.js untuk mobile menu

**Usage:**
```blade
<x-main-layout>
    <x-slot name="title">Page Title</x-slot>
    
    <!-- Your content here -->
</x-main-layout>
```

**Best For:**
- Landing pages
- Content pages
- Public pages

---

### 2. Guest Layout (`layouts/guest.blade.php`)

**Purpose:** Layout untuk halaman authentication (login, register).

**Features:**
- Centered content dengan min-height screen
- Header dan footer
- Ideal untuk form-based pages

**Usage:**
```blade
<x-guest-layout>
    <x-slot name="title">Login</x-slot>
    
    <!-- Auth form here -->
</x-guest-layout>
```

**Best For:**
- Login page
- Register page
- Forgot password page
- Email verification pages

---

### 3. App Layout (`layouts/app.blade.php`)

**Purpose:** Layout untuk authenticated pages dengan header optional.

**Features:**
- Header component
- Optional page header dengan $header slot
- Main content area dengan min-height
- Footer component
- Stack untuk styles dan scripts

**Usage:**
```blade
<x-app-layout>
    <x-slot name="title">Page Title</x-slot>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Page Header
        </h2>
    </x-slot>

    <!-- Main content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Your content -->
        </div>
    </div>
</x-app-layout>
```

**Best For:**
- Job listings page
- Job detail page
- Profile pages
- General authenticated pages

**Current Usage:**
- `home.blade.php`
- `jobs/index.blade.php`
- `jobs/show.blade.php`

---

### 4. Dashboard Layout (`layouts/dashboard.blade.php`)

**Purpose:** Layout dengan sidebar untuk dashboard pages (Admin, Employer, Seeker).

**Features:**
- Top navigation bar (fixed)
- Collapsible sidebar (mobile responsive)
- User info di top nav dengan dropdown
- Notifications icon
- Content area dengan optional header
- Alpine.js untuk sidebar toggle dan dropdowns

**Structure:**
```
┌─────────────────────────────────────────────┐
│  Top Nav (Logo, User Menu, Notifications)  │ Fixed
├───────────┬─────────────────────────────────┤
│           │ Page Header (optional)          │
│  Sidebar  ├─────────────────────────────────┤
│           │                                 │
│  (Fixed)  │  Main Content Area             │
│           │                                 │
│           │                                 │
└───────────┴─────────────────────────────────┘
```

**Usage:**
```blade
<x-dashboard-layout>
    <x-slot name="title">Dashboard</x-slot>
    
    <x-slot name="sidebar">
        @if(auth()->user()->isAdmin())
            <x-sidebar.admin />
        @elseif(auth()->user()->isEmployer())
            <x-sidebar.employer />
        @elseif(auth()->user()->isSeeker())
            <x-sidebar.seeker />
        @endif
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <!-- Dashboard content -->
</x-dashboard-layout>
```

**Best For:**
- Admin dashboard
- Employer dashboard
- Seeker dashboard
- All role-specific pages dengan navigation menu

---

## 🎨 Design System

### Colors

```css
Primary:    Indigo (#4F46E5) - indigo-600
Secondary:  Purple (#9333EA) - purple-600
Accent:     Blue (#3B82F6) - blue-600
Background: Gray-50 (#F9FAFB)
Text:       Gray-900 (#111827)
Footer:     Gray-900 (#111827) - Dark theme
```

### Typography

```css
Font Family: 'Jost', sans-serif
Weights: 400 (Regular), 500 (Medium), 600 (Semi-Bold), 700 (Bold)
```

### Spacing

```css
Container: max-w-7xl mx-auto px-4 sm:px-6 lg:px-8
Section Padding: py-12 lg:py-20
Card Padding: p-6
```

### Components

```css
Border Radius:
  - Small: rounded-lg (8px)
  - Medium: rounded-xl (12px)
  - Large: rounded-2xl (16px)
  - XL: rounded-3xl (24px)

Shadows:
  - Small: shadow-sm
  - Medium: shadow-lg
  - Large: shadow-xl
  - XL: shadow-2xl

Transitions: transition duration-200 ease-in-out
```

---

## 📱 Responsive Breakpoints

```css
sm:   640px   @media (min-width: 640px)
md:   768px   @media (min-width: 768px)
lg:   1024px  @media (min-width: 1024px)
xl:   1280px  @media (min-width: 1280px)
2xl:  1536px  @media (min-width: 1536px)
```

### Mobile Behavior:

1. **Header:**
   - Desktop: Horizontal navigation
   - Mobile: Hamburger menu dengan slide-down

2. **Sidebar (Dashboard):**
   - Desktop: Fixed sidebar (w-64)
   - Mobile: Overlay sidebar dengan backdrop

3. **Footer:**
   - Desktop: 4 columns
   - Mobile: Stacked (1 column)

---

## 🔧 Alpine.js Data

Semua layouts menggunakan Alpine.js untuk interactivity:

```html
<html x-data="{ mobileMenuOpen: false, sidebarOpen: false }">
```

**Variables:**
- `mobileMenuOpen` - Toggle mobile navigation menu
- `sidebarOpen` - Toggle sidebar (dashboard layout)

**Directives:**
- `@click` - Toggle menus
- `@click.away` - Close dropdowns when clicking outside
- `x-show` - Conditional display
- `:class` - Dynamic classes

---

## 📝 Usage Examples

### Example 1: Public Page dengan Header & Footer

```blade
<x-main-layout>
    <x-slot name="title">About Us</x-slot>
    
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">About Us</h1>
            <p class="text-gray-600">Content here...</p>
        </div>
    </section>
</x-main-layout>
```

### Example 2: Auth Page

```blade
<x-guest-layout>
    <x-slot name="title">Login</x-slot>
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold mb-6">Login</h2>
        <!-- Form here -->
    </div>
</x-guest-layout>
```

### Example 3: Job Listing Page

```blade
<x-app-layout>
    <x-slot name="title">Browse Jobs</x-slot>
    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Browse Jobs
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Job listings -->
        </div>
    </div>
</x-app-layout>
```

### Example 4: Admin Dashboard

```blade
<x-dashboard-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Dashboard Overview
        </h2>
    </x-slot>

    <!-- Dashboard widgets -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Stats cards -->
    </div>
</x-dashboard-layout>
```

---

## 🚀 Best Practices

### 1. Container Usage

Selalu gunakan container untuk content consistency:

```blade
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Content -->
</div>
```

### 2. Section Spacing

Gunakan consistent spacing untuk sections:

```blade
<section class="py-12 lg:py-20">
    <!-- Section content -->
</section>
```

### 3. Card Styling

Standard card styling untuk consistency:

```blade
<div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
    <!-- Card content -->
</div>
```

### 4. Responsive Grid

Gunakan responsive grid untuk layouts:

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Grid items -->
</div>
```

### 5. Button Styling

Primary button:
```blade
<a href="#" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium transition">
    Button Text
</a>
```

Secondary button:
```blade
<a href="#" class="bg-white text-indigo-600 border-2 border-indigo-600 px-6 py-3 rounded-lg hover:bg-indigo-50 font-medium transition">
    Button Text
</a>
```

---

## 🔄 Migration dari Old Layout

Jika ada views yang masih menggunakan old layout system:

### Before (Old):
```blade
@extends('layouts.app')

@section('content')
    <!-- Content -->
@endsection
```

### After (New):
```blade
<x-app-layout>
    <x-slot name="header">
        <h2>Page Title</h2>
    </x-slot>

    <!-- Content -->
</x-app-layout>
```

---

## 📚 Related Documentation

- [WELCOME_PAGE.md](WELCOME_PAGE.md) - Welcome page structure
- [DEVELOPMENT_GUIDE.md](DEVELOPMENT_GUIDE.md) - Development workflow
- [README.md](README.md) - Project overview

---

## 🎯 Next Steps

1. **Implement Dashboard Views:**
   - Create admin dashboard using `<x-dashboard-layout>` + `<x-sidebar.admin />`
   - Create employer dashboard using `<x-dashboard-layout>` + `<x-sidebar.employer />`
   - Create seeker dashboard using `<x-dashboard-layout>` + `<x-sidebar.seeker />`

2. **Update Auth Views:**
   - Migrate login.blade.php to use `<x-guest-layout>`
   - Migrate register.blade.php to use `<x-guest-layout>`

3. **Create Additional Pages:**
   - About page using `<x-main-layout>`
   - Contact page using `<x-main-layout>`
   - Terms & Privacy pages

4. **Enhance Components:**
   - Add search functionality to header
   - Add notification system
   - Add breadcrumbs component

---

**Last Updated:** {{ date('Y-m-d') }}  
**Version:** 1.0.0

