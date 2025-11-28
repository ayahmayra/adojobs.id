<x-layouts.dashboard>
    <x-slot name="title">Edit Category</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Kategori</h1>
                <p class="mt-1 text-sm text-gray-600">Ubah informasi kategori</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <!-- Form Card -->
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">Edit: {{ $category->name }}</h2>
                    <p class="mt-1 text-sm text-gray-500">Update category information</p>
                </div>
                @if($category->jobs_count > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-indigo-800 bg-indigo-100 rounded-full">
                    {{ $category->jobs_count }} jobs using this category
                </span>
                @endif
            </div>

            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Category Name -->
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $category->name) }}"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                           placeholder="e.g., Software Development">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug (Read-only info) -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Slug (Auto-generated)
                    </label>
                    <div class="px-3 py-2 text-sm text-gray-700 bg-gray-50 rounded-md border border-gray-300">
                        {{ $category->slug }}
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Slug is automatically generated from the category name</p>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                              placeholder="Brief description of this category...">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Optional: Provide a brief description for this category</p>
                </div>

                <!-- Icon -->
                <div class="mb-6">
                    <label for="icon" class="block mb-2 text-sm font-medium text-gray-700">
                        Icon (Emoji)
                    </label>
                    <div class="flex gap-3 items-center">
                        <input type="text" 
                               name="icon" 
                               id="icon" 
                               value="{{ old('icon', $category->icon) }}"
                               maxlength="10"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('icon') border-red-500 @enderror"
                               placeholder="e.g., 💼 or 🎨">
                        <div class="flex justify-center items-center w-12 h-12 text-2xl bg-gray-100 rounded-lg border border-gray-200">
                            <span id="icon-preview">{{ $category->icon ?: '📁' }}</span>
                        </div>
                    </div>
                    @error('icon')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Optional: Use emoji or icon character. Examples: 💼 🎨 🔧 📱 🏢 👨‍💻
                    </p>
                </div>

                <!-- Order -->
                <div class="mb-6">
                    <label for="order" class="block mb-2 text-sm font-medium text-gray-700">
                        Display Order <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="order" 
                           id="order" 
                           value="{{ old('order', $category->order) }}"
                           min="0"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('order') border-red-500 @enderror"
                           placeholder="0">
                    @error('order')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first. Use 0, 10, 20, etc. for easy reordering.</p>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $category->is_active) == '1' ? 'checked' : '' }}
                                   class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Active</span> - Category will be visible to users
                            </span>
                        </label>
                        <br>
                        <label class="inline-flex items-center">
                            <input type="radio" 
                                   name="is_active" 
                                   value="0" 
                                   {{ old('is_active', $category->is_active) == '0' ? 'checked' : '' }}
                                   class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Inactive</span> - Category will be hidden from users
                            </span>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    @if(!$category->is_active && $category->jobs_count > 0)
                        <div class="p-3 mt-2 text-sm text-yellow-700 bg-yellow-50 rounded-md border border-yellow-200">
                            <strong>Warning:</strong> This category is inactive but has {{ $category->jobs_count }} job(s). Inactive categories are hidden from public view.
                        </div>
                    @endif
                </div>

                <!-- Divider -->
                <div class="my-6 border-t border-gray-200"></div>

                <!-- Buttons -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('admin.categories.index') }}" 
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent shadow-sm hover:bg-indigo-700">
                        <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Category
                    </button>
                </div>
            </form>
        </div>

        <!-- Delete Section (if no jobs) -->
        @if($category->jobs_count == 0)
        <div class="p-6 mt-6 bg-white rounded-lg border-2 border-red-200 shadow-sm">
            <h3 class="text-lg font-semibold text-red-900">Danger Zone</h3>
            <p class="mt-1 text-sm text-red-700">Once you delete a category, there is no going back. Please be certain.</p>
            
            <form action="{{ route('admin.categories.destroy', $category) }}" 
                  method="POST" 
                  class="mt-4"
                  onsubmit="return confirm('Are you absolutely sure? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md border border-transparent shadow-sm hover:bg-red-700">
                    <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Category
                </button>
            </form>
        </div>
        @else
        <div class="p-4 mt-6 text-sm text-yellow-700 bg-yellow-50 rounded-lg border border-yellow-200">
            <div class="flex">
                <svg class="flex-shrink-0 w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <p class="font-medium">Cannot Delete</p>
                    <p class="mt-1">This category cannot be deleted because it has {{ $category->jobs_count }} associated job(s). To delete this category, first reassign or delete all jobs using this category.</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Live Icon Preview Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconInput = document.getElementById('icon');
            const iconPreview = document.getElementById('icon-preview');
            
            if (iconInput && iconPreview) {
                iconInput.addEventListener('input', function() {
                    const value = this.value.trim();
                    iconPreview.textContent = value || '📁';
                });
            }
        });
    </script>

</x-layouts.dashboard>

