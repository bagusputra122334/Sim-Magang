<x-mail::message>
# 🏢 Dinas Komunikasi dan Informatika, Statistik dan Persandian
### Kabupaten Tuban — Provinsi Jawa Timur

<x-mail::panel>
## 📩 Pesan Pertanyaan Baru dari Website SIMAGANG
</x-mail::panel>

Halo Administrator SIMAGANG,

Terdapat pesan pertanyaan / informasi baru yang dikirimkan oleh pengunjung melalui formulir kontak landing page:

| Informasi Pengirim | Data |
|---|---|
| **Nama Lengkap** | **{{ $name }}** |
| **Kategori** | {{ $categoryLabel }} |
| **Alamat Email** | [{{ $email }}](mailto:{{ $email }}) |
| **Nomor WhatsApp / Telp** | [{{ $phone }}](https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}) |
| **Waktu Pengiriman** | {{ $submittedAt }} |

---

### 💬 Isi Pesan:
<x-mail::panel>
{!! nl2br(e($messageContent)) !!}
</x-mail::panel>

---

<x-mail::button :url="'mailto:' . $email . '?subject=Re:%20[SIMAGANG]%20Tanggapan%20Informasi%20Magang'" color="primary">
✉️ Balas Pesan via Email
</x-mail::button>

Terima kasih,<br>
**Sistem Informasi Magang (SIMAGANG)**<br>
**Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban**

<x-mail::subcopy>
Pesan ini dikirimkan melalui Formulir Kontak Landing Page SIMAGANG. Anda dapat membalas email ini secara langsung ke alamat pengirim: {{ $email }}.
</x-mail::subcopy>
</x-mail::message>
