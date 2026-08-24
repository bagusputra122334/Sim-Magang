<x-mail::message>
# 🏢 Dinas Komunikasi, Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## ❌ Informasi Hasil Seleksi: Ditolak (Rejected)
</x-mail::panel>

Halo, **{{ $namaPeserta }}**

Kami menghargai minat dan usaha Anda yang telah mendaftar Program Magang di **Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**.

Dengan berat hati, kami harus memberitahukan bahwa setelah melalui tahap review dan seleksi yang ketat, **pendaftaran magang Anda untuk sementara ini BELUM DAPAT DITERIMA (REJECTED)**.

Berikut ringkasan data pendaftaran Anda:

| Detail | Keterangan |
|---|---|
| **Kode Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Keputusan** | <span style="color:#dc3545;font-weight:700">{{ $statusLabel }} ❌</span> |
| **Tanggal Keputusan** | {{ now()->translatedFormat('l, d F Y H:i') }} WIB |

@if(!empty($catatanAdmin))
---
### 📝 Catatan / Alasan dari Tim Admin
<blockquote style="padding:12px 16px;margin:10px 0;background:#fff5f5;border-left:4px solid #dc3545;border-radius:4px;color:#7f1d1d;line-height:1.6;">
{!! nl2br(e($catatanAdmin)) !!}
</blockquote>
@endif

---

### 💡 Tips dan Saran untuk Kesempatan Mendatang
Anda **dapat mendaftar kembali** untuk periode / posisi magang berikutnya dengan memperbaiki hal-hal berikut:
1. **Perbaiki kualitas CV** — pastikan berisi pengalaman, project, dan kemampuan yang relevan dengan posisi yang dituju.
2. **Lengkapi Surat Pengantar** — gunakan kertas kop surat resmi instansi / universitas, cap basah, dan ditandatangani oleh pejabat berwenang.
3. **Lengkapi berkas dengan teliti** — pastikan seluruh dokumen persyaratan memenuhi format yang ditentukan.
4. **Perbaiki nilai / portfolio** — sesuaikan portfolio dengan kebutuhan posisi (misal: desain grafis, web developer, data entry, humas).

**Jangan menyerah!** 🌟 Banyak peserta sukses diterima setelah mencoba mendaftar 2-3x dengan perbaikan yang signifikan. Kami akan senantiasa menantikan pendaftaran Anda di kesempatan berikutnya.

<x-mail::button :url="route('participant.registrations.index')" color="primary">
🗂️ Lihat Riwayat Pendaftaran
</x-mail::button>

Terima kasih atas minat dan kepercayaan Anda kepada Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.

Salam hormat,
**Tim SIM-MAGANG**
**Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIM-MAGANG). Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
