# Sistem Notifikasi Otomatis (Automated Notifications)

## 📋 Ringkasan

Sistem ini mengirimkan pesan notifikasi otomatis melalui fitur messaging internal untuk event-event penting terkait lamaran pekerjaan.

## ✨ Fitur

### 1. Notifikasi Lamaran Baru (ke Employer)

Ketika seorang **Seeker** melamar pekerjaan, **Employer** akan otomatis menerima pesan notifikasi yang berisi:

- 📋 Icon lamaran baru
- Nama kandidat
- Posisi yang dilamar
- Tanggal dan waktu melamar
- Pesan untuk cek lamaran di dashboard

**Contoh Pesan:**
```
📋 *Lamaran Baru*

Kandidat: Ahmad Rahman
Posisi: Senior Full Stack Developer
Tanggal Melamar: 16 Okt 2025 13:13

Silakan cek lamaran di dashboard Anda.
```

### 2. Notifikasi Perubahan Status (ke Seeker)

Ketika **Employer** mengubah status lamaran, **Seeker** akan otomatis menerima pesan notifikasi yang berisi:

- Icon sesuai status (⏳ pending, 👀 reviewed, ⭐ shortlisted, 📅 interview, 🎉 offered, ✅ hired, ❌ rejected)
- Nama posisi
- Nama perusahaan
- Status baru dalam bahasa Indonesia
- Tanggal dan waktu pembaruan
- Catatan dari rekruter (jika ada)

**Status yang Tersedia:**
- ⏳ **Pending** → Menunggu Review
- 👀 **Reviewed** → Telah Direview
- ⭐ **Shortlisted** → Masuk Shortlist
- 📅 **Interview** → Undangan Interview
- 🎉 **Offered** → Penawaran Kerja
- ✅ **Hired** → Diterima
- ❌ **Rejected** → Ditolak

**Contoh Pesan:**
```
📅 *Pembaruan Status Lamaran*

Posisi: Renewable Energy Engineer
Perusahaan: Green Energy Solutions
Status Baru: Undangan Interview
Tanggal: 16 Okt 2025 13:12

Catatan dari Rekruter:
Kami mengundang Anda untuk wawancara. Silakan datang ke kantor kami pada Senin, 21 Oktober 2025 pukul 10:00 WIB. Mohon konfirmasi kehadiran Anda.
```

## 🎨 Tampilan UI

### Pesan Sistem
Pesan notifikasi otomatis ditampilkan dengan style khusus yang berbeda dari pesan biasa:

- **Background**: Biru muda (blue-50)
- **Border**: Biru (blue-200)
- **Icon**: Icon informasi berwarna biru
- **Posisi**: Tengah (center), bukan kiri/kanan seperti pesan user biasa
- **Format**: Bold untuk judul, whitespace-pre-wrap untuk format pesan

### Pesan User Biasa
- **Dari current user**: Background ungu (indigo-600), teks putih, posisi kanan
- **Dari lawan bicara**: Background abu-abu (gray-100), teks hitam, posisi kiri

## 🔧 Implementasi Teknis

### File yang Terlibat

1. **`src/app/Http/Controllers/Seeker/ApplicationController.php`**
   - Method: `sendApplicationNotificationToEmployer()`
   - Dipanggil saat: Seeker submit lamaran (method `store()`)

2. **`src/app/Http/Controllers/Employer/ApplicationController.php`**
   - Method: `sendStatusUpdateNotificationToSeeker()`
   - Dipanggil saat: Employer update status lamaran (method `updateStatus()`)

3. **`src/resources/views/messages/show.blade.php`**
   - Conditional rendering untuk pesan sistem (`sender_type === 'system'`)

### Database Schema

**Table: `messages`**
- `sender_type`: 'system' untuk pesan otomatis, 'seeker' atau 'employer' untuk pesan user biasa
- Pesan sistem tetap menyimpan `sender_id` untuk tracking, tapi ditampilkan secara berbeda di UI

### Cara Kerja

1. **Saat Lamaran Baru:**
   ```
   Seeker Apply → ApplicationController@store → sendApplicationNotificationToEmployer()
   → Create/Find Conversation → Create System Message → Increment employer_unread_count
   ```

2. **Saat Update Status:**
   ```
   Employer Update Status → ApplicationController@updateStatus → Load Relationships
   → Update Status → sendStatusUpdateNotificationToSeeker() → Create/Find Conversation
   → Create System Message → Increment seeker_unread_count
   ```

## 🧪 Testing

Database sudah di-seed dengan beberapa pesan sistem untuk testing:

```bash
cd src && php artisan migrate:fresh --seed
```

Data testing yang dibuat:
- 4+ pesan sistem (lamaran baru dan perubahan status)
- 10+ conversations dengan berbagai status
- 50+ messages (termasuk pesan sistem dan pesan biasa)

## 🐛 Troubleshooting

### Pesan Tidak Muncul

Jika pesan notifikasi tidak muncul setelah update status:

1. **Cek Log Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Cek Database:**
   ```php
   // Di tinker
   php artisan tinker
   
   // Cek pesan sistem terbaru
   $systemMessages = App\Models\Message::where('sender_type', 'system')
       ->orderBy('created_at', 'desc')->take(5)->get();
   ```

3. **Pastikan Relationships Loaded:**
   - Controller sudah menambahkan `$application->load(['job.employer', 'seeker.user']);`
   - Jika error, akan tercatat di log dengan prefix "Failed to send status update notification"

4. **Refresh Halaman Messages:**
   - Pastikan user sudah refresh halaman `/messages` untuk melihat pesan baru
   - Cek unread count di sidebar atau header

### Error Handling

Sistem menggunakan try-catch untuk memastikan error notifikasi tidak mengganggu proses utama:

```php
try {
    $this->sendStatusUpdateNotificationToSeeker(...);
} catch (\Exception $e) {
    \Log::error('Failed to send status update notification: ' . $e->getMessage());
    // Continue execution
}
```

## 📝 Catatan

1. **Internal Notes vs Message to Seeker:**
   - Saat ini, field `employer_notes` akan dikirim ke seeker sebagai "Catatan dari Rekruter"
   - Jika ingin memisahkan internal notes dan message ke seeker, perlu menambah field baru di form

2. **Real-time Notifications:**
   - Saat ini notifikasi hanya muncul setelah refresh halaman
   - Untuk real-time notifications, perlu implementasi WebSocket atau Pusher

3. **Email Notifications:**
   - Sistem ini hanya mengirim pesan internal
   - Jika ingin kirim email juga, perlu menambahkan Laravel Queue dan Mail

## 🚀 Future Enhancements

1. ✅ Real-time notifications dengan Pusher/WebSocket
2. ✅ Email notifications
3. ✅ Push notifications untuk mobile
4. ✅ Notification preferences (user bisa pilih mau terima notif apa saja)
5. ✅ Notification history/archive
6. ✅ Mark all as read feature
7. ✅ Custom notification templates

---

**Tanggal Dibuat:** 16 Oktober 2025  
**Versi:** 1.0  
**Status:** ✅ Production Ready

