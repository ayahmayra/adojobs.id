# Perbaikan Sistem Notifikasi Perubahan Status Lamaran

## 🐛 Masalah yang Diperbaiki

**Issue**: Perubahan status lowongan tidak menampilkan notifikasi pesan dan tidak tampil di `/messages`

## ✅ Solusi yang Diimplementasikan

### 1. **Perbaikan Loading Relationship**

**Masalah Awal**: 
- Relationship (`job.employer`, `seeker.user`) di-load setelah `updateStatus()` dipanggil
- Method `updateStatus()` melakukan `save()` yang bisa mengubah state object
- Data relationship mungkin hilang atau tidak konsisten setelah save

**Solusi**:
```php
// Load semua relationship SEBELUM updateStatus
$application->load(['job.employer', 'seeker.user']);

// Simpan data yang diperlukan sebagai variabel lokal
$jobTitle = $application->job->title;
$companyName = $application->job->employer->company_name;
$seekerId = $application->seeker_id;
// ... dst

// Baru kemudian update status
$application->updateStatus($request->status, $request->employer_notes);

// Kirim notifikasi dengan data yang sudah disimpan
$this->sendStatusUpdateNotificationToSeeker(...);
```

### 2. **Pemisahan Internal Notes dan Message to Seeker**

**Sebelum**:
- Hanya ada 1 field: `employer_notes`
- Field ini digunakan untuk internal notes DAN dikirim ke seeker
- Tidak ada kontrol atas apa yang dikirim ke kandidat

**Sesudah**:
- **Pesan untuk Kandidat** (`message_to_seeker`): Pesan yang akan dikirim ke kandidat bersama notifikasi
- **Catatan Internal** (`employer_notes`): Catatan pribadi employer yang TIDAK dikirim ke kandidat

**Perubahan di Form** (`employer/applications/show.blade.php`):
```html
<!-- Field untuk pesan ke kandidat -->
<div class="mb-4">
    <label for="message_to_seeker">Pesan untuk Kandidat</label>
    <textarea name="message_to_seeker" ...>
    <p>Pesan ini akan dikirim ke kandidat bersama notifikasi perubahan status</p>
</div>

<!-- Field untuk catatan internal -->
<div class="mb-4">
    <label for="employer_notes">Catatan Internal</label>
    <textarea name="employer_notes" ...>
    <p>Catatan ini hanya terlihat oleh Anda (tidak akan dikirim ke kandidat)</p>
</div>
```

**Perubahan di Controller**:
```php
$request->validate([
    'status' => 'required|in:...',
    'employer_notes' => 'nullable|string',
    'message_to_seeker' => 'nullable|string|max:1000', // Field baru
]);

// Update status dengan internal notes
$application->updateStatus($request->status, $request->employer_notes);

// Kirim notifikasi dengan message_to_seeker (BUKAN employer_notes)
$this->sendStatusUpdateNotificationToSeeker(
    $application, 
    $request->status, 
    $request->message_to_seeker, // ← Ini yang dikirim ke seeker
    ...
);
```

### 3. **Improved Error Logging**

```php
try {
    $this->sendStatusUpdateNotificationToSeeker(...);
} catch (\Exception $e) {
    \Log::error('Failed to send status update notification: ' . $e->getMessage());
    \Log::error('Stack trace: ' . $e->getTraceAsString()); // ← Ditambahkan
    // Continue execution
}
```

### 4. **Fallback Parameters**

Method `sendStatusUpdateNotificationToSeeker()` sekarang menerima parameter opsional dengan fallback:

```php
private function sendStatusUpdateNotificationToSeeker(
    $application, 
    $newStatus, 
    $notes = null,
    $jobTitle = null,          // ← Parameter opsional
    $companyName = null,       // ← Parameter opsional
    $seekerId = null,          // ← Parameter opsional
    $employerId = null,        // ← Parameter opsional
    $employerUserId = null,    // ← Parameter opsional
    $jobId = null              // ← Parameter opsional
) {
    // Gunakan parameter jika ada, atau fallback ke relationship
    $jobTitle = $jobTitle ?? $application->job->title;
    $companyName = $companyName ?? $application->job->employer->company_name;
    // ... dst
}
```

## 📝 Format Notifikasi

### Tanpa Pesan dari Perekrut:
```
📅 *Pembaruan Status Lamaran*

Posisi: Senior Full Stack Developer
Perusahaan: Tech Startup Indonesia
Status Baru: Undangan Interview
Tanggal: 17 Okt 2025 14:30
```

### Dengan Pesan dari Perekrut:
```
📅 *Pembaruan Status Lamaran*

Posisi: Senior Full Stack Developer
Perusahaan: Tech Startup Indonesia
Status Baru: Undangan Interview
Tanggal: 17 Okt 2025 14:30

💬 Pesan dari Perekrut:
Kami mengundang Anda untuk wawancara. Silakan datang ke kantor kami 
pada Senin, 21 Oktober 2025 pukul 10:00 WIB. Mohon konfirmasi 
kehadiran Anda.
```

## 🎯 Status yang Tersedia

| Status | Label Indonesia | Emoji |
|--------|----------------|-------|
| pending | Menunggu Review | ⏳ |
| reviewed | Telah Direview | 👀 |
| shortlisted | Masuk Shortlist | ⭐ |
| interview | Undangan Interview | 📅 |
| offered | Penawaran Kerja | 🎉 |
| hired | Diterima | ✅ |
| rejected | Ditolak | ❌ |

## 🧪 Testing

### Manual Testing:

1. **Login sebagai Employer**
2. **Buka Lamaran** di `/employer/applications`
3. **Klik salah satu lamaran** untuk melihat detailnya
4. **Update Status**:
   - Pilih status baru (misalnya: "Interview")
   - Isi "Pesan untuk Kandidat" (opsional)
   - Isi "Catatan Internal" (opsional)
   - Klik "Update Status"
5. **Cek Notifikasi**:
   - Login sebagai Seeker yang melamar
   - Buka `/messages`
   - Seharusnya ada pesan sistem baru dengan notifikasi perubahan status
   - Pesan hanya menampilkan "Pesan untuk Kandidat", TIDAK menampilkan "Catatan Internal"

### Debugging:

Jika notifikasi tidak muncul, cek log:

```bash
cd /Users/hermansyah/dev/jobmakerproject/src
tail -f storage/logs/laravel.log
```

Cari error dengan prefix: `"Failed to send status update notification"`

## 📁 File yang Dimodifikasi

1. **Controller**: `src/app/Http/Controllers/Employer/ApplicationController.php`
   - Method `updateStatus()` - Perbaikan loading relationship dan parameter passing
   - Method `sendStatusUpdateNotificationToSeeker()` - Fallback parameters

2. **View**: `src/resources/views/employer/applications/show.blade.php`
   - Tambah field `message_to_seeker` 
   - Update label dan help text

3. **Documentation**: `NOTIFICATION_FIX.md` (file ini)

## 🚀 Deployment

Setelah pull/merge changes:

```bash
# Tidak perlu migration karena tidak ada perubahan database
# Hanya perlu clear cache jika diperlukan
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## 📌 Catatan Penting

1. **Backward Compatibility**: 
   - Method `sendStatusUpdateNotificationToSeeker()` masih support pemanggilan lama (tanpa parameter tambahan)
   - Fallback ke relationship jika parameter tidak disediakan

2. **Validation**:
   - `message_to_seeker` maksimal 1000 karakter
   - `employer_notes` tidak ada batas (nullable string)

3. **Privacy**:
   - `employer_notes` HANYA visible di employer dashboard
   - `message_to_seeker` dikirim ke seeker via notification
   - Keduanya disimpan di database untuk audit trail

## 🔮 Future Improvements

1. ✅ Rich text editor untuk message_to_seeker
2. ✅ Template pesan untuk status umum
3. ✅ Email notification (selain message internal)
4. ✅ Real-time notification dengan WebSocket/Pusher
5. ✅ Notification preferences per user

---

**Tanggal Perbaikan**: 17 Oktober 2025  
**Versi**: 2.0  
**Status**: ✅ Fixed & Tested

