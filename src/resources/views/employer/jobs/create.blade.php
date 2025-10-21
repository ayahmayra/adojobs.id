<x-layouts.dashboard>
    <x-slot name="title">Pasang Lowongan Baru</x-slot>
    
    <x-slot name="sidebar">
        <x-sidebar.employer />
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <span>Pasang Lowongan Baru</span>
            <a href="{{ route('employer.jobs.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Kembali ke Lowongan Saya
            </a>
        </div>
    </x-slot>

    <form action="{{ route('employer.jobs.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Basic Information --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Informasi Dasar</h3>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Job Title --}}
                <div class="md:col-span-2">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-700">
                        Judul Lowongan *
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('title') border-red-500 @enderror"
                           placeholder="contoh: Senior Full Stack Developer">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div>
                    <label for="category_id" class="block mb-2 text-sm font-medium text-gray-700">
                        Kategori *
                    </label>
                    <select name="category_id" 
                            id="category_id" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('category_id') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                    <label for="vacancies" class="block mb-2 text-sm font-medium text-gray-700">
                        Jumlah Posisi *
                    </label>
                    <input type="number" 
                           name="vacancies" 
                           id="vacancies" 
                           value="{{ old('vacancies', 1) }}"
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
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Detail Lowongan</h3>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Job Type --}}
                <div>
                    <label for="job_type" class="block mb-2 text-sm font-medium text-gray-700">
                        Tipe Pekerjaan *
                    </label>
                    <select name="job_type" 
                            id="job_type" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('job_type') border-red-500 @enderror">
                        <option value="">Pilih Tipe</option>
                        <option value="full-time" {{ old('job_type') == 'full-time' ? 'selected' : '' }}>Penuh Waktu</option>
                        <option value="part-time" {{ old('job_type') == 'part-time' ? 'selected' : '' }}>Paruh Waktu</option>
                        <option value="contract" {{ old('job_type') == 'contract' ? 'selected' : '' }}>Kontrak</option>
                        <option value="freelance" {{ old('job_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="internship" {{ old('job_type') == 'internship' ? 'selected' : '' }}>Magang</option>
                    </select>
                    @error('job_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Work Mode --}}
                <div>
                    <label for="work_mode" class="block mb-2 text-sm font-medium text-gray-700">
                        Mode Kerja *
                    </label>
                    <select name="work_mode" 
                            id="work_mode" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('work_mode') border-red-500 @enderror">
                        <option value="">Pilih Mode</option>
                        <option value="on-site" {{ old('work_mode') == 'on-site' ? 'selected' : '' }}>Di Kantor</option>
                        <option value="remote" {{ old('work_mode') == 'remote' ? 'selected' : '' }}>Remote</option>
                        <option value="hybrid" {{ old('work_mode') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                    </select>
                    @error('work_mode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Location --}}
                <div>
                    <label for="location" class="block mb-2 text-sm font-medium text-gray-700">
                        Lokasi
                    </label>
                    <input type="text" 
                           name="location" 
                           id="location" 
                           value="{{ old('location') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: Sudirman, Jakarta Pusat">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-700">
                        Kota
                    </label>
                    <input type="text" 
                           name="city" 
                           id="city" 
                           value="{{ old('city') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: Bengkalis">
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Application Deadline --}}
                <div>
                    <label for="application_deadline" class="block mb-2 text-sm font-medium text-gray-700">
                        Batas Waktu Lamaran *
                    </label>
                    <input type="date" 
                           name="application_deadline" 
                           id="application_deadline" 
                           value="{{ old('application_deadline') }}"
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           required
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('application_deadline') border-red-500 @enderror">
                    @error('application_deadline')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Salary Information --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Informasi Gaji</h3>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                {{-- Salary Min --}}
                <div>
                    <label for="salary_min" class="block mb-2 text-sm font-medium text-gray-700">
                        Gaji Minimum (IDR)
                    </label>
                    <input type="number" 
                           name="salary_min" 
                           id="salary_min" 
                           value="{{ old('salary_min') }}"
                           min="0"
                           step="100000"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: 5000000">
                    @error('salary_min')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Salary Max --}}
                <div>
                    <label for="salary_max" class="block mb-2 text-sm font-medium text-gray-700">
                        Gaji Maksimum (IDR)
                    </label>
                    <input type="number" 
                           name="salary_max" 
                           id="salary_max" 
                           value="{{ old('salary_max') }}"
                           min="0"
                           step="100000"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: 10000000">
                    @error('salary_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Salary Period --}}
                <div>
                    <label for="salary_period" class="block mb-2 text-sm font-medium text-gray-700">
                        Periode Gaji *
                    </label>
                    <select name="salary_period" 
                            id="salary_period" 
                            required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('salary_period') border-red-500 @enderror">
                        <option value="monthly" {{ old('salary_period', 'monthly') == 'monthly' ? 'selected' : '' }}>Per Bulan</option>
                        <option value="yearly" {{ old('salary_period') == 'yearly' ? 'selected' : '' }}>Per Tahun</option>
                        <option value="hourly" {{ old('salary_period') == 'hourly' ? 'selected' : '' }}>Per Jam</option>
                        <option value="daily" {{ old('salary_period') == 'daily' ? 'selected' : '' }}>Per Hari</option>
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
                               {{ old('salary_negotiable') ? 'checked' : '' }}
                               class="text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Gaji dapat dinegosiasikan</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Requirements & Experience --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Persyaratan & Pengalaman</h3>
            
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Experience Level --}}
                <div>
                    <label for="experience_level" class="block mb-2 text-sm font-medium text-gray-700">
                        Level Pengalaman
                    </label>
                    <input type="text" 
                           name="experience_level" 
                           id="experience_level" 
                           value="{{ old('experience_level') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: Entry Level, Mid Level, Senior">
                    @error('experience_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Education Level --}}
                <div>
                    <label for="education_level" class="block mb-2 text-sm font-medium text-gray-700">
                        Level Pendidikan
                    </label>
                    <input type="text" 
                           name="education_level" 
                           id="education_level" 
                           value="{{ old('education_level') }}"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: S1 (Sarjana)">
                    @error('education_level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Experience Years Min --}}
                <div>
                    <label for="experience_years_min" class="block mb-2 text-sm font-medium text-gray-700">
                        Pengalaman Minimum (Tahun)
                    </label>
                    <input type="number" 
                           name="experience_years_min" 
                           id="experience_years_min" 
                           value="{{ old('experience_years_min') }}"
                           min="0"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: 2">
                    @error('experience_years_min')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Experience Years Max --}}
                <div>
                    <label for="experience_years_max" class="block mb-2 text-sm font-medium text-gray-700">
                        Pengalaman Maksimum (Tahun)
                    </label>
                    <input type="number" 
                           name="experience_years_max" 
                           id="experience_years_max" 
                           value="{{ old('experience_years_max') }}"
                           min="0"
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           placeholder="contoh: 5">
                    @error('experience_years_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Description & Details --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Deskripsi Pekerjaan</h3>
            
            <div class="space-y-6">
                {{-- Description --}}
                <div>
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-700">
                        Deskripsi Pekerjaan *
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="6"
                              required
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror"
                              placeholder="Jelaskan peran pekerjaan, apa yang akan dilakukan kandidat...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Requirements --}}
                <div>
                    <label for="requirements" class="block mb-2 text-sm font-medium text-gray-700">
                        Persyaratan
                    </label>
                    <textarea name="requirements" 
                              id="requirements" 
                              rows="6"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Daftarkan persyaratan untuk posisi ini...">{{ old('requirements') }}</textarea>
                    @error('requirements')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Responsibilities --}}
                <div>
                    <label for="responsibilities" class="block mb-2 text-sm font-medium text-gray-700">
                        Tanggung Jawab
                    </label>
                    <textarea name="responsibilities" 
                              id="responsibilities" 
                              rows="6"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Daftarkan tanggung jawab utama...">{{ old('responsibilities') }}</textarea>
                    @error('responsibilities')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Benefits --}}
                <div>
                    <label for="benefits" class="block mb-2 text-sm font-medium text-gray-700">
                        Benefit & Fasilitas
                    </label>
                    <textarea name="benefits" 
                              id="benefits" 
                              rows="4"
                              class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                              placeholder="Daftarkan benefit dan fasilitas yang ditawarkan...">{{ old('benefits') }}</textarea>
                    @error('benefits')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Publish Settings --}}
        <div class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-lg font-semibold text-gray-900">Pengaturan Publikasi</h3>
            
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-700">
                    Status *
                </label>
                <select name="status" 
                        id="status" 
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @error('status') border-red-500 @enderror">
                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Simpan sebagai Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publikasikan Sekarang</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">
                    Lowongan draft tidak akan terlihat oleh pencari kerja. Anda dapat mempublikasikannya nanti.
                </p>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('employer.jobs.index') }}" 
               class="px-8 py-3 font-medium text-gray-700 bg-gray-200 rounded-lg transition hover:bg-gray-300">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-3 font-medium text-white bg-indigo-600 rounded-lg transition hover:bg-indigo-700">
                Buat Lowongan
            </button>
        </div>
    </form>

</x-layouts.dashboard>
