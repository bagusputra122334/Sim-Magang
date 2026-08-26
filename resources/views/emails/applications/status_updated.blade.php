<x-mail::message>
# 🏢 Dinas Komunikasi, Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

@if($statusEnum === \App\Enums\RegistrationStatus::Accepted)
<x-mail::panel>
## 🎉 SELAMAT! Pendaftaran Diterima (Accepted)
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 🥳

Kami dengan senang hati memberitahukan bahwa **Pendaftaran Magang** Anda **DITERIMA (ACCEPTED)** di Program Magang Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban 🎊!
@elseif($statusEnum === \App\Enums\RegistrationStatus::Rejected)
<x-mail::panel>
## ❌ Informasi Hasil Seleksi: Ditolak (Rejected)
</x-mail::panel>

Halo, **{{ $namaPeserta }}**

Kami menghargai minat dan usaha Anda mendaftar Program Magang di **Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**.
Dengan berat hati, kami memberitahukan bahwa pendaftaran magang Anda untuk sementara ini **BELUM DAPAT DITERIMA (REJECTED)**.
@else
<x-mail::panel>
## ℹ️ Status Pendaftaran Diperbarui: {{ $statusLabel }}
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 👋

Status pendaftaran magang Anda telah diperbarui oleh Tim Admin menjadi **{{ $statusLabel }}**.
@endif

Berikut ringkasan data pendaftaran Anda:

| Detail | Keterangan |
|---|---|
| **Kode Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Keputusan** | <span style="font-weight:700;color:{{ $statusEnum === \App\Enums\RegistrationStatus::Accepted ? '#198754' : ($statusEnum === \App\Enums\RegistrationStatus::Rejected ? '#dc3545' : '#0d6efd') }}">{{ $statusLabel }} {{ $statusEnum === \App\Enums\RegistrationStatus::Accepted ? '✅' : ($statusEnum === \App\Enums\RegistrationStatus::Rejected ? '❌' : '') }}</span> |
| **Tanggal Keputusan** | {{ now()->translatedFormat('l, d F Y H:i') }} WIB |

@if(!empty($catatanAdmin))
---
### 📝 Catatan dari Tim Admin
<blockquote style="padding:10px 14px;margin:10px 0;background:{{ $statusEnum === \App\Enums\RegistrationStatus::Accepted ? '#f0faf4' : '#fff5f5' }};border-left:4px solid {{ $statusEnum === \App\Enums\RegistrationStatus::Accepted ? '#198754' : '#dc3545' }};border-radius:4px;color:{{ $statusEnum === \App\Enums\RegistrationStatus::Accepted ? '#14532d' : '#7f1d1d' }};">
{!! nl2br(e($catatanAdmin)) !!}
</blockquote>
@endif

<x-mail::button :url="route('participant.registrations.show', $registration->id)" color="{{ $statusEnum === \App\Enums\RegistrationStatus::Accepted ? 'success' : 'primary' }}">
📄 Lihat Detail Pendaftaran di Dashboard
</x-mail::button>

---

@if($statusEnum === \App\Enums\RegistrationStatus::Accepted)
### 📄 Langkah Selanjutnya
1. Masuk ke **Dashboard Peserta** → **Riwayat Magang** untuk memantau unggahan **Surat Balasan** resmi dari Dinas.
2. Pelajari ketentuan dan jadwal pelaksanaan magang yang tertera pada dokumen Surat Balasan.
3. Siapkan kelengkapan berkas fisik pada hari pertama pelaksanaan magang.
@elseif($statusEnum === \App\Enums\RegistrationStatus::Rejected)
### 💡 Informasi Pendaftaran Kembali
Anda tetap **dapat mengajukan pendaftaran baru** untuk periode atau posisi magang lainnya di kesempatan mendatang.
Pastikan untuk melengkapi profil dan dokumen persyaratan sesuai masukan dari Tim Admin.
@endif

Terima kasih atas perhatian dan kerja sama Anda.

Salam hormat,  
**Tim SIM-MAGANG**  
**Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIM-MAGANG). Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
