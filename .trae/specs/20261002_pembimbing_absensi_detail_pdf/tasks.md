# Tasks: Fitur Absensi Harian Anak Bimbing (Pembimbing)

> Terhubung dengan: `spec.md` (2026-10-02)
> Workflow: Spec → Plan → Approve → Implement → Review
> Aturan penulisan: Setiap task memetakan ke Acceptance Criterion (AC) dan memiliki Test Requirements (TR) sendiri.

---

## Overview Task Dependency Graph
```
Task 1 (Route + Controller Skeleton + Auth)
  ↓ (prasyarat)
Task 2 (Tambah kolom Absensi ke tabel Dashboard Pembimbing)
  ↓ (tugas independent, bisa parallel dengan Task 3-4)
Task 3 (View Halaman Detail Absensi + Statistik + Tabel Rekap)
  ↓
Task 4 (Coba install barryvdh/laravel-dompdf: Plan A)
    ├─ Sukses → Task 5A (Implement PDF via PDF facade + Blade PDF)
    └─ Gagal  → Task 5B (Fallback Printable HTML + window.print())
  ↓
Task 6 (Verifikasi akhir: route:cache, view:cache, diagnostics, backward compatibility check)
```

---

## Task 1: Buat Controller & Daftarkan 2 Route Baru
**Status: pending**
**Priority: high**
**Maps ke AC**: AC-R2, AC-R3, AC-R4, AC-S1, AC-M1

### Deskripsi
Buat file controller baru dan daftarkan route di group `pembimbing.*`. Tambahkan authorization divisi check.

### Output File
- `app/Http/Controllers/Pembimbing/AttendanceController.php` (NEW)
- Edit `routes/web.php` (MODIFY) — tambahkan route di group pembimbing sebelum penutupan grup.

### Checklist Implementasi
- [ ] Controller namespace `App\Http\Controllers\Pembimbing\AttendanceController`.
- [ ] Import: `use App\Models\Registration;`, `use App\Models\DailyCheckin;`, `use Illuminate\Http\Request;`, `use Illuminate\Support\Facades\Auth;`, `use Illuminate\Support\Carbon;`.
- [ ] Method `public function show(Registration $registration)`:
  - [ ] `abort_unless($registration->relationLoaded('user') || true, ...)` → load relasi `$registration->loadMissing(['user.profile', 'user.division', 'position'])`.
  - [ ] **AUTH CHECK:** `abort_unless(optional($registration->user)->division_id === Auth::user()->division_id, 403);`
  - [ ] Hitung periode: `$start = $registration->periode_mulai?->copy()->startOfDay() ?? now()->startOfMonth();` `$end = $registration->periode_selesai?->copy()->endOfDay() ?? now()->endOfMonth();` pastikan `$end >= $start`, jika tidak swap / set default 1 bulan.
  - [ ] Query `DailyCheckin::where('user_id', $registration->user_id) ->whereBetween('date', [$start->toDateString(), $end->toDateString()]) ->orderBy('date', 'asc')->get();`
  - [ ] Hitung statistik:
    - `$totalPeriodDays = $start->diffInDays($end) + 1;` (tanggal inclusive)
    - `$totalWeekendDays` = loop dari `$start` sampai `$end`, `$d->isWeekend()` ? 1 : 0.
    - `$totalHadir = $checkins->count();`
    - `$totalExpectedWorkDays = $totalPeriodDays - $totalWeekendDays;`
    - `$totalBelum = max(0, $totalExpectedWorkDays - $totalHadir);`
  - [ ] Return `view('pembimbing.attendance.show', compact('registration', 'checkins', 'totalPeriodDays', 'totalWeekendDays', 'totalHadir', 'totalBelum', 'start', 'end'));`
- [ ] Method `public function exportPdf(Registration $registration)` — **isikan sementara abort(501, 'Implement Task 5A / 5B')** (placeholder, akan diisi di Task 5).
- [ ] Di `routes/web.php` dalam group `pembimbing.*` TAMBAHKAN 2 route BARIS SETELAH route `submissions.review` (sebelum `});` penutup group):
  ```php
  Route::get('/attendance/{registration}/pdf', [\App\Http\Controllers\Pembimbing\AttendanceController::class, 'exportPdf'])->name('attendance.pdf');
  Route::get('/attendance/{registration}', [\App\Http\Controllers\Pembimbing\AttendanceController::class, 'show'])->name('attendance.show');
  ```
  **Urutan penting: route /pdf harus di ATAS route show agar parameter {registration} tidak menagkap kata "pdf".**

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T1-TR1 | rule | `php artisan route:list --name=pembimbing.attendance` menunjukkan 2 route | 2 route muncul. |
| T1-TR2 | rule | `AttendanceController` memiliki 2 public method non-static: `show` dan `exportPdf` | Buka file. |
| T1-TR3 | rule | Di method `show` ada `abort_unless(... division_id ..., 403)` | Ada statement tersebut dengan kondisi benar. |
| T1-TR4 | rubric | Code style: PSR-12, indent konsisten, eager loading pakai `loadMissing` | Skor ≥ 1. |

---

## Task 2: Tambahkan Kolom "Absensi" ke Tabel Daftar Anak Bimbing
**Status: pending**
**Priority: high**
**Maps ke AC**: AC-R1, AC-M1

### Deskripsi
Tambahkan `<th>` baru bernama ABSENSI pada `<thead>` dan `<td>` action button pada `<tbody>` di Dashboard Pembimbing Blade. Sesuaikan empty-state colspan dan width.

### Output File
- `resources/views/pembimbing/dashboard.blade.php` (MODIFY)

### Checklist Implementasi
- [ ] `<thead>` baris L188-L193: tambahkan `<th class="text-center" style="width: 170px;">Absensi</th>` menjadi kolom ke-5 (setelah Status).
- [ ] `<tbody>` per row: SETELAH `<td>` Status (L239-L248), tambahkan `<td class="text-center">` dengan anchor:
  ```blade
  <td class="text-center">
      <a href="{{ route('pembimbing.attendance.show', $intern) }}"
         class="btn btn-sm d-inline-flex align-items-center justify-content-center gap-1.5 fw-semibold"
         style="background-color: #2563eb !important; color: #ffffff !important; border-radius: 0.5rem; min-width: 112px; font-size: 0.75rem;">
          <i class="bi bi-calendar2-check" style="font-size: 0.85rem;"></i>
          Rekap Absensi
      </a>
  </td>
  ```
- [ ] Empty state row `<td colspan="4" ...>` (L252): ubah menjadi **colspan="5"** agar merentang semua kolom (karena sekarang 5 kolom).
- [ ] Pastikan `$intern` variabel `@forelse($interns as $intern)` → link menerima object `$intern` bertipe Registration (benar karena $interns adalah Paginator dari Registration).

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T2-TR1 | rule | Thead `<tr>` memiliki 5 `<th>` dengan urutan No, Nama, Asal Instansi, Status, Absensi | Buka source blade. |
| T2-TR2 | rule | Setiap `<tr>` body punya 5 `<td>`, kolom terakhir adalah anchor `href` ke route `pembimbing.attendance.show` | Ada pemanggilan `route('pembimbing.attendance.show', $intern)`. |
| T2-TR3 | rule | Empty state `<td colspan=...>` nilainya **5** | Ubah dari 4 menjadi 5. |
| T2-TR4 | rubric | Tombol absensi style compact, konsisten dengan tombol Hapus Filter di empty state (warna biru #2563eb, rounded, fw-semibold) | Skor ≥ 1. |

---

## Task 3: Buat View Detail Absensi (Blade) + Statistik + Tabel Rekap
**Status: pending**
**Priority: high**
**Maps ke AC**: AC-R5, AC-R6, AC-R7, AC-R9, AC-U1, NFR-2, NFR-3

### Deskripsi
Buat folder baru `resources/views/pembimbing/attendance/` dan file `show.blade.php` yang menampilkan:
1. Breadcrumb kembali ke Dashboard
2. Header Judul + Tombol Ekspor PDF (placeholder Task 5)
3. Kartu Identitas Peserta (2 kolom grid: Kiri biodata, Kanan periode + divisi)
4. 3 Kartu Statistik (Sudah Absen / Belum Absen / Libur Akhir Pekan)
5. Tabel Rekap Harian 6 kolom dengan looping tanggal dari start ke end
6. Empty state

### Output File
- `resources/views/pembimbing/attendance/show.blade.php` (NEW)
- Opsional: `resources/views/pembimbing/attendance/print.blade.php` (NEW — Task 5B, bila diperlukan)

### Checklist Implementasi
- [ ] Extends `layouts.app`, `@section('title', 'Rekap Absensi - '.$registration->user->name)`
- [ ] Content padding-top minimal: `<div class="container-fluid" style="padding-top: 0.25rem !important;">`
- [ ] Header row `d-flex justify-content-between align-items-center` → kiri `<h1 class="h4 mb-0">Rekap Absensi Harian</h1>` + breadcrumb ke Dashboard, kanan 2 tombol:
  - Tombol Kembali: `<a href="{{ route('pembimbing.dashboard') }}" class="btn btn-sm btn-outline-secondary">← Kembali</a>`
  - Tombol Ekspor PDF: **placeholder** dengan class `btn btn-sm btn-primary` dengan text `... PDF` (Task 5 akan isi href).
- [ ] Section Identitas Peserta: `card shadow-sm` dengan `card-body p-3`, grid `row g-3`, 2 kolom:
  - Kol-6 kiri: Nama (bold), Email, Jurusan (jika ada), Instansi, Posisi (Position)
  - Kol-6 kanan: Divisi, Periode Magang (Mulai dd MMM yyyy — Selesai dd MMM yyyy)
- [ ] Section Statistik: `row g-2`, 3 column `col-md-4`:
  - Card 1: border-left emerald `#10b981` → badge text Sudah Absen, value `$totalHadir`
  - Card 2: border-left slate 500 → Belum Absen, value `$totalBelum`
  - Card 3: border-left red `#ef4444` → Libur/Akhir Pekan, value `$totalWeekendDays`
- [ ] Section Tabel Rekap:
  - Judul `<h6 class="fw-bold mb-2 mt-3">Rekap Harian ({{ $start->translatedFormat('d M Y') }} — {{ $end->translatedFormat('d M Y') }})</h6>`
  - Thead 6 kolom: No, Tanggal, Hari, Status, Uraian Kegiatan, Jam Submit
  - Tbody loop: Buat period object `$period = new \DatePeriod($start, new \DateInterval('P1D'), $end->copy()->addDay());` di @php top of view.
  - Nomor increment $no = 1
  - For each `$date` in $period:
    - Cari `$checkin = $checkins->firstWhere(fn($c) => $c->date->isSameDay($date));`
    - Tanggal kolom: `$date->translatedFormat('d/m/Y')`
    - Hari kolom: `$date->translatedFormat('l')` (locale id_ID)
    - Status badge:
      - Jika `$date->isWeekend()` → `<span class="badge bg-red-500 text-white" style="background-color:#ef4444 !important;">Libur</span>`
      - Else jika ada `$checkin` → `<span class="badge" style="background-color:#10b981 !important; color:#fff;">Hadir</span>`
      - Else → `<span class="badge bg-gray-200 text-gray-700" style="background-color:#e5e7eb !important; color:#374151 !important;">Belum Absen</span>`
    - Uraian kegiatan: `$checkin?->activity ? e($checkin->activity) : '<span class="text-muted fst-italic small">-</span>'` (tidak menggunakan `{!! !!}` diikuti variable non-escaped, GUNAKAN `{{ $checkin?->activity }}` bila ada; else `<td><span class="text-muted">-</span></td>`)
    - Jam Submit: `$checkin?->submitted_at?->translatedFormat('H:i:s') ?? '<span class="text-muted">-</span>'`
    - Increment $no++
- [ ] Empty state: Jika `$totalHadir === 0 && $totalPeriodDays > 0` → di bawah card statistik tampilkan alert info ringkas "Belum ada data absensi yang di-submit peserta pada periode ini." TAPI **TABEL TETAP DITAMPILKAN** (tanggal tetap muncul, status = Belum Absen / Libur sesuai).

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T3-TR1 | rule | View file `resources/views/pembimbing/attendance/show.blade.php` ada | File ada. |
| T3-TR2 | rule | Terdapat 3 card dengan label: "Sudah Absen", "Belum Absen", "Libur/Akhir Pekan". | Text muncul di source. |
| T3-TR3 | rule | Tabel `<thead>` punya 6 kolom `<th>`: No, Tanggal, Hari, Status, Uraian Kegiatan, Jam Submit. | 6 `<th>` muncul. |
| T3-TR4 | rule | Looping tanggal berjalan dari `$start` ke `$end` inclusive; setidaknya satu row per tanggal. | Source pakai `@foreach($period as $date)`. |
| T3-TR5 | rubric | UI consistency: padding card `p-3` max, font ringkas, gap kecil, warna border-left stats konsisten dashboard. | Skor ≥ 1. |

---

## Task 4: Attempt Install PDF Library (barryvdh/laravel-dompdf)
**Status: pending**
**Priority: high**
**Maps ke AC**: AC-R8, FR-8

### Deskripsi
Coba install `barryvdh/laravel-dompdf` secara resmi. Jika berhasil lanjut ke Task 5A. Jika gagal (timeout/network error/exit code != 0), masuk ke Task 5B (fallback printable HTML + window.print).

### Output File
- `composer.json` (bisa MODIFY jika install sukses)
- `composer.lock` (bisa MODIFY jika install sukses)

### Checklist Implementasi
- [ ] Jalankan `composer require barryvdh/laravel-dompdf:^3.1 --no-interaction` via terminal.
- [ ] CATAT exit code.
- [ ] Jika exit code == 0 → publish vendor config opsional (`php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"`). Tidak wajib publish config.
- [ ] Jika exit code != 0 → catat error, BATALKAN ubah composer.json (jika ada perubahan), lanjut Task 5B.

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T4-TR1 | rule | `composer show barryvdh/laravel-dompdf` exit 0 | Task 5A bisa lanjut. |
| T4-TR2 | rule | Bila T4-TR1 gagal, fallback ke Task 5B dengan jelas. | Terdapat decision log. |

---

## Task 5A: Implement Export PDF via DomPDF (Plan A Success)
**Status: pending**
**Priority: high**
**Depends on**: Task 4 SUCCESS (T4-TR1 pass)
**Maps ke AC**: AC-R7, AC-R8, AC-U2, NFR-6

### Deskripsi
Buat Blade view untuk PDF dan isi method `exportPdf()` di AttendanceController menggunakan Facade `PDF::loadView()`.

### Output File
- `app/Http/Controllers/Pembimbing/AttendanceController.php` (MODIFY — method `exportPdf`)
- `resources/views/pembimbing/attendance/pdf.blade.php` (NEW)

### Checklist Implementasi
- [ ] Di `pdf.blade.php` gunakan **murni inline CSS** (bukan Tailwind) karena DomPDF support terbatas.
  - [ ] Header Kop:
    - Baris 1: **DINAS KOMUNIKASI DAN INFORMATIKA KABUPATEN TUBAN** (bold, center, font-size 14pt)
    - Baris 2: Jl. {...} / bisa di-set "Alamat: Jl. Pemuda No. 1, Tuban" (center, ukuran 9pt)
    - Garis bawah `<hr style="border:1px solid #000;">`
  - [ ] Judul: **LAPORAN REKAP ABSENSI & AKTIVITAS HARIAN PESERTA MAGANG** (bold, center, 12pt)
  - [ ] Section Identitas Peserta: 2 kolom table tanpa border, label bold di kanan nilainya. Field: Nama, NIM/Email, Instansi/Universitas, Jurusan, Posisi, Divisi, Periode Magang.
  - [ ] Section Tabel Rekap (border full):
    - 6 kolom: No, Tanggal, Hari, Status, Uraian Kegiatan, Jam Submit
    - Setiap cell ada padding 4px, font-size 9pt
    - Garis border solid hitam 1px penuh (thead & tbody)
    - Row isi: status badge ganti jadi text biasa dengan warna = background cell (misal Hadir = `bgcolor="#d1fae5"`, Libur = `bgcolor="#fee2e2"`, Belum = `bgcolor="#f3f4f6"`)
  - [ ] Tanda Tangan: Kanan bawah, 3 baris:
    - `Tuban, {{ $end->translatedFormat('d F Y') }}`
    - `Mengetahui,` / `Pembimbing Magang,`
    - (spasi 4 baris untuk tanda tangan basah)
    - `_______________________________`
    - `{{ Auth::user()->name }}`
    - `NIP. {{ Auth::user()->nip ?? '-' }}`
- [ ] Controller `exportPdf()`:
  - [ ] Auth check sama dengan `show()` (abort_unless divisi match, 403).
  - [ ] Load data sama seperti show() (hitung total days, checkins query, eager loading).
  - [ ] `$pdf = \PDF::loadView('pembimbing.attendance.pdf', compact(...))->setPaper('a4', 'portrait');`
  - [ ] `$filename = 'Rekap-Absensi-' . \Str::slug($registration->user->name) . '-' . $start->format('Ymd') . '-' . $end->format('Ymd') . '.pdf';`
  - [ ] Return `$pdf->download($filename);` atau `stream($filename)` — lebih baik `download()` agar file diunduh otomatis.
- [ ] Update tombol PDF di view detail show.blade.php → href = `{{ route('pembimbing.attendance.pdf', $registration) }}`.

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T5A-TR1 | rule | Method `exportPdf()` di controller mengembalikan response `Symfony\Component\HttpFoundation\BinaryFileResponse` atau `\Barryvdh\DomPDF\Facade\Pdf::download()` return type. | Bisa buka route PDF. |
| T5A-TR2 | rule | `response headers Content-Type: application/pdf` dan `Content-Disposition: attachment; filename=Rekap-Absensi-...pdf` | Cek via browser DevTools Network. |
| T5A-TR3 | rubric | Desain PDF: kop jelas, identitas 2 kolom, tabel border penuh, tanda tangan kolom kanan bawah ada nama pembimbing + NIP + tanggal. | Skor ≥ 1. |

---

## Task 5B: Fallback Printable PDF (jika Task 4 GAGAL)
**Status: pending**
**Priority: high**
**Depends on**: Task 4 FAIL (T4-TR1 fail)
**Maps ke AC**: AC-R7, AC-R9, AC-U2

### Deskripsi
Tambahkan tombol "Cetak / Simpan PDF" yang memanggil `window.print()`; buat layout media print yang rapi (sembunyikan navbar, sidebar, tombol; layout A4 potrait; tabel garis penuh).

### Output File
- `resources/views/pembimbing/attendance/show.blade.php` (MODIFY — tambah @media print CSS & tombol)

### Checklist Implementasi
- [ ] Tambahkan `<style>` section di `@push('styles')` atau inline:
  ```css
  @media print {
    body * { visibility: hidden; }
    #print-area, #print-area * { visibility: visible; }
    #print-area { position: absolute; left: 0; top: 0; width: 100%; }
    .no-print { display: none !important; }
    @page { size: A4 portrait; margin: 20mm 15mm; }
    table { border-collapse: collapse; width: 100%; }
    table, th, td { border: 1px solid #000 !important; }
    th, td { padding: 4px; font-size: 10pt; }
  }
  ```
- [ ] Bungkus seluruh konten halaman (kecuali navbar/sidebar) dengan `<div id="print-area">` (layouts.app biasanya sudah punya main-content terpisah).
- [ ] Tombol kanan header pada kolom action: ganti href PDF dengan `<button onclick="window.print()" class="btn btn-sm btn-danger"> <i class="bi bi-filetype-pdf"></i> Cetak / Simpan PDF</button>`. Tambahkan class `no-print` pada tombol-tombol action (simpan pdf, kembali) agar tidak muncul di hasil print.
- [ ] Di atas card statistik, tambahkan kop surat sederhana HANYA untuk area print (pakai class `.print-only { display: none; } @media print { .print-only { display: block; } }`) — kop Dinas Kominfo Tuban, alamat, garis, judul.
- [ ] Section tanda tangan di bawah tabel (print-only): sama seperti Task 5A bagian tanda tangan.
- [ ] Di controller `exportPdf()`: karena dompdf tidak terinstall, ganti redirect ke show page dengan flash message info. **TAPI LEBIH BAIK** langsung redirect ke URL show page dengan fragment `?print=1` dan auto trigger print via JS jika `?print=1` ada. Namun untuk menghindari redirect loop, cukup **kosongi method exportPdf() dan ganti dengan `return back()->with('info', 'Fitur PDF membutuhkan browser print. Silakan klik "Cetak / Simpan PDF".');`** — dan route `attendance.pdf` tetap terdaftar agar AC-R2 pass.

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T5B-TR1 | rule | Tombol "Cetak / Simpan PDF" ada dengan onclick `window.print()`. | Source ada. |
| T5B-TR2 | rule | Ada `@media print` rules pada `<style>` / style section, yang menyembunyikan sidebar/navbar, hanya menampilkan area print, set ukuran A4. | Source ada. |
| T5B-TR3 | rubric | Print preview di Chrome menampilkan kop surat, identitas, tabel penuh border, tanda tangan. Tampilan formal. | Skor ≥ 1. |

---

## Task 6: Verifikasi Akhir & Backward Compatibility
**Status: pending**
**Priority: high**
**Depends on**: All previous tasks (1, 2, 3, [5A or 5B])
**Maps ke AC**: AC-R10, AC-R11, NFR-4

### Deskripsi
Jalankan semua command verifikasi dan periksa backward compatibility.

### Checklist Implementasi
- [ ] Jalankan `php artisan route:cache`. Exit code harus 0.
- [ ] Jalankan `php artisan view:cache`. Exit code harus 0.
- [ ] Jalankan `GetDiagnostics` untuk file-file yang dimodifikasi / dibuat baru:
  - `routes/web.php`
  - `app/Http/Controllers/Pembimbing/DashboardController.php` (harus 0 error)
  - `app/Http/Controllers/Pembimbing/AttendanceController.php`
  - `resources/views/pembimbing/dashboard.blade.php`
  - `resources/views/pembimbing/attendance/show.blade.php`
  - [jika 5A] `resources/views/pembimbing/attendance/pdf.blade.php`
- [ ] Manual cek: Buka Dashboard Pembimbing → klik salah satu tombol "Rekap Absensi" → halaman tidak ada error 500 → statistik tidak null → tabel tidak kosong → klik tombol PDF/Cetak → dialog PDF / unduhan muncul.
- [ ] Backward compatibility: Periksa `DashboardController@index` `compact()` TIDAK ada variable yang dihapus. Statistik cards 4 masih ada di dashboard.

### Task-local Test Requirements (TR)
| ID | Tipe | Uraian | Pass Condition |
|---|---|---|---|
| T6-TR1 | rule | `php artisan route:cache` exit 0. | Terminal output "Routes cached successfully." |
| T6-TR2 | rule | `php artisan view:cache` exit 0. | Terminal output "Views cached successfully." |
| T6-TR3 | rule | `GetDiagnostics` untuk semua file yang disebutkan = 0 error. | Tidak ada diagnostics error. |
| T6-TR4 | rule | `DashboardController@index` `compact()` tetap mengandung `'totalPenugasan', 'statsTotalInterns', 'statsActiveInterns', 'statsInactiveInterns'`. | 4 variabel ada dalam compact. |
| T6-TR5 | rubric | Manual smoke test tanpa HTTP 500 di ketiga tahap (dashboard → attendance.show → PDF/Cetak). | Skor ≥ 1. |

---

## Ringkasan Pemetaan AC → Tasks
| AC | Terdistribusi di Task |
|---|---|
| AC-R1 | T2 |
| AC-R2 | T1, T6 |
| AC-R3 | T1 |
| AC-R4 | T1 |
| AC-R5 | T3 |
| AC-R6 | T3 |
| AC-R7 | T3 + T5A / T5B |
| AC-R8 | T5A |
| AC-R9 | T5B |
| AC-R10 | T6 |
| AC-R11 | T6 |
| AC-U1 | T3 |
| AC-U2 | T5A / T5B |
| AC-S1 | T1 |
| AC-P1 | T1, T3 |
| AC-M1 | T1, T2 |
