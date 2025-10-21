<x-layouts.app>
    <div class="bg-white">
        <div class="px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12 text-center">
                <h1 class="mb-4 text-4xl font-bold text-gray-900">Artikel & Panduan</h1>
                <p class="mx-auto max-w-3xl text-xl text-gray-600">
                    Temukan tips, panduan, dan informasi terbaru tentang cara menggunakan platform AdoJobs.id untuk mencari pekerjaan atau merekrut talenta terbaik.
                </p>
            </div>

            <!-- Search & Filters -->
            <div class="mb-8">
                <form action="{{ route('articles.index') }}" method="GET" class="flex flex-col gap-4 sm:flex-row">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari artikel..." 
                            value="{{ request('search') }}"
                            class="px-4 py-3 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                    <button 
                        type="submit" 
                        class="px-6 py-3 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700"
                    >
                        Cari
                    </button>
                </form>
            </div>

            <!-- Articles Grid -->
            @if($articles->count() > 0)
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($articles as $article)
                        <article class="overflow-hidden bg-white rounded-lg border border-gray-200 shadow-sm transition hover:shadow-md">
                            <!-- Featured Image -->
                            <div class="aspect-w-16 aspect-h-9">
                                <img 
                                    src="{{ $article->featured_image_url }}" 
                                    alt="{{ $article->title }}"
                                    class="object-cover w-full h-48"
                                >
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Meta Info -->
                                <div class="flex items-center mb-3 text-sm text-gray-500">
                                    <span>{{ $article->author->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $article->formatted_published_date }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $article->reading_time }} min baca</span>
                                </div>

                                <!-- Title -->
                                <h2 class="mb-3 text-xl font-semibold text-gray-900 line-clamp-2">
                                    <a href="{{ route('articles.show', $article) }}" class="hover:text-indigo-600">
                                        {{ $article->title }}
                                    </a>
                                </h2>

                                <!-- Excerpt -->
                                <p class="mb-4 text-gray-600 line-clamp-3">
                                    {{ $article->excerpt }}
                                </p>

                                <!-- Footer -->
                                <div class="flex justify-end items-center">
                                    <a 
                                        href="{{ route('articles.show', $article) }}" 
                                        class="text-sm font-medium text-indigo-600 hover:text-indigo-800"
                                    >
                                        Baca selengkapnya →
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $articles->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="py-12 text-center">
                    <svg class="mx-auto w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada artikel</h3>
                    <p class="mt-2 text-gray-500">
                        @if(request('search'))
                            Tidak ada artikel yang sesuai dengan pencarian "{{ request('search') }}"
                        @else
                            Artikel akan segera tersedia
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
