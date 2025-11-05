<x-layouts.dashboard>
    <x-slot name="title">Kelola Lowongan Pekerjaan</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Lowongan Pekerjaan</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola semua lowongan pekerjaan di platform</p>
            </div>
        </div>
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="px-4 py-3 mb-6 text-green-800 bg-green-50 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.jobs.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari lowongan berdasarkan judul..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="status" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>Filled</option>
                </select>
            </div>
            <div>
                <select name="featured" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Lowongan</option>
                    <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                Filter
            </button>
            @if(request('search') || request('status') || request('featured'))
                <a href="{{ route('admin.jobs.index') }}" class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Hapus Filter
                </a>
            @endif
        </form>
    </div>

    {{-- Jobs Table --}}
    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Job Details</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Employer</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Applications</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Posted</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-start">
                            <div>
                                <div class="text-sm font-medium text-gray-900">
                                    <a href="{{ route('admin.jobs.show', $job) }}" class="hover:text-indigo-600">
                                        {{ $job->title }}
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $job->city }} • {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                </div>
                                @if($job->is_featured)
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                    ⭐ Featured
                                </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $job->employer->company_name }}</div>
                        <div class="text-sm text-gray-500">{{ $job->employer->user->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold leading-5 text-blue-800 bg-blue-100 rounded-full">
                            {{ $job->category->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $job->status === 'published' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $job->status === 'draft' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $job->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($job->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                        {{ $job->applications_count ?? 0 }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $job->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <div class="flex justify-end items-center space-x-2">
                            {{-- View Button --}}
                            <a href="{{ route('admin.jobs.show', $job) }}" 
                               class="text-indigo-600 hover:text-indigo-900"
                               title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>

                            {{-- Toggle Featured --}}
                            <form action="{{ route('admin.jobs.toggle-featured', $job) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="{{ $job->is_featured ? 'text-yellow-600 hover:text-yellow-900' : 'text-gray-400 hover:text-gray-600' }}"
                                        title="{{ $job->is_featured ? 'Hapus dari Featured' : 'Jadikan Featured' }}">
                                    <svg class="w-5 h-5" fill="{{ $job->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </button>
                            </form>

                            {{-- Status Dropdown --}}
                            <div class="inline-block relative text-left">
                                <button type="button" 
                                        onclick="toggleDropdown('status-dropdown-{{ $job->id }}')"
                                        class="text-gray-600 hover:text-gray-900"
                                        title="Ubah Status">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                <div id="status-dropdown-{{ $job->id }}" 
                                     class="hidden absolute right-0 z-10 mt-2 w-48 bg-white rounded-md ring-1 ring-black ring-opacity-5 shadow-lg origin-top-right">
                                    <div class="py-1">
                                        <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Ubah Status</div>
                                        @foreach(['draft' => 'Draft', 'published' => 'Published', 'closed' => 'Closed', 'filled' => 'Filled'] as $statusValue => $statusLabel)
                                            @if($job->status !== $statusValue)
                                            <form action="{{ route('admin.jobs.update-status', $job) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $statusValue }}">
                                                <button type="submit" class="block px-4 py-2 w-full text-sm text-left text-gray-700 hover:bg-gray-100">
                                                    {{ $statusLabel }}
                                                </button>
                                            </form>
                                            @endif
                                        @endforeach
                                        <div class="border-t border-gray-100"></div>
                                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block px-4 py-2 w-full text-sm text-left text-red-600 hover:bg-red-50">
                                                Hapus Lowongan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Delete Button --}}
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Hapus"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?')">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        No jobs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $jobs->links() }}
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const allDropdowns = document.querySelectorAll('[id^="status-dropdown-"]');
            
            // Close all other dropdowns
            allDropdowns.forEach(d => {
                if (d.id !== dropdownId) {
                    d.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const isDropdownButton = event.target.closest('button[onclick^="toggleDropdown"]');
            const isDropdownContent = event.target.closest('[id^="status-dropdown-"]');
            
            if (!isDropdownButton && !isDropdownContent) {
                const allDropdowns = document.querySelectorAll('[id^="status-dropdown-"]');
                allDropdowns.forEach(d => d.classList.add('hidden'));
            }
        });
    </script>
    @endpush

</x-layouts.dashboard>

