<section>
    <header>
        <p class="text-sm text-gray-600">
            {{ __("Update your seeker profile information to help employers find you.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.seeker.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            {{-- Phone --}}
            <div>
                <x-input-label for="phone" value="Phone Number" />
                <x-text-input id="phone" name="phone" type="text" class="block mt-1 w-full" 
                              :value="old('phone', $user->seeker->phone ?? '')" 
                              placeholder="+62 812 3456 7890" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            {{-- Date of Birth --}}
            <div>
                <x-input-label for="date_of_birth" value="Date of Birth" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="block mt-1 w-full" 
                              :value="old('date_of_birth', $user->seeker->date_of_birth?->format('Y-m-d') ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>

            {{-- Gender --}}
            <div>
                <x-input-label for="gender" value="Gender" />
                <select id="gender" name="gender" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Gender</option>
                    <option value="male" {{ old('gender', $user->seeker->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('gender', $user->seeker->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ old('gender', $user->seeker->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            {{-- Current Job Title --}}
            <div>
                <x-input-label for="current_job_title" value="Current Job Title" />
                <x-text-input id="current_job_title" name="current_job_title" type="text" class="block mt-1 w-full" 
                              :value="old('current_job_title', $user->seeker->current_job_title ?? '')" 
                              placeholder="e.g., Senior Software Engineer" />
                <x-input-error class="mt-2" :messages="$errors->get('current_job_title')" />
            </div>

            {{-- City --}}
            <div>
                <x-input-label for="city" value="City" />
                <x-text-input id="city" name="city" type="text" class="block mt-1 w-full" 
                              :value="old('city', $user->seeker->city ?? '')" 
                              placeholder="e.g., Jakarta" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            {{-- Country --}}
            <div>
                <x-input-label for="country" value="Country" />
                <x-text-input id="country" name="country" type="text" class="block mt-1 w-full" 
                              :value="old('country', $user->seeker->country ?? 'Indonesia')" 
                              placeholder="Indonesia" />
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>

            {{-- Job Type Preference --}}
            <div>
                <x-input-label for="job_type_preference" value="Job Type Preference" />
                <select id="job_type_preference" name="job_type_preference" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Preference</option>
                    <option value="full-time" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="freelance" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'freelance' ? 'selected' : '' }}>Freelance</option>
                    <option value="internship" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('job_type_preference')" />
            </div>

            {{-- Preferred Location --}}
            <div>
                <x-input-label for="preferred_location" value="Preferred Work Location" />
                <x-text-input id="preferred_location" name="preferred_location" type="text" class="block mt-1 w-full" 
                              :value="old('preferred_location', $user->seeker->preferred_location ?? '')" 
                              placeholder="e.g., Jakarta or Remote" />
                <x-input-error class="mt-2" :messages="$errors->get('preferred_location')" />
            </div>
        </div>

        {{-- Bio --}}
        <div class="mt-6">
            <x-input-label for="bio" value="Bio / About Me" />
            <textarea id="bio" name="bio" rows="4" 
                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Tell employers about yourself, your experience, and what you're looking for...">{{ old('bio', $user->seeker->bio ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        {{-- Expected Salary --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="expected_salary_min" value="Expected Salary (Min)" />
                <x-text-input id="expected_salary_min" name="expected_salary_min" type="number" class="block mt-1 w-full" 
                              :value="old('expected_salary_min', $user->seeker->expected_salary_min ?? '')" 
                              placeholder="5000000" />
                <x-input-error class="mt-2" :messages="$errors->get('expected_salary_min')" />
            </div>

            <div>
                <x-input-label for="expected_salary_max" value="Expected Salary (Max)" />
                <x-text-input id="expected_salary_max" name="expected_salary_max" type="number" class="block mt-1 w-full" 
                              :value="old('expected_salary_max', $user->seeker->expected_salary_max ?? '')" 
                              placeholder="10000000" />
                <x-input-error class="mt-2" :messages="$errors->get('expected_salary_max')" />
            </div>
        </div>

        {{-- Social Links --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="linkedin_url" value="LinkedIn URL" />
                <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="block mt-1 w-full" 
                              :value="old('linkedin_url', $user->seeker->linkedin_url ?? '')" 
                              placeholder="https://linkedin.com/in/yourname" />
                <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
            </div>

            <div>
                <x-input-label for="github_url" value="GitHub URL" />
                <x-text-input id="github_url" name="github_url" type="url" class="block mt-1 w-full" 
                              :value="old('github_url', $user->seeker->github_url ?? '')" 
                              placeholder="https://github.com/yourname" />
                <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="portfolio_url" value="Portfolio URL" />
                <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="block mt-1 w-full" 
                              :value="old('portfolio_url', $user->seeker->portfolio_url ?? '')" 
                              placeholder="https://yourportfolio.com" />
                <x-input-error class="mt-2" :messages="$errors->get('portfolio_url')" />
            </div>
        </div>

        {{-- Skills (Simple text input for now) --}}
        <div>
            <x-input-label for="skills" value="Skills (comma-separated)" />
            @php
                $seekerSkills = $user->seeker->skills ?? [];
                $skillsValue = is_array($seekerSkills) ? implode(', ', $seekerSkills) : '';
            @endphp
            <x-text-input id="skills" name="skills" type="text" class="block mt-1 w-full" 
                          :value="old('skills', $skillsValue)" 
                          placeholder="PHP, Laravel, JavaScript, React, etc." />
            <p class="mt-1 text-xs text-gray-500">Separate skills with commas</p>
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
        </div>

        {{-- Work Experience --}}
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Work Experience</h3>
                    <p class="text-sm text-gray-600">Add your work experience history</p>
                </div>
                <button type="button" onclick="addExperience()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Experience
                </button>
            </div>

            <div id="experienceContainer" class="space-y-4">
                @php
                    $experiences = old('experience', $user->seeker->experience ?? []);
                    if (empty($experiences)) {
                        $experiences = [['job_title' => '', 'company_name' => '', 'start_date' => '', 'end_date' => '', 'is_current' => false, 'description' => '']];
                    }
                @endphp

                @foreach($experiences as $index => $exp)
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 experience-item">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-900">Experience #{{ $index + 1 }}</h4>
                            @if($index > 0 || count($experiences) > 1)
                                <button type="button" onclick="removeExperience(this)" 
                                        class="text-sm text-red-600 hover:text-red-800">
                                    Remove
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Job Title</label>
                                <input type="text" name="experience[{{ $index }}][job_title]" 
                                       value="{{ $exp['job_title'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Software Engineer">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                <input type="text" name="experience[{{ $index }}][company_name]" 
                                       value="{{ $exp['company_name'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., ABC Corp">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="month" name="experience[{{ $index }}][start_date]" 
                                       value="{{ $exp['start_date'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="month" name="experience[{{ $index }}][end_date]" 
                                       value="{{ $exp['end_date'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       {{ !empty($exp['is_current']) ? 'disabled' : '' }}>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="experience[{{ $index }}][is_current]" value="1"
                                           {{ !empty($exp['is_current']) ? 'checked' : '' }}
                                           onchange="toggleEndDate(this)"
                                           class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">I currently work here</span>
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="experience[{{ $index }}][description]" rows="3"
                                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Describe your responsibilities and achievements...">{{ $exp['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Education --}}
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Education</h3>
                    <p class="text-sm text-gray-600">Add your education history</p>
                </div>
                <button type="button" onclick="addEducation()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Education
                </button>
            </div>

            <div id="educationContainer" class="space-y-4">
                @php
                    $educations = old('education', $user->seeker->education ?? []);
                    if (empty($educations)) {
                        $educations = [['degree' => '', 'institution' => '', 'field_of_study' => '', 'start_date' => '', 'end_date' => '', 'is_current' => false, 'description' => '']];
                    }
                @endphp

                @foreach($educations as $index => $edu)
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 education-item">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="font-medium text-gray-900">Education #{{ $index + 1 }}</h4>
                            @if($index > 0 || count($educations) > 1)
                                <button type="button" onclick="removeEducation(this)" 
                                        class="text-sm text-red-600 hover:text-red-800">
                                    Remove
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Degree</label>
                                <input type="text" name="education[{{ $index }}][degree]" 
                                       value="{{ $edu['degree'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Bachelor of Computer Science">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Institution</label>
                                <input type="text" name="education[{{ $index }}][institution]" 
                                       value="{{ $edu['institution'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., University of Indonesia">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Field of Study</label>
                                <input type="text" name="education[{{ $index }}][field_of_study]" 
                                       value="{{ $edu['field_of_study'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="e.g., Software Engineering">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Year</label>
                                <input type="month" name="education[{{ $index }}][start_date]" 
                                       value="{{ $edu['start_date'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Year</label>
                                <input type="month" name="education[{{ $index }}][end_date]" 
                                       value="{{ $edu['end_date'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       {{ !empty($edu['is_current']) ? 'disabled' : '' }}>
                            </div>

                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="education[{{ $index }}][is_current]" value="1"
                                           {{ !empty($edu['is_current']) ? 'checked' : '' }}
                                           onchange="toggleEducationEndDate(this)"
                                           class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">I currently study here</span>
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                                <textarea name="education[{{ $index }}][description]" rows="2"
                                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Achievements, relevant coursework, etc...">{{ $edu['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-4 items-center">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('seeker-updated'))
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

    <script>
        let expIndex = {{ count($experiences) }};
        let eduIndex = {{ count($educations) }};

        function addExperience() {
            const container = document.getElementById('experienceContainer');
            const template = `
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 experience-item">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-900">Experience #${expIndex + 1}</h4>
                        <button type="button" onclick="removeExperience(this)" 
                                class="text-sm text-red-600 hover:text-red-800">
                            Remove
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Job Title</label>
                            <input type="text" name="experience[${expIndex}][job_title]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., Software Engineer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Company Name</label>
                            <input type="text" name="experience[${expIndex}][company_name]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., ABC Corp">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="month" name="experience[${expIndex}][start_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="month" name="experience[${expIndex}][end_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm end-date focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="experience[${expIndex}][is_current]" value="1"
                                       onchange="toggleEndDate(this)"
                                       class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">I currently work here</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="experience[${expIndex}][description]" rows="3"
                                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Describe your responsibilities and achievements..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            expIndex++;
        }

        function removeExperience(button) {
            if (confirm('Are you sure you want to remove this experience?')) {
                button.closest('.experience-item').remove();
            }
        }

        function toggleEndDate(checkbox) {
            const endDateInput = checkbox.closest('.experience-item').querySelector('.end-date');
            if (checkbox.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
            } else {
                endDateInput.disabled = false;
            }
        }

        function addEducation() {
            const container = document.getElementById('educationContainer');
            const template = `
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 education-item">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium text-gray-900">Education #${eduIndex + 1}</h4>
                        <button type="button" onclick="removeEducation(this)" 
                                class="text-sm text-red-600 hover:text-red-800">
                            Remove
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Degree</label>
                            <input type="text" name="education[${eduIndex}][degree]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., Bachelor of Computer Science">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Institution</label>
                            <input type="text" name="education[${eduIndex}][institution]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., University of Indonesia">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Field of Study</label>
                            <input type="text" name="education[${eduIndex}][field_of_study]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="e.g., Software Engineering">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Year</label>
                            <input type="month" name="education[${eduIndex}][start_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">End Year</label>
                            <input type="month" name="education[${eduIndex}][end_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm edu-end-date focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="education[${eduIndex}][is_current]" value="1"
                                       onchange="toggleEducationEndDate(this)"
                                       class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">I currently study here</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <textarea name="education[${eduIndex}][description]" rows="2"
                                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Achievements, relevant coursework, etc..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            eduIndex++;
        }

        function removeEducation(button) {
            if (confirm('Are you sure you want to remove this education?')) {
                button.closest('.education-item').remove();
            }
        }

        function toggleEducationEndDate(checkbox) {
            const endDateInput = checkbox.closest('.education-item').querySelector('.edu-end-date');
            if (checkbox.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
            } else {
                endDateInput.disabled = false;
            }
        }
    </script>
</section>

