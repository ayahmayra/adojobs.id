<x-layouts.dashboard>
    <x-slot name="title">Lowongan Saya</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lowongan Saya</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola semua lowongan pekerjaan Anda</p>
            </div>
            <a href="{{ route('employer.jobs.create') }}" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                + Pasang Lowongan Baru
            </a>
        </div>
    </x-slot>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">
        <div class="p-4 bg-white rounded-lg shadow-sm">
            <div class="mb-1 text-sm text-gray-600">Total Lowongan</div>
            <div class="text-2xl font-bold text-gray-900">{{ $jobs->total() }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-sm">
            <div class="mb-1 text-sm text-gray-600">Dipublikasi</div>
            <div class="text-2xl font-bold text-green-600">{{ $jobs->where('status', 'published')->count() }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-sm">
            <div class="mb-1 text-sm text-gray-600">Draft</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $jobs->where('status', 'draft')->count() }}</div>
        </div>
        <div class="p-4 bg-white rounded-lg shadow-sm">
            <div class="mb-1 text-sm text-gray-600">Ditutup</div>
            <div class="text-2xl font-bold text-gray-600">{{ $jobs->where('status', 'closed')->count() }}</div>
        </div>
    </div>

    {{-- Jobs List --}}
    @if($jobs->count() > 0)
        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Judul Lowongan</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Tipe</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Lamaran</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Diposting</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($jobs as $job)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-indigo-600" target="_blank">
                                            {{ $job->title }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $job->city }}</div>
                                </div>
                                @if($job->is_featured)
                                <span class="inline-flex items-center px-2 py-0.5 ml-2 text-xs font-medium text-yellow-800 bg-yellow-100 rounded">
                                    ⭐ Unggulan
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $job->category->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($job->work_mode) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'published' => 'bg-green-100 text-green-800',
                                    'closed' => 'bg-red-100 text-red-800',
                                    'filled' => 'bg-blue-100 text-blue-800',
                                ];
                                $statusColor = $statusColors[$job->status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColor }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('employer.applications.index', ['job_id' => $job->id]) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                {{ $job->applications_count ?? 0 }} pelamar
                            </a>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $job->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                            <div class="flex justify-end items-center space-x-2">
                                <a href="{{ route('employer.jobs.show', $job) }}" 
                                   class="text-indigo-600 hover:text-indigo-900"
                                   title="Manage Job">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('jobs.show', $job->slug) }}" 
                                   class="text-gray-600 hover:text-gray-900"
                                   title="View Public Page"
                                   target="_blank">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                <a href="{{ route('employer.jobs.edit', $job) }}" 
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('employer.jobs.destroy', $job) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this job?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="p-12 text-center bg-white rounded-lg shadow-sm">
            <svg class="mx-auto mb-4 w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="mb-2 text-lg font-semibold text-gray-900">No Job Postings Yet</h3>
            <p class="mb-6 text-gray-600">
                Start hiring by posting your first job opening!
            </p>
            <a href="{{ route('employer.jobs.create') }}" class="inline-block px-6 py-3 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                + Post Your First Job
            </a>
        </div>
    @endif

</x-layouts.dashboard>

