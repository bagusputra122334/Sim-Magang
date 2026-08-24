<x-mail::message>
# 🏢 Dinas Komunikasi, Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## 📄 Surat Balasan Telah Tersedia untuk Diunduh
</x-mail::panel>

Halo, **{{ $namaPeserta }}** 👋

Kabar baik! **Surat Balasan resmi** untuk pendaftaran magang Anda **telah diunggah** oleh Tim Admin SIM-MAGANG dan **siap diunduh** melalui Dashboard Peserta.

### 📑 Ringkasan Pendaftaran
| Detail | Keterangan |
|---|---|
| **Kode Pendaftaran** | `{{ $kodePendaftaran }}` |
| **Nama Peserta** | {{ $namaPeserta }} |
| **Posisi Magang** | {{ $namaPosisi }} |
| **Periode Magang** | {{ $periodeLabel }} |
| **Status Saat Ini** | <span style="color:#198754;font-weight:700">{{ $statusLabel }} ✅</span> |

---

### 🏷️ Informasi Surat Balasan
| Detail | Keterangan |
|---|---|
| **Nama File** | `{{ $suratBalasanInfo['filename'] }}` |
@if(!empty($suratBalasanInfo['file_size']))| **Ukuran File** | {{ $suratBalasanInfo['file_size'] }} |
@endif| **Jenis File** | Portable Document Format (`.pdf`) |
| **Tanggal Upload** | {{ now()->translatedFormat('l, d F Y H:i') }} WIB |

<x-mail::button :url="$suratBalasanInfo['downloadRoute']" color="success">
⬇️ Download Surat Balasan (PDF) Sekarang
</x-mail::button>

---

### 📌 Setelah Mendownload:
1. **Simpan file PDF dengan baik** — ini merupakan dokumen resmi yang menjadi bukti diterima sebagai peserta magang.
2. **Baca seluruh isi surat** — perhatikan tanggal masuk pertama, lokasi kantor, dress code, kontak PIC, dan kelengkapan dokumen yang harus dibawa.
3. **Cetak 2 lembar (opsional)** — sebagian instansi mewajibkan peserta membawa hard-copy pada hari pertama.
4. **Konfirmasi kedatangan** — jika diminta pada surat, segera konfirmasi kepada narahubung yang tertera.

Jika tombol di atas tidak berfungsi, silakan akses secara manual melalui:
**Dashboard Peserta → Menu Riwayat Pendaftaran → Pilih `{{ $kodePendaftaran }}` → Tombol "Download Surat Balasan"**.

Selamat dan sukses menjalani program magang! 🏆

Terima kasih,
**Tim SIM-MAGANG**
**Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Email ini dikirim otomatis oleh Sistem Informasi Magang (SIM-MAGANG). Mohon tidak membalas email ini secara langsung.
</x-mail::subcopy>
</x-mail::message>
