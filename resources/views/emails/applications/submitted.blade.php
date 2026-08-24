<x-mail::message>
# 🏢 Dinas Komunikasi, Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## 📋 Pendaftaran Magang Berhasil Dikirim!
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 👋

Terima kasih telah mendaftar Program Magang di **Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**.
Berikut adalah ringkasan data pendaftaran Anda:

| Detail | Keterangan |
|---|---|
| **Kode Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Terkini** | <span style="color:#0d6efd;font-weight:600">{{ $statusLabel }}</span> |
| **Tanggal Submit** | {{ $registration->tanggal_submit?->translatedFormat('l, d F Y H:i') ?? '-' }} |

<x-mail::button :url="route('participant.registrations.show', $registration->id)" color="primary">
📄 Lihat Detail Pendaftaran
</x-mail::button>

---

### 📌 Selanjutnya
Tim Admin **SIM-MAGANG** akan melakukan review berkas pendaftaran Anda secara berkala.
Silakan selalu cek **email** dan halaman **Riwayat Pendaftaran** di Dashboard Peserta untuk mendapatkan informasi perubahan status terbaru.

Jika Anda memiliki pertanyaan, silakan hubungi Admin melalui kontak yang tertera pada website resmi Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.

Terima kasih,
**Tim SIM-MAGANG**
**Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIM-MAGANG). Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
