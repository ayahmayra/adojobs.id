# Admin Category Management System

## 📋 Overview

Sistem pengelolaan kategori pekerjaan untuk admin di `/admin/categories`. Admin dapat membuat, mengedit, dan menghapus kategori pekerjaan yang akan digunakan untuk mengorganisir lowongan kerja.

## ✨ Fitur

### 1. **Daftar Kategori** (`/admin/categories`)
- ✅ Menampilkan semua kategori dengan informasi lengkap
- ✅ Sorting berdasarkan order number
- ✅ Menampilkan jumlah jobs per kategori
- ✅ Status active/inactive indicator
- ✅ Quick actions: Edit & Delete
- ✅ Empty state jika belum ada kategori

### 2. **Tambah Kategori** (`/admin/categories/create`)
- ✅ Form lengkap dengan validation
- ✅ Auto-generate slug dari nama
- ✅ Support emoji icons
- ✅ Order number untuk sorting
- ✅ Active/Inactive status
- ✅ Helpful tips dan examples

### 3. **Edit Kategori** (`/admin/categories/{id}/edit`)
- ✅ Pre-filled form dengan data existing
- ✅ Preview icon emoji
- ✅ Display slug (read-only)
- ✅ Warning jika inactive tapi ada jobs
- ✅ Delete section (danger zone)
- ✅ Protection: tidak bisa delete jika ada jobs

## 📁 File Structure

```
src/
├── app/Http/Controllers/Admin/
│   └── CategoryController.php          # ✅ Already exists
├── app/Models/
│   └── Category.php                     # ✅ Already exists
└── resources/views/admin/categories/
    ├── index.blade.php                  # ✅ List view
    ├── create.blade.php                 # ✅ Create form
    └── edit.blade.php                   # ✅ Edit form
```

## 🗄️ Database Schema

**Table: `categories`**

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary key |
| name | varchar(255) | Category name |
| slug | varchar(255) | URL-friendly slug (auto-generated) |
| description | text | Optional description |
| icon | varchar(255) | Emoji or icon character |
| is_active | boolean | Active status (default: true) |
| order | integer | Display order (default: 0) |
| created_at | timestamp | |
| updated_at | timestamp | |

**Indexes:**
- Primary key: `id`
- Unique: `slug`
- Index: `is_active`, `order`

## 🎯 Routes

```php
// All routes use admin middleware and 'admin.' prefix
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('categories', Admin\CategoryController::class);
});
```

**Available Routes:**
- `GET /admin/categories` - List all categories
- `GET /admin/categories/create` - Show create form
- `POST /admin/categories` - Store new category
- `GET /admin/categories/{id}/edit` - Show edit form
- `PUT /admin/categories/{id}` - Update category
- `DELETE /admin/categories/{id}` - Delete category

## 🔧 Controller Methods

### `index()`
```php
public function index()
{
    $categories = Category::ordered()->withCount('jobs')->get();
    return view('admin.categories.index', compact('categories'));
}
```
- Mengambil semua kategori
- Sorting berdasarkan order & name
- Include jobs count

### `store(Request $request)`
```php
$request->validate([
    'name' => 'required|string|max:255',
    'description' => 'nullable|string',
    'icon' => 'nullable|string|max:255',
    'is_active' => 'required|boolean',
    'order' => 'required|integer|min:0',
]);
```

### `update(Request $request, Category $category)`
- Same validation as store
- Auto-generates new slug if name changes

### `destroy(Category $category)`
- Soft protection: tidak bisa delete jika ada jobs
- Hard protection di UI: disable button jika jobs_count > 0

## 🎨 UI Features

### Index Page
```
┌─────────────────────────────────────────────┐
│ Manage Categories        [+ Add New]        │
├─────────────────────────────────────────────┤
│ Order │ Category    │ Description │ Jobs    │
├───────┼─────────────┼─────────────┼─────────┤
│  0    │ 💼 IT       │ Tech jobs   │ 5 jobs  │
│  10   │ 🎨 Design   │ Creative    │ 3 jobs  │
└─────────────────────────────────────────────┘
```

**Features:**
- 📊 Clean table layout with clear columns
- 🎨 Icon preview di kolom category
- 📈 Jobs count badge
- 🟢 Active/Inactive status badge
- ✏️ Edit & 🗑️ Delete actions
- 🚫 Delete disabled jika ada jobs

### Create/Edit Form
```
┌─────────────────────────────────────┐
│ Category Name *                     │
│ [Software Development]              │
│                                     │
│ Description                         │
│ [Optional description...]           │
│                                     │
│ Icon (Emoji)                        │
│ [💼]                        [💼]    │
│                                     │
│ Display Order *                     │
│ [0]                                 │
│                                     │
│ Status *                            │
│ (•) Active  ( ) Inactive            │
│                                     │
│         [Cancel] [Save Category]    │
└─────────────────────────────────────┘
```

## 🔐 Security & Validation

### Delete Protection
```php
// Controller: cek jumlah jobs sebelum delete
if ($category->jobs_count > 0) {
    return redirect()->back()
        ->with('error', 'Cannot delete category with jobs');
}
```

### Validation Rules
- **Name**: Required, string, max 255 characters
- **Description**: Optional, string
- **Icon**: Optional, max 255 characters (untuk emoji)
- **Order**: Required, integer, minimum 0
- **Status**: Required, boolean (0 or 1)

### Slug Generation
```php
protected static function boot()
{
    static::creating(function ($category) {
        if (empty($category->slug)) {
            $category->slug = Str::slug($category->name);
        }
    });
}
```
- Auto-generate dari name
- URL-friendly format
- Unique constraint di database

## 💡 Usage Tips

### Recommended Order Numbers
```
0   - Featured/Priority category
10  - Software Development
20  - Design & Creative
30  - Marketing
40  - Business & Management
50  - Other categories
```

**Why gaps of 10?**
- Easy to insert new categories between existing ones
- e.g., add "UI/UX Design" at order 25 (between 20 and 30)

### Popular Emoji Icons
```
💼 - Business/Professional
🎨 - Design & Creative
🔧 - Engineering/Technical
📱 - Mobile/Tech
🏢 - Corporate
👨‍💻 - Software/IT
📊 - Data/Analytics
💡 - Innovation/Ideas
🎯 - Marketing/Sales
📝 - Content/Writing
```

### Active vs Inactive
- **Active**: Kategori tampil di public pages (job listings, filters)
- **Inactive**: Kategori tersembunyi dari public, tapi jobs tetap ada
- **Use case**: Temporarily hide category without deleting jobs

## 🧪 Testing

### Manual Testing Checklist

**Create Category:**
- [ ] Navigate to `/admin/categories`
- [ ] Click "Add New Category"
- [ ] Fill form:
  - Name: "Test Category"
  - Description: "Test description"
  - Icon: "🧪"
  - Order: 999
  - Status: Active
- [ ] Submit form
- [ ] Verify success message
- [ ] Verify category appears in list

**Edit Category:**
- [ ] Click Edit on a category
- [ ] Change name and icon
- [ ] Submit form
- [ ] Verify changes saved

**Delete Category:**
- [ ] Try to delete category WITH jobs → Should be disabled
- [ ] Delete category WITHOUT jobs → Should succeed

**Validation:**
- [ ] Try submit form without name → Should show error
- [ ] Try negative order number → Should show error

## 📊 Model Relationships

```php
// Category.php
public function jobs()
{
    return $this->hasMany(Job::class);
}

// Scopes
scopeActive($query)      // WHERE is_active = 1
scopeOrdered($query)     // ORDER BY order, name

// Methods
activeJobsCount()        // Count published jobs
```

## 🚀 Deployment

No additional steps required for deployment:
- ✅ Migration already exists
- ✅ Controller already exists
- ✅ Routes already configured
- ✅ Views created (new)
- ✅ Sidebar link already exists

Just push changes to server:
```bash
# Clear cache if needed
php artisan cache:clear
php artisan view:clear
```

## 🔮 Future Enhancements

1. ✨ **Drag & Drop Reordering**
   - jQuery UI sortable untuk reorder categories
   - AJAX save new order

2. 🖼️ **Image Icons**
   - Upload custom icon images
   - Fallback to emoji jika tidak ada image

3. 📈 **Category Analytics**
   - Views per category
   - Application stats per category
   - Popular categories report

4. 🌐 **Multi-language Support**
   - Nama kategori dalam multiple bahasa
   - Auto-detect user language

5. 🔄 **Batch Actions**
   - Select multiple categories
   - Bulk activate/deactivate
   - Bulk delete (yang tidak ada jobs)

6. 📱 **Category API**
   - RESTful API endpoint
   - Mobile app integration

## 📝 Changelog

### Version 1.0 (17 Oktober 2025)
- ✅ Initial release
- ✅ Complete CRUD functionality
- ✅ Delete protection for categories with jobs
- ✅ Emoji icon support
- ✅ Order management
- ✅ Active/Inactive status
- ✅ Responsive UI
- ✅ Form validation
- ✅ Help text and tips

---

**Documentation Version**: 1.0  
**Last Updated**: 17 Oktober 2025  
**Status**: ✅ Production Ready

