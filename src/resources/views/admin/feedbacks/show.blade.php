<x-layouts.dashboard>
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.feedbacks.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Feedback
            </a>
        </div>

        <!-- Feedback Detail -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $feedback->title }}</h1>
                        <div class="mt-2 flex items-center space-x-4">
                            <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded">
                                {{ \App\Models\Feedback::CATEGORIES[$feedback->category] ?? $feedback->category }}
                            </span>
                            <span class="px-2 py-1 text-xs font-medium rounded
                                {{ $feedback->status_color == 'blue' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $feedback->status_color == 'yellow' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $feedback->status_color == 'green' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $feedback->status_color == 'gray' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ $feedback->status_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="px-6 py-4 bg-blue-50 border-b border-blue-100">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ substr($feedback->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">{{ $feedback->user->name }}</p>
                        <p class="text-sm text-gray-600">{{ $feedback->user->email }} · Dikirim {{ $feedback->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="px-6 py-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Deskripsi</h3>
                <div class="prose max-w-none text-gray-600 whitespace-pre-line">{{ $feedback->description }}</div>
            </div>

            <!-- Screenshot -->
            @if($feedback->screenshot)
            <div class="px-6 py-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Screenshot</h3>
                <a href="{{ asset('storage/' . $feedback->screenshot) }}" target="_blank">
                    <img src="{{ asset('storage/' . $feedback->screenshot) }}" 
                         alt="Screenshot" 
                         class="max-w-full rounded-lg shadow-sm hover:shadow-md transition cursor-pointer">
                </a>
            </div>
            @endif

            <!-- Admin Notes -->
            @if($feedback->admin_notes)
            <div class="px-6 py-6 border-t border-gray-200 bg-yellow-50">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Catatan Admin</h3>
                <div class="prose max-w-none text-gray-600 whitespace-pre-line">{{ $feedback->admin_notes }}</div>
            </div>
            @endif

            <!-- Update Status Form -->
            <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Update Status</h3>
                <form action="{{ route('admin.feedbacks.update-status', $feedback) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status" class="w-full rounded-md border-gray-300" required>
                                <option value="new" {{ $feedback->status == 'new' ? 'selected' : '' }}>Baru</option>
                                <option value="in_progress" {{ $feedback->status == 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                                <option value="resolved" {{ $feedback->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                <option value="closed" {{ $feedback->status == 'closed' ? 'selected' : '' }}>Ditutup</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Admin</label>
                        <textarea name="admin_notes" 
                                  id="admin_notes" 
                                  rows="4" 
                                  placeholder="Catatan internal tentang feedback ini..."
                                  class="w-full rounded-md border-gray-300">{{ old('admin_notes', $feedback->admin_notes) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dashboard>
