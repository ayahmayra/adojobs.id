<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Avatar Upload --}}
        <div>
            <x-input-label for="avatar" :value="__('Profile Photo')" />
            <div class="mt-2 flex items-center space-x-6">
                {{-- Current Avatar Preview --}}
                <div class="flex-shrink-0">
                    <img id="avatarPreview" 
                         src="{{ $user->avatar_url }}" 
                         alt="{{ $user->name }}" 
                         class="h-20 w-20 rounded-full object-cover border-2 border-gray-200">
                </div>
                {{-- Upload Button --}}
                <div class="flex-1">
                    <input type="file" 
                           name="avatar" 
                           id="avatar" 
                           accept="image/*" 
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                           onchange="previewAvatar(event)">
                    <p class="mt-1 text-sm text-gray-500">
                        JPG, PNG or GIF. Max size 2MB.
                    </p>
                    <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                    @if($user->avatar)
                    <div class="mt-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Remove current photo</span>
                        </label>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Resume URL Slug (for Seekers) --}}
        @if($user->isSeeker())
            <div>
                <x-input-label for="resume_slug" value="Public Resume URL" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        {{ url('/resume') }}/
                    </span>
                    <x-text-input id="resume_slug" 
                                  name="resume_slug" 
                                  type="text" 
                                  class="flex-1 rounded-none rounded-r-md" 
                                  :value="old('resume_slug', $user->resume_slug)" 
                                  placeholder="your-name" 
                                  pattern="[a-z0-9\-]+"
                                  title="Only lowercase letters, numbers, and hyphens allowed" />
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    This will be your public resume URL. Only lowercase letters, numbers, and hyphens allowed.
                    @if($user->resume_slug)
                        <br>
                        <a href="{{ route('resume.show', $user->resume_slug) }}" 
                           target="_blank" 
                           class="text-indigo-600 hover:text-indigo-800">
                            View your public resume →
                        </a>
                    @endif
                </p>
                <x-input-error class="mt-2" :messages="$errors->get('resume_slug')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

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
</section>
