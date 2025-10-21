<x-layouts.dashboard>
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Artikel</h1>
                    <p class="text-gray-600">{{ $article->title }}</p>
                </div>
                <a 
                    href="{{ route('admin.articles.index') }}" 
                    class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg transition hover:bg-gray-200"
                >
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="mb-6">
                <label for="title" class="block mb-2 text-sm font-medium text-gray-700">
                    Judul Artikel <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title', $article->title) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                    placeholder="Masukkan judul artikel"
                    required
                >
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Excerpt -->
            <div class="mb-6">
                <label for="excerpt" class="block mb-2 text-sm font-medium text-gray-700">
                    Ringkasan Artikel
                </label>
                <textarea 
                    id="excerpt" 
                    name="excerpt" 
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('excerpt') border-red-500 @enderror"
                    placeholder="Ringkasan singkat artikel (opsional)"
                >{{ old('excerpt', $article->excerpt) }}</textarea>
                @error('excerpt')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content -->
            <div class="mb-6">
                <label for="content" class="block mb-2 text-sm font-medium text-gray-700">
                    Konten Artikel <span class="text-red-500">*</span>
                </label>
                <textarea 
                    id="content" 
                    name="content" 
                    rows="15"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('content') border-red-500 @enderror"
                    placeholder="Tulis konten artikel di sini..."
                    required
                >{{ old('content', $article->content) }}</textarea>
                @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">
                    Gunakan toolbar di atas untuk memformat teks. Tips: Gunakan Heading 3 untuk subjudul, Bold untuk penekanan, dan Bullet List untuk daftar.
                </p>
            </div>

            <!-- Featured Image -->
            <div class="mb-6">
                <label for="featured_image" class="block mb-2 text-sm font-medium text-gray-700">
                    Gambar Utama
                </label>
                
                @if($article->featured_image)
                    <div class="mb-4">
                        <img 
                            src="{{ $article->featured_image_url }}" 
                            alt="{{ $article->title }}"
                            class="object-cover w-32 h-32 rounded-lg"
                        >
                        <p class="mt-2 text-sm text-gray-500">Gambar saat ini</p>
                    </div>
                @endif
                
                <input 
                    type="file" 
                    id="featured_image" 
                    name="featured_image" 
                    accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('featured_image') border-red-500 @enderror"
                >
                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah.</p>
                @error('featured_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status & Published Date -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-700">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="status" 
                        name="status" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror"
                        required
                    >
                        <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ old('status', $article->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="published_at" class="block mb-2 text-sm font-medium text-gray-700">
                        Tanggal Publikasi
                    </label>
                    <input 
                        type="datetime-local" 
                        id="published_at" 
                        name="published_at" 
                        value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('published_at') border-red-500 @enderror"
                    >
                    @error('published_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Meta Data -->
            <div class="mb-6">
                <h3 class="mb-4 text-lg font-medium text-gray-900">SEO Meta Data</h3>
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="meta_title" class="block mb-2 text-sm font-medium text-gray-700">
                            Meta Title
                        </label>
                        <input 
                            type="text" 
                            id="meta_title" 
                            name="meta_title" 
                            value="{{ old('meta_title', $article->meta_data['title'] ?? '') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('meta_title') border-red-500 @enderror"
                            placeholder="Meta title untuk SEO"
                        >
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block mb-2 text-sm font-medium text-gray-700">
                            Meta Description
                        </label>
                        <textarea 
                            id="meta_description" 
                            name="meta_description" 
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 @error('meta_description') border-red-500 @enderror"
                            placeholder="Meta description untuk SEO"
                        >{{ old('meta_description', $article->meta_data['description'] ?? '') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end items-center pt-6 space-x-4 border-t border-gray-200">
                <a 
                    href="{{ route('admin.articles.index') }}" 
                    class="px-6 py-2 text-gray-700 bg-gray-100 rounded-lg transition hover:bg-gray-200"
                >
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700 hover:shadow-lg"
                >
                    Update Artikel
                </button>
            </div>
        </form>
    </div>

    <!-- Simple Editor Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contentTextarea = document.getElementById('content');
            
            // Create toolbar
            const toolbar = document.createElement('div');
            toolbar.className = 'mb-2 p-2 bg-gray-100 border border-gray-300 rounded-t-lg flex flex-wrap gap-2';
            
            // Toolbar buttons
            const buttons = [
                { name: 'bold', label: 'B', title: 'Bold' },
                { name: 'italic', label: 'I', title: 'Italic' },
                { name: 'underline', label: 'U', title: 'Underline' },
                { name: 'separator', label: '|' },
                { name: 'h3', label: 'H3', title: 'Heading 3' },
                { name: 'h4', label: 'H4', title: 'Heading 4' },
                { name: 'separator', label: '|' },
                { name: 'ul', label: '•', title: 'Bullet List' },
                { name: 'ol', label: '1.', title: 'Numbered List' },
                { name: 'separator', label: '|' },
                { name: 'link', label: '🔗', title: 'Insert Link' },
                { name: 'separator', label: '|' },
                { name: 'clear', label: 'Clear', title: 'Clear Formatting' }
            ];
            
            buttons.forEach(button => {
                if (button.name === 'separator') {
                    const separator = document.createElement('span');
                    separator.className = 'text-gray-400 mx-1';
                    separator.textContent = button.label;
                    toolbar.appendChild(separator);
                } else {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'px-2 py-1 text-sm bg-white border border-gray-300 rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500';
                    btn.textContent = button.label;
                    btn.title = button.title;
                    btn.onclick = () => formatText(button.name);
                    toolbar.appendChild(btn);
                }
            });
            
            // Insert toolbar before textarea
            contentTextarea.parentNode.insertBefore(toolbar, contentTextarea);
            
            // Formatting functions
            function formatText(command) {
                const start = contentTextarea.selectionStart;
                const end = contentTextarea.selectionEnd;
                const selectedText = contentTextarea.value.substring(start, end);
                let formattedText = '';
                
                switch(command) {
                    case 'bold':
                        formattedText = `<strong>${selectedText || 'Bold text'}</strong>`;
                        break;
                    case 'italic':
                        formattedText = `<em>${selectedText || 'Italic text'}</em>`;
                        break;
                    case 'underline':
                        formattedText = `<u>${selectedText || 'Underlined text'}</u>`;
                        break;
                    case 'h3':
                        formattedText = `<h3>${selectedText || 'Heading 3'}</h3>`;
                        break;
                    case 'h4':
                        formattedText = `<h4>${selectedText || 'Heading 4'}</h4>`;
                        break;
                    case 'ul':
                        formattedText = `<ul>\n<li>${selectedText || 'List item'}</li>\n</ul>`;
                        break;
                    case 'ol':
                        formattedText = `<ol>\n<li>${selectedText || 'List item'}</li>\n</ol>`;
                        break;
                    case 'link':
                        const url = prompt('Enter URL:');
                        if (url) {
                            formattedText = `<a href="${url}">${selectedText || 'Link text'}</a>`;
                        }
                        return;
                    case 'clear':
                        formattedText = selectedText.replace(/<[^>]*>/g, '');
                        break;
                }
                
                if (formattedText) {
                    const newValue = contentTextarea.value.substring(0, start) + formattedText + contentTextarea.value.substring(end);
                    contentTextarea.value = newValue;
                    
                    // Set cursor position after inserted text
                    const newPosition = start + formattedText.length;
                    contentTextarea.setSelectionRange(newPosition, newPosition);
                    contentTextarea.focus();
                }
            }
        });
    </script>
</x-layouts.dashboard>
