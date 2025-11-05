<x-layouts.dashboard>
    <x-slot name="title">Pengaturan Website</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengaturan Website</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola pengaturan umum website</p>
            </div>
        </div>
    </x-slot>

<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <!-- Content -->
    <div class="mb-8">
        <p class="mt-2 text-gray-600">Kelola pengaturan umum website AdoJobs.id</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Settings Cards -->
    <div class="grid grid-cols-1 gap-6">
        <!-- General Settings Form -->
        <div class="lg:col-span-2 p-6 bg-white rounded-lg shadow">
            <div class="flex items-center mb-6">
                <div class="flex justify-center items-center w-12 h-12 text-indigo-600 bg-indigo-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Pengaturan Umum</h3>
                    <p class="text-sm text-gray-600">Kelola nama website, deskripsi, logo, dan favicon</p>
                </div>
            </div>

            <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Site Name -->
                        <div>
                            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Website <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="site_name" 
                                   id="site_name" 
                                   value="{{ old('site_name', $settings['site_name']) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('site_name') border-red-500 @enderror"
                                   required>
                            @error('site_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Site Description -->
                        <div>
                            <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Website <span class="text-red-500">*</span>
                            </label>
                            <textarea name="site_description" 
                                      id="site_description" 
                                      rows="4"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('site_description') border-red-500 @enderror"
                                      required>{{ old('site_description', $settings['site_description']) }}</textarea>
                            @error('site_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <label for="site_logo" class="block text-sm font-medium text-gray-700 mb-2">
                                Logo Website
                            </label>
                            
                            @if($settings['site_logo'])
                            <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" 
                                             alt="Logo" 
                                             class="h-16 w-auto object-contain">
                                        <span class="text-sm text-gray-600">Logo saat ini</span>
                                    </div>
                                    <button type="button" 
                                            onclick="deleteFile('logo')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            @endif

                            <input type="file" 
                                   name="site_logo" 
                                   id="site_logo" 
                                   accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('site_logo') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Format: PNG, JPG, JPEG, SVG. Max: 2MB</p>
                            @error('site_logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Favicon Upload -->
                        <div>
                            <label for="site_favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                Favicon
                            </label>
                            
                            @if($settings['site_favicon'])
                            <div class="mb-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" 
                                             alt="Favicon" 
                                             class="h-8 w-8 object-contain">
                                        <span class="text-sm text-gray-600">Favicon saat ini</span>
                                    </div>
                                    <button type="button" 
                                            onclick="deleteFile('favicon')"
                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                            @endif

                            <input type="file" 
                                   name="site_favicon" 
                                   id="site_favicon" 
                                   accept="image/png,image/x-icon,image/jpeg,image/jpg"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('site_favicon') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Format: PNG, ICO, JPG, JPEG. Max: 1MB. Ukuran ideal: 32x32px</p>
                            @error('site_favicon')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Banner Upload Section -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Banner Utama</h3>
                        <p class="text-sm text-gray-600">Upload banner utama untuk ditampilkan di halaman website. Banner ini akan digunakan sesuai kebutuhan desain.</p>
                    </div>

                    <div>
                        <label for="site_banner" class="block text-sm font-medium text-gray-700 mb-2">
                            Banner Utama
                        </label>
                        
                        @if($settings['site_banner'])
                        <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-gray-700">Banner saat ini</span>
                                    <p class="mt-1 text-xs text-gray-500">Klik gambar untuk melihat ukuran penuh</p>
                                </div>
                                <button type="button" 
                                        onclick="deleteFile('banner')"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Hapus
                                </button>
                            </div>
                            <div class="overflow-hidden rounded-lg border border-gray-200">
                                <img src="{{ asset('storage/' . $settings['site_banner']) }}" 
                                     alt="Banner" 
                                     class="w-full h-auto object-contain max-h-64 cursor-pointer"
                                     onclick="window.open('{{ asset('storage/' . $settings['site_banner']) }}', '_blank')"
                                     title="Klik untuk melihat ukuran penuh">
                            </div>
                        </div>
                        @endif

                        <input type="file" 
                               name="site_banner" 
                               id="site_banner" 
                               accept="image/png,image/jpeg,image/jpg"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent @error('site_banner') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">
                            Format: PNG, JPG, JPEG. Max: 5MB. 
                            <span class="text-gray-600 font-medium">Rekomendasi ukuran: 1920x600px atau rasio 16:5</span>
                        </p>
                        @error('site_banner')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Other Settings Placeholder -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Email Settings Placeholder -->
            <div class="p-6 bg-white rounded-lg shadow opacity-60">
                <div class="flex items-center mb-4">
                    <div class="flex justify-center items-center w-12 h-12 text-green-600 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan Email</h3>
                        <p class="text-sm text-gray-600">Segera hadir</p>
                    </div>
                </div>
            </div>

            <!-- SEO Settings Placeholder -->
            <div class="p-6 bg-white rounded-lg shadow opacity-60">
                <div class="flex items-center mb-4">
                    <div class="flex justify-center items-center w-12 h-12 text-purple-600 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan SEO</h3>
                        <p class="text-sm text-gray-600">Segera hadir</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for file deletion -->
<script>
function deleteFile(type) {
    const typeNames = {
        'logo': 'logo',
        'favicon': 'favicon',
        'banner': 'banner utama'
    };
    
    if (!confirm(`Apakah Anda yakin ingin menghapus ${typeNames[type] || type}?`)) {
        return;
    }

    fetch('{{ route('admin.settings.file.delete') }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Gagal menghapus file');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menghapus file');
    });
}
</script>

</x-layouts.dashboard>
