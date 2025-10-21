<x-layouts.dashboard>
    <x-slot name="title">Edit Job - {{ $job->title }}</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <span>Edit Job</span>
            <a href="{{ route('employer.jobs.show', $job) }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Job Details
            </a>
        </div>
    </x-slot>

    <form action="{{ route('employer.jobs.update', $job) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Basic Information --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Job Title --}}
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Job Title *
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $job->title) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                           placeholder="e.g. Senior Full Stack Developer">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Category *
                    </label>
                    <select name="category_id" 
                            id="category_id" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $job->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Vacancies --}}
                <div>
                    <label for="vacancies" class="block text-sm font-medium text-gray-700 mb-2">
                        Number of Vacancies *
                    </label>
                    <input type="number" 
                           name="vacancies" 
                           id="vacancies" 
                           value="{{ old('vacancies', $job->vacancies) }}"
                           min="1"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('vacancies') border-red-500 @enderror">
                    @error('vacancies')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Job Details --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Job Type --}}
                <div>
                    <label for="job_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Job Type *
                    </label>
                    <select name="job_type" 
                            id="job_type" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('job_type') border-red-500 @enderror">
                        <option value="">Select Type</option>
                        <option value="full-time" {{ old('job_type', $job->job_type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                        <option value="part-time" {{ old('job_type', $job->job_type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                        <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                        <option value="freelance" {{ old('job_type', $job->job_type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="internship" {{ old('job_type', $job->job_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                    </select>
                    @error('job_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Work Mode --}}
                <div>
                    <label for="work_mode" class="block text-sm font-medium text-gray-700 mb-2">
                        Work Mode *
                    </label>
                    <select name="work_mode" 
                            id="work_mode" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('work_mode') border-red-500 @enderror">
                        <option value="">Select Mode</option>
                        <option value="on-site" {{ old('work_mode', $job->work_mode) == 'on-site' ? 'selected' : '' }}>On-site</option>
                        <option value="remote" {{ old('work_mode', $job->work_mode) == 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="hybrid" {{ old('work_mode', $job->work_mode) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    @error('work_mode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location
                    </label>
                    <input type="text" 
                           name="location" 
                           id="location" 
                           value="{{ old('location', $job->location) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. Sudirman, Jakarta Pusat">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        City
                    </label>
                    <input type="text" 
                           name="city" 
                           id="city" 
                           value="{{ old('city', $job->city) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. Jakarta">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Application Deadline --}}
                <div>
                    <label for="application_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                        Application Deadline *
                    </label>
                    <input type="date" 
                           name="application_deadline" 
                           id="application_deadline" 
                           value="{{ old('application_deadline', $job->application_deadline?->format('Y-m-d')) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('application_deadline') border-red-500 @enderror">
                    @error('application_deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Salary Information --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Salary Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Salary Min --}}
                <div>
                    <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Salary (IDR)
                    </label>
                    <input type="number" 
                           name="salary_min" 
                           id="salary_min" 
                           value="{{ old('salary_min', $job->salary_min) }}"
                           min="0"
                           step="100000"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. 5000000">
                    @error('salary_min')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Salary Max --}}
                <div>
                    <label for="salary_max" class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Salary (IDR)
                    </label>
                    <input type="number" 
                           name="salary_max" 
                           id="salary_max" 
                           value="{{ old('salary_max', $job->salary_max) }}"
                           min="0"
                           step="100000"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. 10000000">
                    @error('salary_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Salary Period --}}
                <div>
                    <label for="salary_period" class="block text-sm font-medium text-gray-700 mb-2">
                        Salary Period *
                    </label>
                    <select name="salary_period" 
                            id="salary_period" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('salary_period') border-red-500 @enderror">
                        <option value="monthly" {{ old('salary_period', $job->salary_period) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="yearly" {{ old('salary_period', $job->salary_period) == 'yearly' ? 'selected' : '' }}>Yearly</option>
                        <option value="hourly" {{ old('salary_period', $job->salary_period) == 'hourly' ? 'selected' : '' }}>Hourly</option>
                        <option value="daily" {{ old('salary_period', $job->salary_period) == 'daily' ? 'selected' : '' }}>Daily</option>
                    </select>
                    @error('salary_period')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Salary Negotiable --}}
                <div class="md:col-span-3">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="salary_negotiable" 
                               value="1"
                               {{ old('salary_negotiable', $job->salary_negotiable) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Salary is negotiable</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Requirements & Experience --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Requirements & Experience</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Experience Level --}}
                <div>
                    <label for="experience_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Experience Level
                    </label>
                    <input type="text" 
                           name="experience_level" 
                           id="experience_level" 
                           value="{{ old('experience_level', $job->experience_level) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. Entry Level, Mid Level, Senior">
                    @error('experience_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Education Level --}}
                <div>
                    <label for="education_level" class="block text-sm font-medium text-gray-700 mb-2">
                        Education Level
                    </label>
                    <input type="text" 
                           name="education_level" 
                           id="education_level" 
                           value="{{ old('education_level', $job->education_level) }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. Bachelor's Degree">
                    @error('education_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Experience Years Min --}}
                <div>
                    <label for="experience_years_min" class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Experience (Years)
                    </label>
                    <input type="number" 
                           name="experience_years_min" 
                           id="experience_years_min" 
                           value="{{ old('experience_years_min', $job->experience_years_min) }}"
                           min="0"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. 2">
                    @error('experience_years_min')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Experience Years Max --}}
                <div>
                    <label for="experience_years_max" class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Experience (Years)
                    </label>
                    <input type="number" 
                           name="experience_years_max" 
                           id="experience_years_max" 
                           value="{{ old('experience_years_max', $job->experience_years_max) }}"
                           min="0"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="e.g. 5">
                    @error('experience_years_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Description & Details --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Description</h3>
            
            <div class="space-y-6">
                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Job Description *
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="6"
                              required
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                              placeholder="Describe the job role, what the candidate will do...">{{ old('description', $job->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Requirements --}}
                <div>
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">
                        Requirements
                    </label>
                    <textarea name="requirements" 
                              id="requirements" 
                              rows="6"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="List the requirements for this position...">{{ old('requirements', $job->requirements) }}</textarea>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Responsibilities --}}
                <div>
                    <label for="responsibilities" class="block text-sm font-medium text-gray-700 mb-2">
                        Responsibilities
                    </label>
                    <textarea name="responsibilities" 
                              id="responsibilities" 
                              rows="6"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="List the key responsibilities...">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                    @error('responsibilities')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Benefits --}}
                <div>
                    <label for="benefits" class="block text-sm font-medium text-gray-700 mb-2">
                        Benefits
                    </label>
                    <textarea name="benefits" 
                              id="benefits" 
                              rows="4"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="List the benefits and perks...">{{ old('benefits', $job->benefits) }}</textarea>
                    @error('benefits')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Publish Settings --}}
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Publish Settings</h3>
            
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status *
                </label>
                <select name="status" 
                        id="status" 
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $job->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    Draft: Not visible • Published: Visible to seekers • Closed: No longer accepting applications
                </p>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('employer.jobs.show', $job) }}" 
               class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-indigo-600 text-white px-8 py-3 rounded-lg hover:bg-indigo-700 transition font-medium">
                Update Job Posting
            </button>
        </div>
    </form>

</x-layouts.dashboard>

