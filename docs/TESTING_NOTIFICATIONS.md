# 🧪 Testing Sistem Notifikasi Otomatis

## ✅ Status: Pesan Sistem Sudah Berhasil Dibuat di Database

Berdasarkan pengecekan database, sistem notifikasi **sudah berfungsi dengan baik**. Pesan otomatis sudah tersimpan di database dan siap ditampilkan.

## 📊 Data Testing yang Tersedia

### User untuk Testing
**Email:** seeker2@jobmaker.local  
**Password:** password  
**Nama:** Jane Smith  
**Role:** Seeker

### Conversations dengan System Messages
**Conversation ID 11:**
- Subject: "Re: DevOps Engineer"
- Employer: Tech Innovations Ltd
- Total Messages: 2 (semua system messages)
- System Messages:
  - 📅 Undangan Interview (created: 16 Oct 2025 06:08)
  - 🎉 Penawaran Kerja (created: 16 Oct 2025 06:16)
- Unread Count: 2

## 🧪 Cara Testing

### 1. Login sebagai Seeker (Jane Smith)

1. Buka browser: `http://127.0.0.1:8000`
2. Klik "Masuk / Daftar" di navbar
3. Login dengan:
   - Email: `seeker2@jobmaker.local`
   - Password: `password`

### 2. Buka Messages

1. Setelah login, klik menu "**Pesan**" di sidebar atau navbar
2. Anda akan melihat list conversations
3. Cari conversation dengan subject "**Re: DevOps Engineer**"
4. Perhatikan badge unread count (seharusnya ada angka 2)

### 3. Buka Conversation

1. Klik pada conversation "Re: DevOps Engineer"
2. Anda seharusnya melihat **2 pesan sistem** dengan:
   - ✅ Background biru muda (blue-50)
   - ✅ Border biru (blue-200)
   - ✅ Icon informasi di sebelah kiri
   - ✅ Posisi di tengah (bukan kiri/kanan)
   - ✅ Text bold untuk judul
   - ✅ Detail lengkap (posisi, perusahaan, status, tanggal, catatan)

### 4. Format Pesan yang Ditampilkan

**Pesan 1 (Interview):**
```
📅 *Pembaruan Status Lamaran*

Posisi: DevOps Engineer
Perusahaan: Tech Innovations Ltd
Status Baru: Undangan Interview
Tanggal: 16 Oct 2025 06:08

Catatan dari Rekruter:
[catatan jika ada]
```

**Pesan 2 (Offered):**
```
🎉 *Pembaruan Status Lamaran*

Posisi: DevOps Engineer
Perusahaan: Tech Innovations Ltd
Status Baru: Penawaran Kerja
Tanggal: 16 Oct 2025 06:16

Catatan dari Rekruter:
Selamat! Kami ingin menawarkan posisi ini kepada Anda...
```

## 🔄 Testing dari Employer Side

### Login sebagai Employer

**Email:** employer1@jobmaker.local (Tech Innovations Ltd)  
**Password:** password

### Test Update Status

1. Login sebagai employer
2. Buka "Lamaran" dari sidebar
3. Klik salah satu lamaran yang status masih "pending" atau "reviewed"
4. Di sidebar kanan, ada form "Update Status"
5. Pilih status baru (misalnya "Interview" atau "Offered")
6. Isi "Internal Notes" dengan pesan untuk kandidat
7. Klik "Update Status"
8. ✅ Pesan akan otomatis terkirim ke seeker

### Verifikasi dari Seeker

1. Logout dari employer account
2. Login sebagai seeker yang lamarannya baru saja di-update
3. Buka "Pesan" di sidebar
4. ✅ Seharusnya ada conversation baru atau update di conversation existing
5. ✅ Pesan sistem dengan format biru seharusnya muncul

## 🐛 Jika Pesan Tidak Muncul

### Checklist Debugging:

1. **Periksa apakah sudah login sebagai user yang tepat:**
   - Jane Smith untuk melihat pesan di Conversation ID 11

2. **Periksa apakah di halaman messages yang benar:**
   - URL seharusnya: `http://127.0.0.1:8000/messages`

3. **Periksa apakah conversation ada di list:**
   - Conversation "Re: DevOps Engineer" seharusnya ada di list

4. **Refresh halaman:**
   - Pesan baru tidak real-time, perlu refresh manual

5. **Check database manual:**
   ```bash
   cd src && php artisan tinker
   ```
   ```php
   $conv = App\Models\Conversation::find(11);
   echo "Messages: " . $conv->messages()->count();
   echo "\nSystem messages: " . $conv->messages()->where('sender_type', 'system')->count();
   ```

6. **Clear cache:**
   ```bash
   cd src && php artisan cache:clear
   cd src && php artisan view:clear
   ```

## 📸 Screenshot Checklist

Saat testing, pastikan Anda melihat:
- ✅ Pesan sistem dengan background biru muda
- ✅ Icon informasi berwarna biru di sebelah kiri pesan
- ✅ Pesan di posisi tengah
- ✅ Format text dengan bold untuk judul
- ✅ Emoji sesuai status (📅 interview, 🎉 offered, etc)
- ✅ Detail lengkap (posisi, perusahaan, status, tanggal)

## 🚀 Next Steps

Jika setelah testing pesan tidak muncul:
1. Screenshot halaman messages yang Anda lihat
2. Beritahu saya conversation mana yang Anda buka
3. Share email/username yang Anda gunakan untuk login
4. Saya akan debug lebih lanjut

## 📝 Catatan Penting

- Pesan sistem **hanya muncul** saat:
  - ✅ Seeker melamar pekerjaan (notif ke employer)
  - ✅ Employer update status lamaran (notif ke seeker)
- Pesan **tidak real-time**, perlu refresh halaman
- Jika testing dengan data seed, pastikan sudah run `php artisan migrate:fresh --seed`
- Conversation yang sudah ada sebelum implementasi **tidak akan punya** system messages

---

**Test berhasil jika:** Anda melihat pesan sistem dengan background biru di conversation messages.

**Dibuat:** 16 Oktober 2025  
**Status:** Ready for Testing ✅

