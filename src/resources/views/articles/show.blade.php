<x-layouts.app>
    <div class="bg-white">
        <div class="px-4 py-8 mx-auto max-w-4xl sm:px-6 lg:px-8">
            <!-- Article Header -->
            <header class="mb-8">
                <!-- Breadcrumb -->
                <nav class="mb-4">
                    <ol class="flex items-center space-x-2 text-sm text-gray-500">
                        <li><a href="{{ route('home') }}" class="hover:text-indigo-600">Beranda</a></li>
                        <li><span>/</span></li>
                        <li><a href="{{ route('articles.index') }}" class="hover:text-indigo-600">Artikel</a></li>
                        <li><span>/</span></li>
                        <li class="text-gray-900">{{ $article->title }}</li>
                    </ol>
                </nav>

                <!-- Title -->
                <h1 class="mb-4 text-4xl font-bold text-gray-900">{{ $article->title }}</h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap gap-4 items-center mb-6 text-sm text-gray-600">
                    <div class="flex items-center">
                        @if($article->author)
                            <img 
                                src="{{ $article->author->avatar_url }}" 
                                alt="{{ $article->author->name }}"
                                class="mr-2 w-8 h-8 rounded-full"
                            >
                            <span>Oleh {{ $article->author->name }}</span>
                        @else
                            <span>Oleh AdoJobs.id</span>
                        @endif
                    </div>
                    <span>•</span>
                    <span>{{ $article->formatted_published_date }}</span>
                    <span>•</span>
                    <span>{{ $article->reading_time }} menit baca</span>
                </div>

                <!-- Featured Image -->
                @if($article->featured_image)
                    <div class="mb-8 aspect-w-16 aspect-h-9">
                        <img 
                            src="{{ $article->featured_image_url }}" 
                            alt="{{ $article->title }}"
                            class="object-cover w-full h-64 rounded-lg"
                        >
                    </div>
                @endif
            </header>

            <!-- Admin Edit Button -->
            @auth
                @if(Auth::user()->isAdmin())
                    <div class="p-4 mb-6 bg-blue-50 rounded-lg border border-blue-200">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <svg class="mr-2 w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-800">Admin Mode</span>
                            </div>
                            <a 
                                href="{{ route('admin.articles.edit', $article) }}" 
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-lg transition hover:bg-blue-200"
                            >
                                <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Artikel
                            </a>
                        </div>
                    </div>
                @endif
            @endauth

            <!-- Article Content -->
            <article class="max-w-none prose prose-lg">
                <div class="leading-relaxed text-gray-600">
                    {!! $article->content !!}
                </div>
            </article>

            <!-- Article Footer -->
            <footer class="pt-8 mt-12 border-t border-gray-200">
                <!-- Share Buttons -->
                <div class="mb-8">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Bagikan artikel ini</h3>
                    <div class="flex space-x-4">
                        <a 
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                            target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-blue-600 rounded-lg transition hover:bg-blue-700"
                        >
                            <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Facebook
                        </a>
                        <a 
                            href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}" 
                            target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-blue-400 rounded-lg transition hover:bg-blue-500"
                        >
                            <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            Twitter
                        </a>
                        <a 
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}" 
                            target="_blank"
                            class="flex items-center px-4 py-2 text-white bg-blue-700 rounded-lg transition hover:bg-blue-800"
                        >
                            <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </a>
                    </div>
                </div>

                <!-- Related Articles -->
                @if($relatedArticles->count() > 0)
                    <div>
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Artikel terkait</h3>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            @foreach($relatedArticles as $relatedArticle)
                                <article class="p-4 bg-gray-50 rounded-lg transition hover:bg-gray-100">
                                    <h4 class="mb-2 font-semibold text-gray-900 line-clamp-2">
                                        <a href="{{ route('articles.show', $relatedArticle) }}" class="hover:text-indigo-600">
                                            {{ $relatedArticle->title }}
                                        </a>
                                    </h4>
                                    <p class="mb-2 text-sm text-gray-600 line-clamp-2">
                                        {{ $relatedArticle->excerpt }}
                                    </p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <span>{{ $relatedArticle->formatted_published_date }}</span>
                                        <span class="mx-2">•</span>
                                        <span>{{ $relatedArticle->reading_time }} min</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif
            </footer>
        </div>
    </div>
</x-layouts.app>
