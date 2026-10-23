<x-mail::message>
# 🏢 Dinas Komunikasi dan Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## 🎓 @if($isReplacing) Pembaruan Sertifikat Kelulusan Magang Digital @else SELAMAT! Sertifikat Kelulusan Magang Digital Telah Diterbitkan @endif
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 👋🎓

Selamat! Masa pelaksanaan program magang Anda di **Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban** telah resmi selesai dengan baik.

Sebagai bentuk penghargaan dan bukti kelulusan resmi, kami memberitahukan bahwa **Sertifikat Kelulusan Magang Digital** Anda {{ $isReplacing ? 'telah diperbarui dengan revisi/versi terbaru' : 'telah resmi diterbitkan' }} oleh Admin Diskominfo Tuban. Dokumen digital ini siap untuk Anda akses dan unduh secara langsung melalui dashboard akun SIMAGANG Anda.

### 📑 Rincian Kelulusan & Sertifikat
| Detail | Keterangan |
|---|---|
| **Nomor Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Asal Institusi** | {{ $institusi }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Magang** | <span style="color:#059669;font-weight:700">Selesai Magang (Completed) 🎓</span> |
| **Status Dokumen** | <span style="color:#059669;font-weight:700">{{ $isReplacing ? 'Telah Diperbarui (Versi Terbaru)' : 'Telah Terbit Resmi' }} ✅</span> |
@if(!empty($fileSize))| **Ukuran Dokumen** | {{ $fileSize }} |
@endif| **Format Dokumen** | Portable Document Format (`.pdf`) |
| **Waktu Terbit** | {{ $issuedAt }} |

<x-mail::button :url="$downloadUrl" color="success">
⬇️ Unduh Sertifikat Kelulusan (PDF)
</x-mail::button>

<x-mail::button :url="$dashboardUrl" color="primary">
🏠 Buka Dashboard Akun Peserta
</x-mail::button>

---

### 📌 Informasi & Panduan Sertifikat:
1. **Dokumen Sah & Resmi** — Sertifikat ini merupakan dokumen digital resmi yang diterbitkan oleh Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban sebagai bukti sah penyelesaian program magang.
2. **Unduh & Simpan Arsip** — Anda disarankan mengunduh file PDF ini dan menyimpannya di tempat yang aman (misalnya Google Drive pribadi atau perangkat Anda) untuk kebutuhan portofolio, SKPI, atau lampiran lamaran kerja.
3. **Dapat Diunduh Kapan Saja** — Anda tetap dapat mengunduh ulang sertifikat ini sewaktu-waktu melalui akun Anda di Dashboard SIMAGANG.
4. **Pencetakan Mandiri** — Dokumen dibuat dalam format PDF resolusi tinggi dan siap dicetak secara mandiri jika diperlukan oleh instansi pendidikan Anda.

Jika tombol di atas tidak berfungsi, silakan akses melalui:
**Dashboard Peserta → Menu Riwayat / Status Magang → Tombol "Download Sertifikat"**.

Terima kasih atas kerja keras, integritas, dan dedikasi luar biasa yang telah Anda berikan selama menjalani masa magang di Diskominfo SP Kabupaten Tuban. Semoga ilmu, wawasan, dan pengalaman yang didapatkan menjadi bekal berharga untuk meraih kesuksesan di masa depan! 🌟

Salam hangat,  
**Tim Pembina Magang**  
**Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIMAGANG) Diskominfo Kabupaten Tuban melalui layanan Resend. Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
