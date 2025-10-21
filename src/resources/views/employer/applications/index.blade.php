<x-layouts.dashboard>
    <x-slot name="title">Semua Lamaran</x-slot>
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Lamaran Masuk</h1>
            <p class="mt-1 text-sm text-gray-600">Tinjau dan kelola lamaran pekerjaan</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <form method="GET" action="{{ route('employer.applications.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
            {{-- Status Filter --}}
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Ditinjau</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Diseleksi</option>
                    <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="offered" {{ request('status') === 'offered' ? 'selected' : '' }}>Ditawari</option>
                    <option value="hired" {{ request('status') === 'hired' ? 'selected' : '' }}>Dipekerjakan</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Job Filter --}}
            <div>
                <label for="job_id" class="block mb-2 text-sm font-medium text-gray-700">Posisi Pekerjaan</label>
                <select name="job_id" id="job_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Semua Lowongan</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                            {{ $job->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Search & Filter Button --}}
            <div class="flex gap-2 items-end">
                <button type="submit" 
                        class="flex-1 px-4 py-2 text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    Terapkan Filter
                </button>
                @if(request()->hasAny(['status', 'job_id']))
                    <a href="{{ route('employer.applications.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md transition hover:bg-gray-300">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-4">
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-blue-100 rounded-md">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $applications->total() }}</p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-yellow-100 rounded-md">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Menunggu</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Application::whereIn('job_id', auth()->user()->employer->jobs->pluck('id'))->pending()->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-purple-100 rounded-md">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Diseleksi</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Application::whereIn('job_id', auth()->user()->employer->jobs->pluck('id'))->shortlisted()->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0 p-3 bg-green-100 rounded-md">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Dipekerjakan</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ \App\Models\Application::whereIn('job_id', auth()->user()->employer->jobs->pluck('id'))->hired()->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Applications List --}}
    @if($applications->count() > 0)
        <div class="overflow-hidden bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Kandidat
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Posisi Pekerjaan
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Tanggal Melamar
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Status
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($applications as $application)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <img class="object-cover w-10 h-10 rounded-full" 
                                             src="{{ $application->seeker->user->avatar_url }}" 
                                             alt="{{ $application->seeker->user->name }}">
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $application->seeker->user->name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $application->seeker->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->job->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->job->category->name }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                    {{ $application->created_at->format('d M Y') }}
                                    <br>
                                    <span class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'shortlisted' => 'bg-purple-100 text-purple-800',
                                            'interview' => 'bg-indigo-100 text-indigo-800',
                                            'offered' => 'bg-green-100 text-green-800',
                                            'hired' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        @if($application->status === 'pending')
                                            Menunggu
                                        @elseif($application->status === 'reviewed')
                                            Ditinjau
                                        @elseif($application->status === 'shortlisted')
                                            Diseleksi
                                        @elseif($application->status === 'interview')
                                            Interview
                                        @elseif($application->status === 'offered')
                                            Ditawari
                                        @elseif($application->status === 'hired')
                                            Dipekerjakan
                                        @elseif($application->status === 'rejected')
                                            Ditolak
                                        @else
                                            {{ ucfirst($application->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <a href="{{ route('employer.applications.show', $application) }}" 
                                       class="text-indigo-600 transition hover:text-indigo-900">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 bg-white border-t border-gray-200">
                {{ $applications->links() }}
            </div>
        </div>
    @else
        <div class="p-12 text-center bg-white rounded-lg shadow-sm">
            <svg class="mx-auto w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada lamaran ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request()->hasAny(['status', 'job_id']))
                    Tidak ada lamaran yang cocok dengan filter Anda. Coba sesuaikan kriteria pencarian.
                @else
                    Anda belum menerima lamaran apapun.
                @endif
            </p>
            @if(request()->hasAny(['status', 'job_id']))
                <div class="mt-6">
                    <a href="{{ route('employer.applications.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md border border-transparent shadow-sm hover:bg-indigo-700">
                        Hapus Filter
                    </a>
                </div>
            @endif
        </div>
    @endif
</x-layouts.dashboard>
