<x-layouts.dashboard>
    <x-slot name="sidebar">
        @if(Auth::user()->isSeeker())
            <x-sidebar.seeker />
        @elseif(Auth::user()->isEmployer())
            <x-sidebar.employer />
        @elseif(Auth::user()->isAdmin())
            <x-sidebar.admin />
        @endif
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-[calc(100vh-8rem)]">
        <div class="flex h-full">
            <!-- Left Sidebar - Conversations List -->
            <div class="flex flex-col w-full border-r border-gray-200 md:w-96">
                <!-- Header -->
                <div class="p-4 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-900">Pesan</h2>
                        @if($unreadCount > 0)
                            <span class="px-2 py-1 text-xs font-bold text-white bg-red-500 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </div>

                    <!-- Search -->
                    <form action="{{ route('messages.index') }}" method="GET" class="mb-3">
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Cari percakapan..." 
                            value="{{ request('search') }}"
                            class="px-4 py-2 w-full rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </form>

                    <!-- Filters -->
                    <div class="flex gap-2">
                        <a href="{{ route('messages.index') }}" 
                           class="flex-1 text-center px-3 py-2 text-sm rounded-lg {{ !request('filter') ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            Semua
                        </a>
                        <a href="{{ route('messages.index', ['filter' => 'unread']) }}" 
                           class="flex-1 text-center px-3 py-2 text-sm rounded-lg {{ request('filter') === 'unread' ? 'bg-indigo-100 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            Belum Dibaca
                        </a>
                    </div>
                </div>

                <!-- Conversations List -->
                <div class="overflow-y-auto flex-1">
                    @forelse($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation) }}" 
                           class="block p-4 border-b border-gray-200 hover:bg-gray-50 transition {{ $conversation->unread_count > 0 ? 'bg-indigo-50' : '' }}">
                            <div class="flex gap-3 items-start">
                                <!-- Avatar -->
                                <img src="{{ $conversation->other_participant_avatar }}" 
                                     alt="{{ $conversation->other_participant }}" 
                                     class="object-cover flex-shrink-0 w-12 h-12 rounded-full">
                                
                                <div class="flex-1 min-w-0">
                                    <!-- Name & Time -->
                                    <div class="flex justify-between items-center mb-1">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $conversation->other_participant }}
                                        </h3>
                                        <span class="flex-shrink-0 ml-2 text-xs text-gray-500">
                                            {{ $conversation->last_message_at?->diffForHumans() ?? $conversation->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <!-- Subject -->
                                    <p class="mb-1 text-sm text-gray-600 truncate">
                                        {{ $conversation->subject }}
                                    </p>

                                    <!-- Last Message Preview -->
                                    @if($conversation->lastMessage)
                                        <p class="text-sm text-gray-500 truncate">
                                            @if($conversation->lastMessage->sender_id === auth()->id())
                                                <span class="font-medium">Anda:</span>
                                            @endif
                                            {{ Str::limit($conversation->lastMessage->message, 50) }}
                                        </p>
                                    @endif

                                    <!-- Unread Badge -->
                                    @if($conversation->unread_count > 0)
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-bold text-white bg-indigo-600 rounded-full">
                                                {{ $conversation->unread_count }} baru
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada percakapan</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai berkirim pesan dengan rekruter atau kandidat.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($conversations->hasPages())
                    <div class="p-4 border-t border-gray-200">
                        {{ $conversations->links() }}
                    </div>
                @endif
            </div>

            <!-- Right Side - Empty State (shown when no conversation selected) -->
            <div class="hidden flex-1 justify-center items-center bg-gray-50 md:flex">
                <div class="text-center">
                    <svg class="mx-auto w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Pilih percakapan</h3>
                    <p class="mt-2 text-sm text-gray-500">Pilih percakapan dari daftar untuk melihat pesan</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dashboard>

