<x-layouts.app>
    <div class="min-h-screen bg-gradient-to-br from-yellow-50 via-white to-indigo-50 flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Welcome Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <!-- Header with Icon -->
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-8 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                        <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Selamat Datang, Tester AdoJobs!</h1>
                    <p class="text-yellow-100 text-lg">{{ auth()->user()->name }}</p>
                </div>

                <!-- Content -->
                <div class="p-8">
                    <!-- Tester Badge Display -->
                    <div class="mb-6 text-center">
                        <p class="text-gray-600 mb-3">Anda sekarang memiliki badge khusus:</p>
                        <x-tester-badge :user="auth()->user()" class="text-base px-4 py-2" />
                    </div>

                    <!-- Welcome Message -->
                    <div class="prose prose-indigo max-w-none mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Terima kasih telah bergabung sebagai Tester!</h2>
                        
                        <p class="text-gray-600 mb-4">
                            Sebagai Tester AdoJobs, Anda memiliki peran penting dalam membantu kami meningkatkan platform. 
                            Anda dapat menggunakan semua fitur seperti biasa, namun dengan tanggung jawab khusus:
                        </p>

                        <ul class="list-disc list-inside space-y-2 text-gray-600 mb-6">
                            <li>Mengeksplorasi dan menggunakan semua fitur platform</li>
                            <li>Melaporkan bug atau error yang ditemukan</li>
                            <li>Memberikan saran perbaikan untuk UI/UX</li>
                            <li>Memberikan feedback tentang performa sistem</li>
                            <li>Membantu identifikasi masalah keamanan</li>
                        </ul>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 font-medium">
                                        Catatan Penting: Peran tester tidak mengubah role atau hak akses Anda sebagai 
                                        {{ auth()->user()->isEmployer() ? 'Recruiter' : 'Candidate' }}. 
                                        Semua fitur normal tetap berfungsi seperti biasa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('tester.feedback') }}" 
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Kirim Feedback
                        </a>
                        
                        <form action="{{ route('tester.welcomed') }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Masuk ke Dashboard
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Info Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Anda dapat mengakses halaman feedback kapan saja dari menu dashboard Anda.</p>
            </div>
        </div>
    </div>
</x-layouts.app>
