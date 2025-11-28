<x-layouts.dashboard>
    <x-slot name="title">Create New User</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.admin />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Buat Pengguna Baru</h1>
                <p class="mt-1 text-sm text-gray-600">Tambahkan pengguna baru ke platform</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Avatar Upload --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Profile Photo
                    </label>
                    <div class="flex items-center space-x-6">
                        {{-- Preview --}}
                        <div class="flex-shrink-0">
                            <img id="avatarPreview" 
                                 src="https://ui-avatars.com/api/?name=New+User&color=4F46E5&background=EEF2FF&bold=true&size=128" 
                                 alt="Avatar Preview" 
                                 class="h-24 w-24 rounded-full object-cover border-2 border-gray-200">
                        </div>
                        {{-- Upload Button --}}
                        <div class="flex-1">
                            <input type="file" name="avatar" id="avatar" accept="image/*" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                   onchange="previewAvatar(event)">
                            <p class="mt-2 text-sm text-gray-500">
                                JPG, PNG or GIF. Max size 2MB.
                            </p>
                            @error('avatar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name *
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address *
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password *
                    </label>
                    <input type="password" name="password" id="password" required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password *
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Role --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role *
                    </label>
                    <select name="role" id="role" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('role') border-red-500 @enderror">
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="employer" {{ old('role') == 'employer' ? 'selected' : '' }}>Employer</option>
                        <option value="seeker" {{ old('role') == 'seeker' ? 'selected' : '' }}>Job Seeker</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Account Status
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" checked
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Create User
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function previewAvatar(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
    @endpush

</x-layouts.dashboard>

