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

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Percakapan</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $conversation->subject }}</p>
            </div>
            <a href="{{ route('messages.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-[calc(100vh-8rem)]">
        <div class="flex flex-col h-full">
            <!-- Chat Header -->
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <div class="flex gap-3 items-center">
                    <!-- Back Button (Mobile) -->
                    <a href="{{ route('messages.index') }}" class="text-gray-600 md:hidden hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>

                    <!-- Avatar & Name -->
                    <div class="flex-shrink-0">
                        @if($conversation->other_participant_profile_url)
                            <a href="{{ $conversation->other_participant_profile_url }}" 
                               target="_blank"
                               class="block group"
                               title="Lihat Profile">
                                <img src="{{ $conversation->other_participant_avatar }}" 
                                     alt="{{ $conversation->other_participant }}" 
                                     class="object-cover w-10 h-10 rounded-full ring-2 ring-transparent transition group-hover:ring-indigo-500">
                            </a>
                        @else
                            <img src="{{ $conversation->other_participant_avatar }}" 
                                 alt="{{ $conversation->other_participant }}" 
                                 class="object-cover w-10 h-10 rounded-full">
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            @if($conversation->other_participant_profile_url)
                                <a href="{{ $conversation->other_participant_profile_url }}" 
                                   target="_blank"
                                   class="hover:text-indigo-600 hover:underline">
                                    {{ $conversation->other_participant }}
                                    <svg class="inline-block ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @else
                                {{ $conversation->other_participant }}
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500">{{ $conversation->subject }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 items-center">
                    @if($conversation->job)
                        <a href="{{ route('jobs.show', $conversation->job->slug) }}" 
                           target="_blank"
                           class="px-3 py-1.5 text-sm text-gray-700 bg-gray-100 rounded-lg transition hover:bg-gray-200">
                            <svg class="inline-block mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Lihat Lowongan
                        </a>
                    @endif

                    <form action="{{ route('messages.destroy', $conversation) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan percakapan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-gray-600 transition hover:text-red-600" title="Arsipkan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="overflow-y-auto flex-1 p-4 space-y-4" id="messages-container">
                @forelse($conversation->messages as $message)
                    @if($message->sender_type === 'system')
                        <!-- System Message -->
                        <div class="flex justify-center">
                            <div class="px-4 py-3 max-w-2xl text-sm bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex gap-2 items-start">
                                    <svg class="flex-shrink-0 mt-0.5 w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-medium text-blue-900 whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                        <span class="inline-block mt-1 text-xs text-blue-600">
                                            {{ $message->created_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Regular User Message -->
                        <div class="flex {{ $message->isFromCurrentUser() ? 'justify-end' : 'justify-start' }}">
                            <div class="flex items-end gap-2 max-w-md {{ $message->isFromCurrentUser() ? 'flex-row-reverse' : 'flex-row' }}">
                                <!-- Avatar -->
                                @if(!$message->isFromCurrentUser())
                                    <div class="flex-shrink-0">
                                        @if($conversation->other_participant_profile_url)
                                            <a href="{{ $conversation->other_participant_profile_url }}" 
                                               target="_blank"
                                               class="block group"
                                               title="Lihat Profile">
                                                <img src="{{ $conversation->other_participant_avatar }}" 
                                                     alt="{{ $message->sender->name }}" 
                                                     class="object-cover w-8 h-8 rounded-full ring-2 ring-transparent transition group-hover:ring-indigo-500">
                                            </a>
                                        @else
                                            <img src="{{ $conversation->other_participant_avatar }}" 
                                                 alt="{{ $message->sender->name }}" 
                                                 class="object-cover w-8 h-8 rounded-full">
                                        @endif
                                    </div>
                                @endif

                                <!-- Message Bubble -->
                                <div class="flex flex-col {{ $message->isFromCurrentUser() ? 'items-end' : 'items-start' }}">
                                    <div class="px-4 py-2 rounded-lg {{ $message->isFromCurrentUser() ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                                        <p class="text-sm whitespace-pre-wrap break-words">{{ $message->message }}</p>
                                    </div>
                                    <span class="px-2 mt-1 text-xs text-gray-500">
                                        {{ $message->created_at->format('d M Y H:i') }}
                                        @if($message->isFromCurrentUser() && $message->read_at)
                                            <span class="text-indigo-600">· Dibaca</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="flex justify-center items-center h-full text-center">
                        <div>
                            <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pesan</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai percakapan dengan mengirim pesan di bawah</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Message Input Form -->
            <div class="p-4 bg-gray-50 border-t border-gray-200">
                @if(session('success'))
                    <div class="p-3 mb-3 text-sm text-green-700 bg-green-100 rounded-lg border border-green-400">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-3 mb-3 text-sm text-red-700 bg-red-100 rounded-lg border border-red-400">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('messages.store', $conversation) }}" method="POST" class="flex gap-2">
                    @csrf
                    <textarea 
                        name="message" 
                        rows="1"
                        placeholder="Ketik pesan Anda..." 
                        required
                        maxlength="2000"
                        class="flex-1 px-4 py-2 rounded-lg border border-gray-300 resize-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"
                    ></textarea>
                    <button 
                        type="submit" 
                        class="flex gap-2 items-center px-6 py-2 font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                        <span>Kirim</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
                <p class="mt-2 text-xs text-gray-500">Tekan Enter untuk mengirim, Shift+Enter untuk baris baru. Maksimal 2000 karakter.</p>
            </div>
        </div>
    </div>

    <!-- Auto-scroll to bottom on page load -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messages-container');
            container.scrollTop = container.scrollHeight;
        });
    </script>
</x-layouts.dashboard>

