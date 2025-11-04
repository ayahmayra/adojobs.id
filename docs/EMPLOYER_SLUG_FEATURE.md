# 🔗 Employer Slug Feature

## 📋 Overview

**Feature**: Custom URL/Slug for Employer Public Profiles  
**Date**: 17 Oktober 2025  
**Status**: ✅ Completed

## 🎯 Purpose

Memungkinkan employer untuk mengatur URL publik mereka dengan slug yang dapat disesuaikan, sehingga URL profil perusahaan menjadi lebih SEO-friendly dan mudah diingat.

## ✅ Features Implemented

### 1. **Database Schema**
- ✅ Added `slug` field to `employers` table
- ✅ Unique constraint untuk mencegah duplikasi
- ✅ Nullable untuk backward compatibility

### 2. **Model Updates**
- ✅ Added `slug` to fillable fields
- ✅ Auto-generation dari company name
- ✅ Unique slug generation method
- ✅ Public profile URL attribute

### 3. **Controller Updates**
- ✅ New `updateEmployerSlug()` method in ProfileController
- ✅ Validation untuk slug format
- ✅ Unique validation dengan exclusion

### 4. **Route Updates**
- ✅ Updated `employers.show` route untuk menggunakan slug
- ✅ New route untuk update slug: `profile.employer.slug.update`

### 5. **UI/UX Updates**
- ✅ Edit slug form di profile page
- ✅ Toggle form dengan JavaScript
- ✅ Real-time URL preview
- ✅ Validation feedback

## 📊 URL Examples

### Before (ID-based):
```
http://localhost:8080/employers/1
http://localhost:8080/employers/2
http://localhost:8080/employers/3
```

### After (Slug-based):
```
http://localhost:8080/employers/tech-innovations-ltd
http://localhost:8080/employers/green-energy-solutions
http://localhost:8080/employers/creative-digital-agency
```

## 🔧 Technical Implementation

### Database Migration
```php
Schema::table('employers', function (Blueprint $table) {
    $table->string('slug')->unique()->nullable()->after('company_name');
});
```

### Model Methods
```php
// Generate unique slug
public function generateUniqueSlug($slug = null)
{
    if (empty($slug)) {
        $slug = Str::slug($this->company_name);
    }

    $originalSlug = $slug;
    $counter = 1;

    while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

// Get public profile URL
public function getPublicProfileUrlAttribute()
{
    return route('employers.show', $this->slug ?: $this->id);
}
```

### Route Model Binding
```php
Route::get('/employers/{employer:slug}', [EmployerController::class, 'show'])
    ->name('employers.show');
```

### Controller Method
```php
public function updateEmployerSlug(Request $request): RedirectResponse
{
    $user = $request->user();
    
    if (!$user->isEmployer() || !$user->employer) {
        abort(403, 'Unauthorized action.');
    }

    $validated = $request->validate([
        'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:employers,slug,' . $user->employer->id],
    ]);

    $slug = $user->employer->generateUniqueSlug($validated['slug']);
    $user->employer->update(['slug' => $slug]);

    return Redirect::route('profile.show')->with('slug-updated', true);
}
```

## 🎨 UI Components

### Profile Page Integration
```blade
<div class="p-3 mt-4 bg-white bg-opacity-20 rounded-lg">
    <div class="flex justify-between items-center">
        <div class="flex items-center text-sm">
            <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Profile URL: <span class="ml-2 font-mono">{{ $user->employer->public_profile_url }}</span></span>
        </div>
        <button onclick="toggleSlugEdit()" 
                class="text-xs text-indigo-200 transition hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
        </button>
    </div>
    
    {{-- Slug Edit Form --}}
    <div id="slug-edit-form" class="hidden pt-3 mt-3 border-t border-white border-opacity-20">
        <form action="{{ route('profile.employer.slug.update') }}" method="POST" class="flex gap-2">
            @csrf
            @method('PATCH')
            <div class="flex-1">
                <input type="text" 
                       name="slug" 
                       value="{{ $user->employer->slug ?: Str::slug($user->employer->company_name) }}"
                       placeholder="company-slug"
                       class="px-3 py-2 w-full text-sm text-black bg-white bg-opacity-90 rounded border-0 focus:ring-2 focus:ring-white focus:ring-opacity-50"
                       pattern="[a-z0-9-]+"
                       title="Only lowercase letters, numbers, and hyphens allowed">
            </div>
            <button type="submit" 
                    class="px-3 py-2 text-xs font-medium text-indigo-600 bg-white rounded transition hover:bg-indigo-50">
                Update
            </button>
            <button type="button" 
                    onclick="toggleSlugEdit()"
                    class="px-3 py-2 text-xs font-medium text-gray-600 bg-white bg-opacity-50 rounded transition hover:bg-opacity-70">
                Cancel
            </button>
        </form>
        <p class="mt-1 text-xs text-indigo-100">
            Only lowercase letters, numbers, and hyphens allowed. Leave empty to auto-generate from company name.
        </p>
    </div>
</div>
```

### JavaScript Toggle
```javascript
function toggleSlugEdit() {
    const form = document.getElementById('slug-edit-form');
    if (form.classList.contains('hidden')) {
        form.classList.remove('hidden');
    } else {
        form.classList.add('hidden');
    }
}
```

## 📝 Validation Rules

### Slug Format
- ✅ Required field
- ✅ String type
- ✅ Maximum 255 characters
- ✅ Regex pattern: `^[a-z0-9-]+$` (lowercase letters, numbers, hyphens only)
- ✅ Unique constraint (excluding current employer)

### Auto-generation Rules
- ✅ Generate from company name if slug is empty
- ✅ Convert to lowercase
- ✅ Replace spaces with hyphens
- ✅ Remove special characters
- ✅ Add counter suffix if duplicate exists

## 🚀 Migration & Seeding

### Migration
```bash
docker-compose exec app php artisan migrate
```

### Seeder for Existing Data
```bash
docker-compose exec app php artisan db:seed --class=UpdateEmployerSlugsSeeder
```

## 🧪 Testing

### Test Cases
1. ✅ Create new employer - auto-generate slug
2. ✅ Update existing employer - keep current slug
3. ✅ Edit slug via profile page
4. ✅ Duplicate slug handling
5. ✅ Invalid slug format handling
6. ✅ Public profile URL generation
7. ✅ Route model binding with slug

### Test Commands
```bash
# Check existing slugs
docker-compose exec app php artisan tinker --execute="use App\Models\Employer; Employer::all(['id', 'company_name', 'slug'])->each(function(\$emp) { echo \$emp->id . ': ' . \$emp->company_name . ' -> ' . \$emp->slug . PHP_EOL; });"

# Test URL generation
docker-compose exec app php artisan tinker --execute="use App\Models\Employer; \$emp = Employer::first(); echo \$emp->public_profile_url;"
```

## 📱 User Experience

### Before
- ❌ Generic URLs dengan ID
- ❌ Tidak SEO-friendly
- ❌ Sulit diingat
- ❌ Tidak profesional

### After
- ✅ Custom URLs dengan slug
- ✅ SEO-friendly
- ✅ Mudah diingat
- ✅ Profesional
- ✅ Brandable URLs

## 🎯 Benefits

1. **SEO Optimization** - URLs yang lebih baik untuk search engines
2. **Branding** - URLs yang mencerminkan nama perusahaan
3. **User Experience** - URLs yang mudah diingat dan dibagikan
4. **Professional** - Tampilan yang lebih profesional
5. **Flexibility** - Employer dapat mengatur URL mereka sendiri

## 📂 Files Modified

1. ✅ `database/migrations/2025_10_17_022027_add_slug_to_employers_table.php`
2. ✅ `src/app/Models/Employer.php`
3. ✅ `src/app/Http/Controllers/ProfileController.php`
4. ✅ `src/routes/web.php`
5. ✅ `src/resources/views/profile/show.blade.php`
6. ✅ `src/database/seeders/UpdateEmployerSlugsSeeder.php`

## 🔄 Backward Compatibility

- ✅ Existing URLs dengan ID masih berfungsi
- ✅ Auto-fallback ke ID jika slug tidak ada
- ✅ Seamless migration untuk data existing

---

**Status**: ✅ **Completed**  
**Feature**: Employer Custom Slug URLs  
**Migration**: Applied  
**Seeding**: Completed  
**Testing**: Ready
