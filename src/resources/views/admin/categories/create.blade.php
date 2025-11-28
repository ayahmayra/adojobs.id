<x-layouts.dashboard>
    <x-slot name="title">Create Category</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Kategori Baru</h1>
                <p class="mt-1 text-sm text-gray-600">Tambahkan kategori baru untuk lowongan pekerjaan</p>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <!-- Form Card -->
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <!-- Category Name -->
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror"
                           placeholder="e.g., Software Development">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
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
                              placeholder="Brief description of this category...">{{ old('description') }}</textarea>
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
                               value="{{ old('icon') }}"
                               maxlength="10"
                               class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('icon') border-red-500 @enderror"
                               placeholder="e.g., 💼 or 🎨">
                        <div class="flex justify-center items-center w-12 h-12 text-2xl bg-gray-100 rounded-lg border border-gray-200">
                            <span id="icon-preview">📁</span>
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
                           value="{{ old('order', 0) }}"
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
                                   {{ old('is_active', '1') == '1' ? 'checked' : '' }}
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
                                   {{ old('is_active') == '0' ? 'checked' : '' }}
                                   class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">
                                <span class="font-medium">Inactive</span> - Category will be hidden from users
                            </span>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
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
                        Create Category
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Info -->
        <div class="p-4 mt-6 text-sm text-blue-700 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex">
                <svg class="flex-shrink-0 w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="ml-3">
                    <p class="font-medium">Tips for Creating Categories:</p>
                    <ul class="mt-2 ml-5 space-y-1 list-disc list-inside">
                        <li>Choose clear, descriptive names that users will easily understand</li>
                        <li>Keep category names short and concise</li>
                        <li>Use emoji icons to make categories more visually appealing</li>
                        <li>Set appropriate order numbers for logical grouping</li>
                    </ul>
                </div>
            </div>
        </div>
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

