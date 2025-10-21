<x-layouts.app>
    <x-slot name="title">Kategori Lowongan Kerja</x-slot>

    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="px-4 py-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white">Kategori Lowongan Kerja</h1>
                <p class="mt-4 text-xl text-indigo-100">
                    Temukan lowongan berdasarkan kategori yang sesuai dengan keahlian Anda
                </p>
            </div>
        </div>
    </div>

    {{-- Categories Grid --}}
    <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($categories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="block p-6 bg-white rounded-lg shadow-sm transition group hover:shadow-md hover:scale-105">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($category->icon)
                                <div class="flex justify-center items-center w-12 h-12 text-2xl bg-indigo-100 rounded-lg">
                                    <span class="emoji-icon" data-icon="{{ $category->icon }}">{{ $category->icon }}</span>
                                </div>
                            @else
                                <div class="flex justify-center items-center w-12 h-12 bg-gray-100 rounded-lg">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600">
                                {{ $category->name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ $category->jobs_count }} lowongan tersedia
                            </p>
                            @if($category->description)
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">
                                    {{ $category->description }}
                                </p>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full">
                    <div class="p-12 text-center bg-white rounded-lg shadow-sm">
                        <svg class="mx-auto w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">Belum Ada Kategori</h3>
                        <p class="mt-2 text-gray-600">Kategori lowongan akan muncul di sini</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- CTA Section --}}
    <div class="bg-gray-50">
        <div class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Tidak Menemukan Kategori yang Sesuai?</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Jelajahi semua lowongan yang tersedia atau pasang lowongan baru
                </p>
                <div class="flex gap-4 justify-center mt-8">
                    <a href="{{ route('jobs.index') }}" 
                       class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                        Lihat Semua Lowongan
                    </a>
                    @auth
                        @if(auth()->user()->isEmployer())
                            <a href="{{ route('employer.jobs.create') }}" 
                               class="inline-flex items-center px-6 py-3 text-base font-medium text-indigo-600 bg-white rounded-lg border border-indigo-600 transition hover:bg-indigo-50">
                                Pasang Lowongan
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" 
                           class="inline-flex items-center px-6 py-3 text-base font-medium text-indigo-600 bg-white rounded-lg border border-indigo-600 transition hover:bg-indigo-50">
                            Daftar sebagai Employer
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Emoji Icon Fix --}}
    <style>
        .emoji-icon {
            font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', 'Twemoji Mozilla', sans-serif;
            font-size: 1.5rem;
            line-height: 1;
            display: inline-block;
            text-align: center;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iconMappings = {
                'computer': '💻', 'megaphone': '📢', 'palette': '🎨', 'calculator': '🧮', 'users': '👥',
                'cog': '⚙️', 'heart': '❤️', 'briefcase': '💼', 'chart': '📊', 'building': '🏢',
                'mobile': '📱', 'wrench': '🔧', 'globe': '🌐', 'paint': '🎨', 'money': '💰',
                'car': '🚗', 'home': '🏠', 'hospital': '🏥', 'book': '📚', 'music': '🎵',
                'camera': '📷', 'star': '⭐', 'lightbulb': '💡', 'target': '🎯', 'shield': '🛡️'
            };
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
</x-layouts.app>
