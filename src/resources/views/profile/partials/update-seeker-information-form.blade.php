<section>
    <header>
        <p class="text-sm text-gray-600">
            {{ __("Perbarui informasi profil pencari kerja Anda agar lebih mudah ditemukan oleh perusahaan.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.seeker.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            {{-- Phone --}}
            <div>
                <x-input-label for="phone" value="Nomor Telepon" />
                <x-text-input id="phone" name="phone" type="text" class="block mt-1 w-full" 
                              :value="old('phone', $user->seeker->phone ?? '')" 
                              placeholder="+62 812 3456 7890" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            {{-- Date of Birth --}}
            <div>
                <x-input-label for="date_of_birth" value="Tanggal Lahir" />
                <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="block mt-1 w-full" 
                              :value="old('date_of_birth', $user->seeker->date_of_birth?->format('Y-m-d') ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
            </div>

            {{-- Gender --}}
            <div>
                <x-input-label for="gender" value="Jenis Kelamin" />
                <select id="gender" name="gender" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="male" {{ old('gender', $user->seeker->gender ?? '') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="female" {{ old('gender', $user->seeker->gender ?? '') === 'female' ? 'selected' : '' }}>Perempuan</option>
                    <option value="other" {{ old('gender', $user->seeker->gender ?? '') === 'other' ? 'selected' : '' }}>Lainnya</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>

            {{-- Current Job Title --}}
            <div>
                <x-input-label for="current_job_title" value="Jabatan Saat Ini" />
                <x-text-input id="current_job_title" name="current_job_title" type="text" class="block mt-1 w-full" 
                              :value="old('current_job_title', $user->seeker->current_job_title ?? '')" 
                              placeholder="contoh: Software Engineer Senior" />
                <x-input-error class="mt-2" :messages="$errors->get('current_job_title')" />
            </div>

            {{-- City --}}
            <div>
                <x-input-label for="city" value="Kota" />
                <x-text-input id="city" name="city" type="text" class="block mt-1 w-full" 
                              :value="old('city', $user->seeker->city ?? '')" 
                              placeholder="contoh: Jakarta" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            {{-- Country --}}
            <div>
                <x-input-label for="country" value="Negara" />
                <x-text-input id="country" name="country" type="text" class="block mt-1 w-full" 
                              :value="old('country', $user->seeker->country ?? 'Indonesia')" 
                              placeholder="Indonesia" />
                <x-input-error class="mt-2" :messages="$errors->get('country')" />
            </div>

            {{-- Job Type Preference --}}
            <div>
                <x-input-label for="job_type_preference" value="Preferensi Tipe Pekerjaan" />
                <select id="job_type_preference" name="job_type_preference" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Pilih Preferensi</option>
                    <option value="full-time" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="part-time" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="contract" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'contract' ? 'selected' : '' }}>Kontrak</option>
                    <option value="freelance" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'freelance' ? 'selected' : '' }}>Freelance</option>
                    <option value="internship" {{ old('job_type_preference', $user->seeker->job_type_preference ?? '') === 'internship' ? 'selected' : '' }}>Magang</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('job_type_preference')" />
            </div>

            {{-- Preferred Location --}}
            <div>
                <x-input-label for="preferred_location" value="Lokasi Kerja yang Diinginkan" />
                <x-text-input id="preferred_location" name="preferred_location" type="text" class="block mt-1 w-full" 
                              :value="old('preferred_location', $user->seeker->preferred_location ?? '')" 
                              placeholder="contoh: Jakarta atau Remote" />
                <x-input-error class="mt-2" :messages="$errors->get('preferred_location')" />
            </div>
        </div>

        {{-- Bio --}}
        <div class="mt-6">
            <x-input-label for="bio" value="Bio / Tentang Saya" />
            <textarea id="bio" name="bio" rows="4" 
                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Ceritakan tentang diri Anda, pengalaman, dan apa yang Anda cari...">{{ old('bio', $user->seeker->bio ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        {{-- Expected Salary --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="expected_salary_min" value="Gaji yang Diharapkan (Min)" />
                <x-text-input id="expected_salary_min" name="expected_salary_min" type="number" class="block mt-1 w-full" 
                              :value="old('expected_salary_min', $user->seeker->expected_salary_min ?? '')" 
                              placeholder="5000000" />
                <x-input-error class="mt-2" :messages="$errors->get('expected_salary_min')" />
            </div>

            <div>
                <x-input-label for="expected_salary_max" value="Gaji yang Diharapkan (Max)" />
                <x-text-input id="expected_salary_max" name="expected_salary_max" type="number" class="block mt-1 w-full" 
                              :value="old('expected_salary_max', $user->seeker->expected_salary_max ?? '')" 
                              placeholder="10000000" />
                <x-input-error class="mt-2" :messages="$errors->get('expected_salary_max')" />
            </div>
        </div>

        {{-- Social Links --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <x-input-label for="linkedin_url" value="URL LinkedIn" />
                <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="block mt-1 w-full" 
                              :value="old('linkedin_url', $user->seeker->linkedin_url ?? '')" 
                              placeholder="https://linkedin.com/in/namaanda" />
                <x-input-error class="mt-2" :messages="$errors->get('linkedin_url')" />
            </div>

            <div>
                <x-input-label for="github_url" value="URL GitHub" />
                <x-text-input id="github_url" name="github_url" type="url" class="block mt-1 w-full" 
                              :value="old('github_url', $user->seeker->github_url ?? '')" 
                              placeholder="https://github.com/namaanda" />
                <x-input-error class="mt-2" :messages="$errors->get('github_url')" />
            </div>

            <div class="md:col-span-2">
                <x-input-label for="portfolio_url" value="URL Portofolio" />
                <x-text-input id="portfolio_url" name="portfolio_url" type="url" class="block mt-1 w-full" 
                              :value="old('portfolio_url', $user->seeker->portfolio_url ?? '')" 
                              placeholder="https://portofolio-anda.com" />
                <x-input-error class="mt-2" :messages="$errors->get('portfolio_url')" />
            </div>
        </div>

        {{-- Skills (Simple text input for now) --}}
        <div>
            <x-input-label for="skills" value="Keahlian (dipisahkan dengan koma)" />
            @php
                $seekerSkills = $user->seeker->skills ?? [];
                $skillsValue = is_array($seekerSkills) ? implode(', ', $seekerSkills) : '';
            @endphp
            <x-text-input id="skills" name="skills" type="text" class="block mt-1 w-full" 
                          :value="old('skills', $skillsValue)" 
                          placeholder="PHP, Laravel, JavaScript, React, dll." />
            <p class="mt-1 text-xs text-gray-500">Pisahkan keahlian dengan koma</p>
            <x-input-error class="mt-2" :messages="$errors->get('skills')" />
        </div>

        {{-- Work Experience --}}
        <div class="pt-6 mt-6 border-t border-gray-200">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Pengalaman Kerja</h3>
                    <p class="text-sm text-gray-600">Tambahkan riwayat pengalaman kerja Anda</p>
                </div>
                <button type="button" onclick="addExperience()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengalaman
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
                            <h4 class="font-medium text-gray-900">Pengalaman #{{ $index + 1 }}</h4>
                            @if($index > 0 || count($experiences) > 1)
                                <button type="button" onclick="removeExperience(this)" 
                                        class="text-sm text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input type="text" name="experience[{{ $index }}][job_title]" 
                                       value="{{ $exp['job_title'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="contoh: Software Engineer">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                                <input type="text" name="experience[{{ $index }}][company_name]" 
                                       value="{{ $exp['company_name'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="contoh: PT ABC">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                                <input type="month" name="experience[{{ $index }}][start_date]" 
                                       value="{{ $exp['start_date'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
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
                                    <span class="ml-2 text-sm text-gray-700">Saya masih bekerja di sini</span>
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="experience[{{ $index }}][description]" rows="3"
                                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Jelaskan tanggung jawab dan pencapaian Anda...">{{ $exp['description'] ?? '' }}</textarea>
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
                    <h3 class="text-base font-semibold text-gray-900">Pendidikan</h3>
                    <p class="text-sm text-gray-600">Tambahkan riwayat pendidikan Anda</p>
                </div>
                <button type="button" onclick="addEducation()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm text-white bg-indigo-600 rounded-md transition hover:bg-indigo-700">
                    <svg class="mr-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pendidikan
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
                            <h4 class="font-medium text-gray-900">Pendidikan #{{ $index + 1 }}</h4>
                            @if($index > 0 || count($educations) > 1)
                                <button type="button" onclick="removeEducation(this)" 
                                        class="text-sm text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gelar</label>
                                <input type="text" name="education[{{ $index }}][degree]" 
                                       value="{{ $edu['degree'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="contoh: Sarjana Teknik Informatika">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Institusi</label>
                                <input type="text" name="education[{{ $index }}][institution]" 
                                       value="{{ $edu['institution'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="contoh: Universitas Indonesia">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Bidang Studi</label>
                                <input type="text" name="education[{{ $index }}][field_of_study]" 
                                       value="{{ $edu['field_of_study'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="contoh: Teknik Perangkat Lunak">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Mulai</label>
                                <input type="month" name="education[{{ $index }}][start_date]" 
                                       value="{{ $edu['start_date'] ?? '' }}"
                                       class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tahun Selesai</label>
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
                                    <span class="ml-2 text-sm text-gray-700">Saya masih belajar di sini</span>
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                                <textarea name="education[{{ $index }}][description]" rows="2"
                                          class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Pencapaian, mata kuliah relevan, dll...">{{ $edu['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-4 items-center">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('seeker-updated'))
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Tersimpan.') }}</p>
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
                        <h4 class="font-medium text-gray-900">Pengalaman #${expIndex + 1}</h4>
                        <button type="button" onclick="removeExperience(this)" 
                                class="text-sm text-red-600 hover:text-red-800">
                            Hapus
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <input type="text" name="experience[${expIndex}][job_title]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="contoh: Software Engineer">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                            <input type="text" name="experience[${expIndex}][company_name]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="contoh: PT ABC">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="month" name="experience[${expIndex}][start_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="month" name="experience[${expIndex}][end_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm end-date focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="experience[${expIndex}][is_current]" value="1"
                                       onchange="toggleEndDate(this)"
                                       class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Saya masih bekerja di sini</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <textarea name="experience[${expIndex}][description]" rows="3"
                                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Jelaskan tanggung jawab dan pencapaian Anda..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            expIndex++;
        }

        function removeExperience(button) {
            if (confirm('Apakah Anda yakin ingin menghapus pengalaman ini?')) {
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
                        <h4 class="font-medium text-gray-900">Pendidikan #${eduIndex + 1}</h4>
                        <button type="button" onclick="removeEducation(this)" 
                                class="text-sm text-red-600 hover:text-red-800">
                            Hapus
                        </button>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Gelar</label>
                            <input type="text" name="education[${eduIndex}][degree]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="contoh: Sarjana Teknik Informatika">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Institusi</label>
                            <input type="text" name="education[${eduIndex}][institution]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="contoh: Universitas Indonesia">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Bidang Studi</label>
                            <input type="text" name="education[${eduIndex}][field_of_study]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   placeholder="contoh: Teknik Perangkat Lunak">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun Mulai</label>
                            <input type="month" name="education[${eduIndex}][start_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tahun Selesai</label>
                            <input type="month" name="education[${eduIndex}][end_date]" 
                                   class="block mt-1 w-full rounded-md border-gray-300 shadow-sm edu-end-date focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="education[${eduIndex}][is_current]" value="1"
                                       onchange="toggleEducationEndDate(this)"
                                       class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Saya masih belajar di sini</span>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi (Opsional)</label>
                            <textarea name="education[${eduIndex}][description]" rows="2"
                                      class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      placeholder="Pencapaian, mata kuliah relevan, dll..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', template);
            eduIndex++;
        }

        function removeEducation(button) {
            if (confirm('Apakah Anda yakin ingin menghapus pendidikan ini?')) {
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
