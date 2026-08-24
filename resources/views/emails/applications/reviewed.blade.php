<x-mail::message>
# 🏢 Dinas Komunikasi, Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## 🔍 Status Pendaftaran: Under Review (Sedang Diverifikasi)
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 👋

Kami ingin memberitahukan bahwa pendaftaran magang Anda dengan detail berikut **sedang dalam tahap review / verifikasi** oleh Tim Admin SIM-MAGANG:

| Detail | Keterangan |
|---|---|
| **Kode Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Terkini** | <span style="color:#ffc107;font-weight:700">{{ $statusLabel }}</span> |
| **Mulai Diverifikasi** | {{ now()->translatedFormat('l, d F Y H:i') }} WIB |

<x-mail::button :url="route('participant.registrations.show', $registration->id)" color="success">
🔍 Pantau Status Pendaftaran
</x-mail::button>

---

### 📌 Catatan
Proses verifikasi biasanya memakan waktu **1 - 3 hari kerja** tergantung antrian pendaftaran.
Anda akan menerima **email notifikasi otomatis** segera setelah Tim Admin mengambil keputusan akhir (Accepted / Rejected).

Harap tenang menunggu dan pastikan selalu memantau inbox email (termasuk folder **Promosi / Spam**) secara berkala.

Terima kasih atas kesabaran Anda,
**Tim SIM-MAGANG**
**Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIM-MAGANG). Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
