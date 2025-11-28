<x-layouts.dashboard>
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Artikel</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola artikel dan panduan untuk pengguna</p>
            </div>
            <a href="{{ route('admin.articles.create') }}" 
                    class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700"
                >
                    <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Buat Artikel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <!-- Filters & Search -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="flex flex-col gap-4 sm:flex-row">
                <!-- Search -->
                <div class="flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Cari artikel..." 
                        value="{{ request('search') }}"
                        class="px-3 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <select 
                        name="status" 
                        class="px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <button 
                    type="submit" 
                    class="px-4 py-2 text-white bg-gray-600 rounded-lg transition hover:bg-gray-700"
                >
                    Filter
                </button>
            </form>
        </div>

        <!-- Articles Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Artikel
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Author
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Status
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                            Published
                        </th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($articles as $article)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($article->featured_image)
                                        <img 
                                            src="{{ $article->featured_image_url }}" 
                                            alt="{{ $article->title }}"
                                            class="object-cover mr-4 w-12 h-12 rounded-lg"
                                        >
                                    @else
                                        <div class="flex justify-center items-center mr-4 w-12 h-12 bg-gray-200 rounded-lg">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 line-clamp-1">
                                            {{ $article->title }}
                                        </div>
                                        <div class="text-sm text-gray-500 line-clamp-1">
                                            {{ $article->excerpt }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img 
                                        src="{{ $article->author->avatar_url }}" 
                                        alt="{{ $article->author->name }}"
                                        class="mr-3 w-8 h-8 rounded-full"
                                    >
                                    <div class="text-sm text-gray-900">{{ $article->author->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($article->status === 'published')
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                        Published
                                    </span>
                                @elseif($article->status === 'draft')
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-yellow-800 bg-yellow-100 rounded-full">
                                        Draft
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium text-gray-800 bg-gray-100 rounded-full">
                                        Archived
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $article->published_at ? $article->published_at->format('d M Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex justify-end items-center space-x-2">
                                    <a 
                                        href="{{ route('articles.show', $article) }}" 
                                        target="_blank"
                                        class="text-indigo-600 hover:text-indigo-900"
                                        title="Lihat"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a 
                                        href="{{ route('admin.articles.edit', $article) }}" 
                                        class="text-indigo-600 hover:text-indigo-900"
                                        title="Edit"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form 
                                        action="{{ route('admin.articles.destroy', $article) }}" 
                                        method="POST" 
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            title="Hapus"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada artikel</h3>
                                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat artikel pertama Anda.</p>
                                <div class="mt-6">
                                    <a 
                                        href="{{ route('admin.articles.create') }}" 
                                        class="inline-flex items-center px-4 py-2 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700"
                                    >
                                        <svg class="mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Buat Artikel
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($articles->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</x-layouts.dashboard>
