<x-layouts.dashboard>
    <x-slot name="title">Manage Users</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola Pengguna</h1>
                <p class="mt-1 text-sm text-gray-600">Kelola semua pengguna di platform</p>
            </div>
        </div>
    </x-slot>

    {{-- Add New User Button (moved from header) --}}
    <div class="flex justify-end mb-6">
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
            <svg class="inline-block mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add New User
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="px-4 py-3 mb-6 text-green-800 bg-green-50 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name or email..." 
                       class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <select name="role" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="employer" {{ request('role') == 'employer' ? 'selected' : '' }}>Employer</option>
                    <option value="seeker" {{ request('role') == 'seeker' ? 'selected' : '' }}>Seeker</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                Filter
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Users Table --}}
    <div class="overflow-hidden bg-white rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">User</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Role</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Joined</th>
                    <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                 class="object-cover w-10 h-10 rounded-full">
                            <div class="ml-4">
                                @if($user->role === 'employer' && $user->employer)
                                    <a href="{{ route('employers.show', $user->employer->slug) }}" 
                                       class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline"
                                       target="_blank">
                                        {{ $user->name }}
                                        <svg class="inline-block ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @elseif($user->role === 'seeker' && $user->seeker)
                                    <a href="{{ route('seekers.show', $user->seeker) }}" 
                                       class="text-sm font-medium text-indigo-600 hover:text-indigo-900 hover:underline"
                                       target="_blank">
                                        {{ $user->name }}
                                        <svg class="inline-block ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                    </a>
                                @else
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                @endif
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            @if($user->role === 'admin') bg-purple-100 text-purple-800
                            @elseif($user->role === 'employer') bg-blue-100 text-blue-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                        <a href="{{ route('admin.users.edit', $user) }}" 
                           class="mr-3 text-indigo-600 hover:text-indigo-900">
                            Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <button type="button" 
                                class="text-red-600 hover:text-red-900"
                                onclick="openDeleteModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')"
                                data-user-id="{{ $user->id }}">
                            Delete
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" onclick="closeDeleteModal()"></div>

            {{-- Center modal --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Delete User
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete <strong id="modalUserName"></strong> (<span id="modalUserEmail"></span>)?
                                </p>
                                
                                {{-- Loading State --}}
                                <div id="loadingState" class="flex items-center justify-center py-4">
                                    <svg class="w-8 h-8 text-indigo-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-600">Loading related data...</span>
                                </div>

                                {{-- Related Data Info --}}
                                <div id="relatedDataInfo" class="hidden mt-4">
                                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-yellow-800">
                                                    This user has related data that will also be deleted:
                                                </p>
                                                <ul id="relatedDataList" class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                                    {{-- Populated via JavaScript --}}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Confirmation Checkbox --}}
                                    <div class="mt-4">
                                        <label class="flex items-start">
                                            <input type="checkbox" id="confirmDelete" class="mt-1 rounded border-gray-300 text-red-600 focus:ring-red-500">
                                            <span class="ml-2 text-sm text-gray-700">
                                                I understand that this action cannot be undone and all related data will be permanently deleted.
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                {{-- No Related Data --}}
                                <div id="noRelatedData" class="hidden mt-4">
                                    <p class="text-sm text-gray-600">
                                        This user has no related data. The user account will be deleted.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                id="confirmDeleteBtn"
                                disabled
                                class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                            Delete User
                        </button>
                    </form>
                    <button type="button" 
                            onclick="closeDeleteModal()"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentUserId = null;

        function openDeleteModal(userId, userName, userEmail, userRole) {
            currentUserId = userId;
            
            // Set user info
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalUserEmail').textContent = userEmail;
            
            // Show modal
            document.getElementById('deleteModal').classList.remove('hidden');
            
            // Show loading state
            document.getElementById('loadingState').classList.remove('hidden');
            document.getElementById('relatedDataInfo').classList.add('hidden');
            document.getElementById('noRelatedData').classList.add('hidden');
            document.getElementById('confirmDeleteBtn').disabled = true;
            
            // Reset checkbox
            document.getElementById('confirmDelete').checked = false;
            
            // Fetch related data
            fetch(`/admin/users/${userId}/related-data`)
                .then(response => response.json())
                .then(data => {
                    // Hide loading
                    document.getElementById('loadingState').classList.add('hidden');
                    
                    if (data.has_related_data) {
                        // Show related data
                        const list = document.getElementById('relatedDataList');
                        list.innerHTML = '';
                        
                        for (const [type, count] of Object.entries(data.related_data)) {
                            const li = document.createElement('li');
                            const label = type.replace(/_/g, ' ');
                            li.textContent = `${count} ${label}`;
                            list.appendChild(li);
                        }
                        
                        document.getElementById('relatedDataInfo').classList.remove('hidden');
                        
                        // Enable delete button only when checkbox is checked
                        document.getElementById('confirmDelete').addEventListener('change', function() {
                            document.getElementById('confirmDeleteBtn').disabled = !this.checked;
                        });
                    } else {
                        // No related data
                        document.getElementById('noRelatedData').classList.remove('hidden');
                        document.getElementById('confirmDeleteBtn').disabled = false;
                    }
                    
                    // Set form action
                    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
                })
                .catch(error => {
                    console.error('Error fetching related data:', error);
                    document.getElementById('loadingState').classList.add('hidden');
                    alert('Error loading user data. Please try again.');
                    closeDeleteModal();
                });
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            currentUserId = null;
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>

</x-layouts.dashboard>

