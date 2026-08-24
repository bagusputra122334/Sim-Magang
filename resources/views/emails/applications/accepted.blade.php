<x-mail::message>
# 🏢 Dinas Komunikasi, Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## 🎉 SELAMAT! Pendaftaran Diterima (Accepted)
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 🥳

Kami dengan senang hati memberitahukan bahwa **Pendaftaran Magang** Anda **DITERIMA (ACCEPTED)** di Program Magang Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban 🎊!

Berikut detail lengkap pendaftaran yang telah disetujui:

| Detail | Keterangan |
|---|---|
| **Kode Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Resmi** | <span style="color:#198754;font-weight:700;font-size:1.05em">{{ $statusLabel }} ✅</span> |
| **Tanggal Keputusan** | {{ now()->translatedFormat('l, d F Y H:i') }} WIB |

@if(!empty($catatanAdmin))
---
### 📝 Catatan dari Tim Admin
<blockquote style="padding:10px 14px;margin:10px 0;background:#f0faf4;border-left:4px solid #198754;border-radius:4px;color:#14532d;">
{!! nl2br(e($catatanAdmin)) !!}
</blockquote>
@endif

<x-mail::button :url="route('participant.registrations.show', $registration->id)" color="success">
🎉 Lihat Detail Pendaftaran Diterima
</x-mail::button>

@if(!empty($suratBalasanInfo))
---
### 📄 Surat Balasan
Surat Balasan resmi dari Dinas Komunikasi, Informatika, Statistik dan Persandian **telah tersedia** dan dapat Anda unduh melalui link di bawah:
<x-mail::button :url="$suratBalasanInfo['downloadRoute']" color="primary">
⬇️ Download Surat Balasan (PDF)
</x-mail::button>
*Jika tombol di atas tidak berfungsi, silakan akses melalui Dashboard Peserta → Riwayat Pendaftaran → Lihat Detail → Download Surat Balasan.*
@else
---
### 📄 Informasi Selanjutnya
**Surat Balasan resmi** akan segera diunggah oleh Tim Admin paling lambat **1 x 24 jam** sejak email ini dikirim.
Anda akan mendapatkan **email notifikasi terpisah** segera setelah file Surat Balasan siap diunduh.
@endif

---

#### 📌 Langkah Selanjutnya Setelah Mendownload Surat Balasan:
1. Baca dengan seksama seluruh **isi Surat Balasan**, terutama ketentuan jam kerja, dress code, dan peraturan internal.
2. Siapkan seluruh **dokumen kelengkapan** yang diperlukan pada hari pertama masuk.
3. Datang tepat waktu sesuai tanggal yang ditentukan pada surat.
4. Jika ada pertanyaan lebih lanjut, silakan hubungi narahubung yang tertera pada Surat Balasan.

Selamat bergabung dan selamat menjalankan Program Magang! 🌟

Terima kasih,
**Tim SIM-MAGANG**
**Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIM-MAGANG). Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
