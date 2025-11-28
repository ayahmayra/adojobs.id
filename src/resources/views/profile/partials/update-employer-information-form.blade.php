<section>
    <header>
        <p class="text-sm text-gray-600">
            {{ __("Update your company information to attract quality candidates.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.employer.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Company Name --}}
            <div class="md:col-span-2">
                <x-input-label for="company_name" value="Company Name" />
                <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" 
                              :value="old('company_name', $user->employer->company_name ?? '')" 
                              required
                              placeholder="e.g., PT Tech Indonesia" />
                <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
            </div>

            {{-- Industry --}}
            <div>
                <x-input-label for="industry" value="Industry" />
                <x-text-input id="industry" name="industry" type="text" class="mt-1 block w-full" 
                              :value="old('industry', $user->employer->industry ?? '')" 
                              placeholder="e.g., Information Technology" />
                <x-input-error class="mt-2" :messages="$errors->get('industry')" />
            </div>

            {{-- Company Size --}}
            <div>
                <x-input-label for="company_size" value="Company Size" />
                <select id="company_size" name="company_size" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">Select Size</option>
                    <option value="1-10" {{ old('company_size', $user->employer->company_size ?? '') === '1-10' ? 'selected' : '' }}>1-10 employees</option>
                    <option value="11-50" {{ old('company_size', $user->employer->company_size ?? '') === '11-50' ? 'selected' : '' }}>11-50 employees</option>
                    <option value="51-200" {{ old('company_size', $user->employer->company_size ?? '') === '51-200' ? 'selected' : '' }}>51-200 employees</option>
                    <option value="201-500" {{ old('company_size', $user->employer->company_size ?? '') === '201-500' ? 'selected' : '' }}>201-500 employees</option>
                    <option value="501-1000" {{ old('company_size', $user->employer->company_size ?? '') === '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                    <option value="1000+" {{ old('company_size', $user->employer->company_size ?? '') === '1000+' ? 'selected' : '' }}>1000+ employees</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('company_size')" />
            </div>

            {{-- Phone --}}
            <div>
                <x-input-label for="phone" value="Company Phone" />
                <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" 
                              :value="old('phone', $user->employer->phone ?? '')" 
                              placeholder="+62 21 1234 5678" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            {{-- Website --}}
            <div>
                <x-input-label for="company_website" value="Company Website" />
                <x-text-input id="company_website" name="company_website" type="url" class="mt-1 block w-full" 
                              :value="old('company_website', $user->employer->company_website ?? '')" 
                              placeholder="https://yourcompany.com" />
                <x-input-error class="mt-2" :messages="$errors->get('company_website')" />
            </div>

            {{-- City --}}
            <div>
                <x-input-label for="city" value="City" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" 
                              :value="old('city', $user->employer->city ?? '')" 
                              placeholder="e.g., Jakarta" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            {{-- Country --}}
            <div>
                <x-input-label for="country" value="Country" />
                <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" 
                              :value="old('country', $user->employer->country ?? 'Indonesia')" 
                              placeholder="Indonesia" />
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>
        </div>

        {{-- Address --}}
        <div>
            <x-input-label for="address" value="Company Address" />
            <textarea id="address" name="address" rows="2" 
                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                      placeholder="Full company address...">{{ old('address', $user->employer->address ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        {{-- Description --}}
        <div>
            <x-input-label for="description" value="About Company" />
            <textarea id="description" name="description" rows="4" 
                      class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                      placeholder="Tell candidates about your company, culture, and what makes it a great place to work...">{{ old('description', $user->employer->description ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('employer-updated'))
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
</section>

