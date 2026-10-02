# Spec: Fitur Absensi Harian Anak Bimbing (Pembimbing)

> Versi: 1.0 — Tanggal: 2026-10-02
> Bahasa: Indonesia
> Sumber Kebutuhan: User Prompt "PROMPT ANTI-GRAVITY: KOLOM ABSENSI & DETAIL REKAP HARIAN ANAK BIMBING"

---

## 1. Problem
Saat ini **Dashboard Pembimbing** (`/pembimbing/dashboard`) hanya menampilkan kolom No, Nama, Asal Instansi, dan Status untuk daftar anak bimbing. Pembimbing **belum bisa** memantau:
- Rekap absensi harian peserta per tanggal
- Uraian kegiatan / logbook harian yang di-submit peserta
- Jam submit / waktu absensi tiap hari
- Mengekspor laporan absensi & aktivitas peserta menjadi dokumen PDF formal

Ini menghambat pembimbing dalam melakukan pemantauan kehadiran harian anak magang secara cepat dan terstruktur.

## 2. Users & Goals
### Primary User
- **Pembimbing Dinas Komunikasi dan Informatika Kab. Tuban**
- Sudah melewati Middleware `auth` + `EnsureIsPembimbing` (route prefix `/pembimbing`, name `pembimbing.*`)

### Goals
1. **Kolom Absensi di Tabel Utama**: Dari tabel daftar anak bimbing, pembimbing bisa dengan cepat menuju halaman rekap absensi anak bimbing yang diinginkan dalam **1 klik**.
2. **Halaman Detail Rekap Absensi**: Menampilkan identitas peserta (Nama, Instansi, Jurusan, Periode Magang, Divisi) beserta **tabel rekap per hari** (Tanggal, Hari, Status Absensi, Uraian Kegiatan, Jam Submit).
3. **Ringkasan Statistik**: Menampilkan Total Hari Hadir (sudah absen), Total Hari Belum Absen (dalam periode), Total Hari Libur/Akhir Pekan.
4. **Ekspor PDF Profesional**: Satu klik menghasilkan dokumen PDF formal dengan:
   - Kop / Judul Instansi
   - Identitas Anak Magang
   - Tabel rekap absensi & uraian kegiatan
   - Kolom tanda tangan pembimbing + tanggal
5. **Non-Goals (tidak termasuk scope)**:
   - ❌ Mengubah logika submit absensi peserta (DailyCheckinController participant)
   - ❌ Mengubah struktur database / model / migrasi
   - ❌ Mengubah statistik utama pembimbing (card Total Penugasan, Total Anak Bimbing, Aktif, Nonaktif)
   - ❌ Mengubah filter / search / pagination tabel dashboard pembimbing yang sudah ada
   - ❌ Fitur edit / hapus data absensi oleh pembimbing (hanya baca + ekspor PDF)

---

## 3. Functional Requirements (FR)

| ID | Requirement |
|---|---|
| FR-1 | Tabel "Daftar Anak Bimbing" di Dashboard Pembimbing memiliki kolom baru **"ABSENSI"** (setelah kolom Status), yang berisi tombol aksi dengan ikon kalender/eye dan teks **"Rekap Absensi"** yang mengarah ke halaman detail. |
| FR-2 | Route baru `pembimbing.attendance.show` dengan path `/pembimbing/attendance/{registration}`: menerima ID Registration, memverifikasi bahwa Registration tersebut punya user dengan `division_id` SAMA dengan `division_id` pembimbing saat ini. Jika beda divisi → 403 Forbidden (Gunakan Policy / Gate / closure manual via auth check). |
| FR-3 | Halaman Detail Absensi menampilkan kartu identitas peserta (nama, NIM/email, instansi, jurusan, periode magang mulai-selesai, posisi, divisi). |
| FR-4 | Halaman Detail Absensi menampilkan 3 statistik ringkas: **Total Sudah Absen**, **Total Belum Absen**, **Total Hari Libur/Akhir Pekan** — dihitung berdasarkan periode magang peserta (`periode_mulai` s/d `periode_selesai`). |
| FR-5 | Tabel rekap harian ditampilkan **urut tanggal ASC** dengan kolom: No, Tanggal (dd/mm/yyyy), Hari, Status (Hijau="Hadir"/Abu-abu="Belum Absen"/Merah="Libur"), Uraian Kegiatan, Jam Submit. Batas data: hanya tanggal dalam periode magang. |
| FR-6 | Tombol **"Ekspor PDF"** muncul di halaman detail absensi (pojok kanan atas kartu identitas), membuka download PDF yang sama. |
| FR-7 | Route baru `pembimbing.attendance.pdf` dengan path `/pembimbing/attendance/{registration}/pdf`: verifikasi ownership divisi sama dengan FR-2, generate PDF formal (layout tanpa Tailwind, inline CSS agar bisa dirender PDF engine), response berupa `streamDownload` dengan nama file `Rekap-Absensi-{nama-peserta}-{bulan-tahun}.pdf` |
| FR-8 | Karena project `composer.json` TIDAK memiliki `dompdf/snappy`, maka PDF di-generate menggunakan **Laravel native**: Blade view PDF + `response()->streamDownload` dengan `mb_convert_encoding` atau (preferred) install `barryvdh/laravel-dompdf` via composer require. JIKA install library gagal/blocked network, fallback ke HTML-to-PDF murni menggunakan DomDocument + TCPDF wrapper TIDAK diizinkan. Gunakan opsi: install `barryvdh/laravel-dompdf` via composer; BILA tidak bisa install → fallback ke file PDF sederhana menggunakan blade `view()` dengan DOMPDF murni (manual require autoload). Namun PREFERENSI UTAMA: gunakan `maatwebsite/excel` yang SUDAH ADA di composer.json → BUKAN. PDF harus PDF, bukan Excel. Maka PREFERRED: install `barryvdh/laravel-dompdf`; JIKA network issue/timeout → fallback approach: buat file PDF terstruktur dengan `\PDF::` facade via **manual copy dompdf installation** TIDAK diizinkan. Solusi resmi: **Attempt composer require barryvdh/laravel-dompdf**. |
| FR-9 | Empty state: jika peserta belum ada data absensi sama sekali → tampilkan ilustrasi "Belum ada data absensi" beserta teks keterangan, TAPI statistik tetap muncul (Total Sudah Absen = 0 dst.). |

---

## 4. Non-Functional Requirements (NFR)

| ID | Requirement |
|---|---|
| NFR-1 | **Keamanan (Strict)**: Semua route absensi pembimbing berada di group middleware `['auth', 'pembimbing']` (sama seperti route pembimbing lain) + pengecekan DIVISI ownership di dalam controller. Pembimbing A divisi "Aplikasi" TIDAK BOLEH mengakses absensi Registration milik user divisi lain → HARUS mengembalikan HTTP 403. |
| NFR-2 | **Konsistensi UI**: Tailwind CSS utility classes, minimalis, compact. Tombol & card konsisten dengan desain Dashboard Pembimbing existing (`rounded-lg`, shadow tipis, gradient tidak berlebih, font-weight pada heading tidak melebihi fw-bold). |
| NFR-3 | **Performance (Eager Loading)**: Query detail absensi WAJIB `with(['user.profile', 'user.division', 'position'])` pada Registration, dan `DailyCheckin::where('user_id', X)->orderBy('date')` 1 query terpisah → maksimal 2-3 query per halaman, tidak ada N+1. |
| NFR-4 | **Backward Compatible**: Tidak merusak route `pembimbing.dashboard` existing. Tidak mengubah variabel `$statsTotalInterns`, `$statsActiveInterns`, `$totalPenugasan` di DashboardController. |
| NFR-5 | **Kode Standar Laravel**: Controller di `App\Http\Controllers\Pembimbing\`. Model menggunakan Route Model Binding dengan `resolveRouteBinding` default. Tidak ada query builder mentah SELECT di Blade. |
| NFR-6 | **Ukuran file PDF < 500KB**: Optimasi gambar tidak perlu (tidak wajib ada gambar), konten berupa teks dan tabel saja. |

---

## 5. Constraints, Dependencies, Assumptions, Open Questions

### Constraints (Mutlak)
1. **TIDAK BOLEH** mengubah migrasi / struktur tabel.
2. **TIDAK BOLEH** mengubah enum, middleware, atau model existing kecuali menambah relasi *read-only* baru (jika perlu).
3. **TIDAK BOLEH** merusak statistik utama dashboard pembimbing.
4. **TIDAK BOLEH** melewati pembatasan divisi (harus selalu `division_id` match).
5. **Wajib** menggunakan pola Laravel existing: `resources/views/pembimbing/` untuk view, `Pembimbing\` namespace untuk controller, route group `pembimbing.*` di `routes/web.php`.

### Dependencies
1. ✅ `maatwebsite/excel 3.1.69` tersedia (untuk referensi, tidak dipakai untuk PDF).
2. ⚠️ **TIDAK ADA PDF library** di composer.json saat ini. Pilihan:
   - **Plan A**: `composer require barryvdh/laravel-dompdf:^3.1` (preferred, resmi Laravel).
   - **Plan B**: Jika network tidak mengizinkan install, fallback ke **Blade render + Content-Type application/pdf**. Namun Plan B tidak akan menghasilkan file PDF valid di browser modern. Maka Plan A WAJIB dicoba. Bila Plan A GAGAL (exit code != 0), maka fallback ke **Approach C**: Generate laporan berbentuk **HTML Printable** dengan tambahan tombol "Cetak / Simpan PDF" yang memicu `window.print()`. Ini memenuhi semangat "dokumen formal" dan user bisa "Save as PDF" melalui dialog browser. **Approach C hanya digunakan jika composer install barryvdh/laravel-dompdf GAGAL total.**

### Assumptions
- Satu `User` (peserta) diasumsikan **satu Registration aktif** dalam satu waktu. Route binding `{registration}` akan me-load satu Registration.
- Data `DailyCheckin.user_id` === `Registration.user_id`, karena check-in dibuat oleh user peserta bersangkutan.
- Libur = Sabtu (dow=6) + Minggu (dow=0) sesuai logika UI kalender absensi sebelumnya. Hari libur nasional TIDAK dihitung (tidak ada data holiday table).
- NIM peserta: diambil dari `Profile->jurusan?` / `Profile->institusi?`; jika tidak ada, fallback ke email.

### Open Questions
Tidak ada pertanyaan terbuka. Semua requirement sudah cukup jelas.

---

## 6. Acceptance Criteria (AC)

### AC Tipe `rule` (Binary Pass/Fail)
| ID | Rule | Evidence |
|---|---|---|
| AC-R1 | Di tabel "Daftar Anak Bimbing" (Dashboard Pembimbing), **sekarang ada 5 kolom thead**: No, Nama, Asal Instansi, Status, **Absensi**. Kolom terakhir berisi tombol `<a>` dengan teks yang mengandung kata "Rekap Absensi". | Buka `resources/views/pembimbing/dashboard.blade.php`, `<thead>` row terlihat memiliki 5 `<th>`; `<tbody>` setiap row memiliki tombol `<a class"...">Rekap Absensi</a>` yang href ke route `pembimbing.attendance.show` dengan parameter `$intern`. |
| AC-R2 | Route list (`php artisan route:list --name=pembimbing.attendance`) menampilkan **2 route baru**: `pembimbing.attendance.show` (GET) dan `pembimbing.attendance.pdf` (GET), keduanya di group middleware `auth,pembimbing`. | Jalankan `php artisan route:list --name=pembimbing.attendance` di terminal; 2 route terdaftar. |
| AC-R3 | Controller `AttendanceController` di namespace `Pembimbing` memiliki 2 public method: `show(Registration $registration)` dan `exportPdf(Registration $registration)`. | Buka file controller; 2 method public ada, menggunakan Route Model Binding type hint `Registration`. |
| AC-R4 | **Authorization**: Jika pembimbing login dengan `division_id=1` lalu mencoba mengakses route attendance dengan Registration yang user-nya punya `division_id=2` → response HTTP **403**. | Test secara manual atau buat tinker set user division beda. Bukti: di controller method, sebelum render view, ada blok `abort_unless($registration->user->division_id === Auth::user()->division_id, 403)` atau setara Policy. |
| AC-R5 | Halaman detail absensi menampilkan section **Statistik**: minimal 3 kartu dengan label "Sudah Absen", "Belum Absen", "Libur/Akhir Pekan". | Buka halaman di browser; 3 kartu statistik terlihat dengan nilai integer (bukan null/kosong). |
| AC-R6 | Tabel rekap harian memiliki 6 kolom: No, Tanggal, Hari, Status, Uraian Kegiatan, Jam Submit. Diurutkan ASC berdasarkan tanggal. | `<thead>` pada tabel terlihat 6 `<th>`; baris `<tr>` tidak berurutan DESC. |
| AC-R7 | Tombol "Ekspor PDF" / "Cetak Laporan" ada di halaman detail absensi (paling tidak satu elemen `<a>` / `<button>` dengan teks yang mengandung kata "PDF" atau "Cetak"). | Buka halaman; elemen terlihat, href menuju route PDF atau onclick menjalankan window.print(). |
| AC-R8 | **Plan A Success Condition**: `composer show barryvdh/laravel-dompdf` exit code 0 → PDF di-download dengan Content-Type `application/pdf` dan filename berawalan `Rekap-Absensi-`. | Jalankan command; unduh file; Content-Type = application/pdf. |
| AC-R9 | **Plan C (Fallback PDF-Not-Installable)**: Jika composer require gagal → tombol bertuliskan "Cetak / Simpan PDF" memanggil `window.print()` dan view PDF printable (layout A4 potrait) tersedia sebagai file Blade terpisah. | Ada elemen onclick window.print() atau `<a href="javascript:window.print()">`; layout print terdefinisi dengan @media print. |
| AC-R10 | 4 card statistik utama Dashboard Pembimbing **masih berfungsi**: Total Penugasan, Total Anak Bimbing, Aktif, Nonaktif. `compact()` di `DashboardController@index` TIDAK DIHAPUS. | `DashboardController@index` masih mengembalikan view dengan variabel `$totalPenugasan`, `$statsTotalInterns`, `$statsActiveInterns`, `$statsInactiveInterns`. |
| AC-R11 | Tidak ada error lint / sintaks: `php artisan view:cache` & `php artisan route:cache` exit code 0. | Jalankan kedua command; keduanya success. |

### AC Tipe `rubric` (Evaluative, 0-2)
| ID | Rubric | Pass Threshold | Evidence |
|---|---|---|---|
| AC-U1 | **UI Konsistensi**: Layout halaman detail absensi konsisten dengan desain Dashboard Pembimbing (warna card, border radius, tombol, typo, padding card-body tidak melebihi p-3/p-4). | Skor ≥ 1 | Screenshot halaman / inspeksi DOM. |
| AC-U2 | **Desain PDF Formalitas**: Kop surat jelas ("DINAS KOMUNIKASI DAN INFORMATIKA KABUPATEN TUBAN" atau sesuai project setting), identitas peserta rapi, tabel garis penuh, **kolom tanda tangan di kanan bawah** beserta nama dan tanggal. | Skor ≥ 1 | Buka PDF; bagian tersebut ada. |
| AC-S1 | **Security**: Tidak ada celah IDOR. Parameter `{registration}` BISA diganti user jahat, tapi server selalu memverifikasi division ownership. | Skor = 2 | Bukti: closure abort_unless di setiap method controller. |
| AC-P1 | **Performance**: Eager loading terlihat di query controller (with / whereHas). Total query per request < 10 (dicek via Laravel Debugbar / DB::getQueryLog()). | Skor ≥ 1 | Controller menggunakan `with()` pada relasi. |
| AC-M1 | **Maintainability**: Penamaan controller / route konsisten, tidak ada "magic string" route URL hardcoded (harus pakai `route()` helper). | Skor = 2 | Semua `href` menggunakan `route('pembimbing.attendance.show', $intern)` atau setara. |

---

## 7. Cakupan yang Harus Ada di Review Gate
1. Security check: authorization division match?
2. UI consistency: card, button, padding tidak merusak layout existing?
3. Functional check: buka dashboard → klik "Rekap Absensi" → halaman detail muncul dengan statistik dan tabel → klik Ekspor PDF / Cetak → berhasil.
4. Backward compatibility: dashboard stats masih ada, filter dashboard masih jalan, pagination masih jalan.
