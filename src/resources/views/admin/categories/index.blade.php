<x-layouts.dashboard>
    <x-slot name="title">Manage Categories</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Kategori</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola kategori lowongan pekerjaan</p>
            </div>
        </div>
    </x-slot>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="p-4 mb-6 text-green-700 bg-green-100 rounded-lg border border-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-6 text-red-700 bg-red-100 rounded-lg border border-red-400">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Categories</h2>
            <p class="mt-1 text-sm text-gray-600">Kelola kategori pekerjaan</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
           class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
            <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New Category
        </a>
    </div>

    <!-- Categories Table -->
    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Order
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Category
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Jobs
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        <span class="inline-flex justify-center items-center w-8 h-8 font-medium text-gray-700 bg-gray-100 rounded">
                            {{ $category->order }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($category->icon)
                                <div class="flex-shrink-0 mr-3 text-2xl leading-none">
                                    <span class="inline-block w-8 h-8 text-center emoji-icon" data-icon="{{ $category->icon }}">{{ $category->icon }}</span>
                                </div>
                            @else
                                <div class="flex flex-shrink-0 justify-center items-center mr-3 w-8 h-8 bg-gray-100 rounded-full">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $category->name }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $category->slug }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        <div class="text-sm text-gray-600 truncate">
                            {{ $category->description ?? '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-indigo-800 bg-indigo-100 rounded-full">
                            {{ $category->jobs_count }} jobs
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($category->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                <svg class="mr-1 w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <div class="flex gap-2 justify-end">
                            <!-- Edit Button -->
                            <a href="{{ route('admin.categories.edit', $category) }}" 
                               class="text-indigo-600 hover:text-indigo-900"
                               title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.categories.destroy', $category) }}" 
                                  method="POST" 
                                  class="inline-block"
                                  onsubmit="return confirm('Are you sure you want to delete this category? This will also affect {{ $category->jobs_count }} jobs.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Delete"
                                        {{ $category->jobs_count > 0 ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No categories</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.categories.create') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent shadow-sm hover:bg-indigo-700">
                                <svg class="mr-2 -ml-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                New Category
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Info Box -->
    @if($categories->count() > 0)
    <div class="p-4 mt-6 text-sm text-blue-700 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex">
            <svg class="flex-shrink-0 w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div class="ml-3">
                <p class="font-medium">Category Management Tips:</p>
                <ul class="mt-2 ml-5 space-y-1 list-disc list-inside">
                    <li>Categories with jobs cannot be deleted</li>
                    <li>Use "Order" to control the display sequence</li>
                    <li>Icons support emoji characters (e.g., 📁, 💼, 🎨)</li>
                    <li>Inactive categories won't appear in public listings</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Emoji Icon Fix CSS & JavaScript -->
    <style>
        .emoji-icon {
            font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', 'Twemoji Mozilla', sans-serif;
            font-size: 1.5rem;
            line-height: 1;
            display: inline-block;
            text-align: center;
            width: 2rem;
            height: 2rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mapping teks ke emoji untuk fix data yang salah
            const iconMappings = {
                'computer': '💻',
                'megaphone': '📢',
                'palette': '🎨',
                'calculator': '🧮',
                'users': '👥',
                'cog': '⚙️',
                'heart': '❤️',
                'briefcase': '💼',
                'chart': '📊',
                'building': '🏢',
                'mobile': '📱',
                'wrench': '🔧',
                'globe': '🌐',
                'paint': '🎨',
                'money': '💰',
                'car': '🚗',
                'home': '🏠',
                'hospital': '🏥',
                'book': '📚',
                'music': '🎵',
                'camera': '📷',
                'star': '⭐',
                'lightbulb': '💡',
                'target': '🎯',
                'shield': '🛡️'
            };

            // Fix icon yang masih berupa teks
            document.querySelectorAll('.emoji-icon').forEach(function(element) {
                const currentIcon = element.getAttribute('data-icon');
                const emojiIcon = iconMappings[currentIcon];
                
                if (emojiIcon && currentIcon !== emojiIcon) {
                    element.textContent = emojiIcon;
                    element.setAttribute('data-icon', emojiIcon);
                }
            });
        });
    </script>

</x-layouts.dashboard>

