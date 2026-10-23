@extends('layouts.app')

@section('title', 'Sertifikat Magang — SIMAGANG Diskominfo SP Tuban')

@section('content')
@php
    $divisionName = $user->division->nama_divisi ?? ($user->division->name ?? 'Aplikasi dan Informatika');
    $hasSearch = trim((string) ($search ?? '')) !== '';
    $activeStatus = in_array($status ?? 'all', ['all', 'active', 'upcoming', 'completed'], true) ? (string) ($status ?? 'all') : 'all';
    $searchVal = e((string) ($search ?? ''));
    $perPageVal = (int) ($perPage ?? 10);
@endphp

<div class="container-fluid" style="padding-top: 0.25rem !important;">
    <!-- Top Header (Ultra-Compact) -->
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <div>
            <h1 class="h6 mb-0 text-gray-900 dark:text-white fw-bold d-flex align-items-center gap-1.5" style="line-height: 1.2;">
                <i class="bi bi-award-fill text-primary"></i>
                <span>Sertifikat Kelulusan Magang Digital</span>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-blue-50 text-blue-900 border border-blue-200 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800 px-2 py-0.5 rounded text-[11px] font-bold">
                <i class="bi bi-diagram-3 me-1"></i>Divisi: {{ $divisionName }}
            </span>
        </div>
    </div>

    <!-- 4 Summary Metric Cards (Formal Slim & Minimalis Modern - Max 2 Warna) -->
    <div class="row g-2 mb-2">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-600 dark:text-slate-400 tracking-wider">
                                Total Bimbingan
                            </div>
                            <div class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">
                                {{ $stats['total'] ?? 0 }}
                            </div>
                        </div>
                        <div class="text-slate-400 dark:text-slate-500 flex-shrink-0">
                            <i class="bi bi-people text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm border border-emerald-200 dark:border-emerald-900/60 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-emerald-800 dark:text-emerald-400 tracking-wider">
                                Sertifikat Terbit
                            </div>
                            <div class="h5 mb-0 fw-bold text-emerald-900 dark:text-emerald-300">
                                {{ $stats['issued'] ?? 0 }}
                            </div>
                        </div>
                        <div class="text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                            <i class="bi bi-patch-check text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-600 dark:text-slate-400 tracking-wider">
                                Siap Diterbitkan
                            </div>
                            <div class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">
                                {{ $stats['ready_to_issue'] ?? 0 }}
                            </div>
                        </div>
                        <div class="text-slate-400 dark:text-slate-500 flex-shrink-0">
                            <i class="bi bi-clock-history text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-600 dark:text-slate-400 tracking-wider">
                                Sedang Magang
                            </div>
                            <div class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">
                                {{ $stats['in_progress'] ?? 0 }}
                            </div>
                        </div>
                        <div class="text-slate-400 dark:text-slate-500 flex-shrink-0">
                            <i class="bi bi-hourglass-split text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card: Sertifikat Kelulusan Magang (Ultra-Dense & Minimalis) -->
    <div class="card shadow-sm border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 mb-3 overflow-hidden">
        <div class="card-header py-2 px-3 sm:px-4 bg-white dark:bg-slate-900 border-bottom border-slate-200 dark:border-slate-800">
            <!-- Filter Header Satu Baris Sejajar (No Stacking / Tidak Bertumpuk) -->
            <form method="GET" action="{{ route('pembimbing.certificates.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2 mb-0" id="certificateFilterForm">
                <!-- Sisi Kiri (Filter Controls): Dropdown Baris/Hal + Dropdown Status + Dropdown Tahun -->
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <!-- Dropdown Selector Data Per Halaman -->
                    <div class="w-auto">
                        <label class="form-label visually-hidden" for="filterPerPage">Per halaman</label>
                        <select
                            id="filterPerPage"
                            name="per_page"
                            class="h-8 px-2.5 pr-7 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs filter-auto-submit rounded text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs font-medium w-auto"
                            aria-label="Pilih jumlah data per baris">
                            <option value="5" @selected($perPageVal === 5)>5 baris / hal</option>
                            <option value="10" @selected($perPageVal === 10)>10 baris / hal</option>
                            <option value="15" @selected($perPageVal === 15)>15 baris / hal</option>
                            <option value="25" @selected($perPageVal === 25)>25 baris / hal</option>
                            <option value="50" @selected($perPageVal === 50)>50 baris / hal</option>
                        </select>
                    </div>

                    <!-- Dropdown Filter Status Operasional -->
                    <div class="w-auto">
                        <label class="form-label visually-hidden" for="filterStatus">Filter Status Operasional</label>
                        <select
                            id="filterStatus"
                            name="status"
                            class="h-8 px-2.5 pr-7 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs filter-auto-submit rounded text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs font-medium w-auto"
                            aria-label="Filter status operasional">
                            <option value="all" @selected($activeStatus === 'all')>Semua Status</option>
                            <option value="active" @selected($activeStatus === 'active')>Masih Aktif</option>
                            <option value="upcoming" @selected($activeStatus === 'upcoming')>Belum Mulai</option>
                            <option value="completed" @selected($activeStatus === 'completed')>Sudah Selesai</option>
                        </select>
                    </div>

                    <!-- Dropdown Filter Tahun (Dinamis) -->
                    <div class="w-auto">
                        <label class="form-label visually-hidden" for="filterYear">Filter Tahun</label>
                        <select
                            id="filterYear"
                            name="year"
                            class="h-8 px-2.5 pr-7 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 text-xs filter-auto-submit rounded text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-xs font-medium w-auto"
                            aria-label="Filter tahun pelaksanaan">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" @selected((string)($year ?? '') === (string)$y)>Tahun {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset Filter Button -->
                    @if($hasSearch || $activeStatus !== 'all' || !empty($year) || $perPageVal !== 10)
                        <a href="{{ route('pembimbing.certificates.index') }}" class="btn btn-sm btn-outline-secondary py-1 px-2 text-xs font-semibold d-inline-flex items-center gap-1 rounded" title="Reset semua filter">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </div>

                <!-- Sisi Kanan: Search Bar & Tombol Ekspor PDF (Filter-Aware) -->
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap ms-auto">
                    <div class="w-full sm:w-auto" style="min-width: 200px; max-width: 260px;">
                        <div class="input-group">
                            <span class="input-group-text bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 border-end-0 py-1 px-2 text-xs text-slate-400" style="border-radius: 0.25rem 0 0 0.25rem;">
                                <i class="bi bi-search"></i>
                            </span>
                            <input
                                type="search"
                                name="search"
                                id="liveSearchInput"
                                class="form-control border border-slate-200 dark:border-slate-700 border-start-0 bg-white dark:bg-slate-900 text-xs rounded-r font-medium text-slate-800 dark:text-slate-200"
                                placeholder="Ketik nama / instansi..."
                                value="{{ $searchVal }}"
                                aria-label="Cari nama peserta atau instansi"
                                autocomplete="off"
                                style="min-height: 30px; font-size: 0.75rem; padding: 3px 8px;">
                        </div>
                    </div>

                    <!-- Tombol Ekspor PDF -->
                    <a href="{{ route('pembimbing.certificates.export-pdf', request()->query()) }}" target="_blank" class="h-8 px-3 inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-semibold shadow-xs transition-all text-decoration-none shrink-0" title="Ekspor Laporan PDF Sesuai Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Ekspor PDF
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card Body (Padding wrapper agar kolom pertama tidak terpotong) -->
        <div class="card-body p-3 sm:p-4">
            <div class="table-responsive">
                <table class="table table-sm table-dense table-hover align-middle mb-0 text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 border-bottom border-slate-200 dark:border-slate-800 text-[11px] font-bold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider">
                        <tr>
                            <th scope="col" class="text-center py-2 px-3" style="width: 48px;">No</th>
                            <th scope="col" class="py-2 px-3">Nama</th>
                            <th scope="col" class="py-2 px-3">Instansi &amp; Jurusan</th>
                            <th scope="col" class="py-2 px-3 text-nowrap">Periode Magang</th>
                            <th scope="col" class="text-center py-2 px-3 text-nowrap">Status</th>
                            <th scope="col" class="text-center py-2 px-3 text-nowrap" style="width: 1%; white-space: nowrap;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($interns as $index => $intern)
                            @php
                                $certExists = ! empty($intern->certificate_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($intern->certificate_path);
                                $isGraduationReached = $intern->isGraduationEligible();
                                $isTerminated = (bool) ($intern->is_terminated || $intern->operational_status === 'terminated');
                                $profile = $intern->user?->profile;
                                $isSiswa = $profile?->isSiswa();
                                $categoryBadge = $isSiswa ? 'Siswa SMK' : 'Mahasiswa';
                                $categoryClass = $isSiswa
                                    ? 'bg-slate-100 text-slate-800 border border-slate-300 font-bold text-[10px] px-2 py-0.5 rounded'
                                    : 'bg-blue-50 text-blue-900 border border-blue-300 font-bold text-[10px] px-2 py-0.5 rounded';
                                $rowNo = ($interns->currentPage() - 1) * $interns->perPage() + $loop->iteration;
                            @endphp
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/50 transition-colors">
                                <!-- Kolom 1: No -->
                                <td class="text-center font-monospace fw-semibold !text-slate-700 dark:!text-slate-300 py-2 px-3 text-xs">
                                    {{ $rowNo }}
                                </td>

                                <!-- Kolom 2: Nama (2 Baris Bersih: Nama & Email) -->
                                <td class="py-2 px-3 text-start">
                                    <div class="d-flex flex-column text-start">
                                        <div class="font-bold text-slate-900 dark:text-slate-100 text-xs leading-tight mb-0.5">
                                            {{ $intern->user?->name ?? '—' }}
                                        </div>
                                        <div class="text-slate-500 dark:text-slate-400 text-xs font-monospace leading-tight">
                                            {{ $intern->user?->email ?? '—' }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom 3: Instansi & Jurusan -->
                                <td class="py-2 px-3">
                                    <div class="font-bold !text-slate-900 dark:!text-slate-100 text-xs leading-tight mb-0.5">
                                         {{ $profile?->institusi ?? ($intern->institution ?? '—') }}
                                    </div>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                         @if($isSiswa)
                                             <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded border border-slate-400 !text-slate-900 !bg-slate-200" style="color: #0f172a !important; background-color: #e2e8f0 !important;">Siswa SMK</span>
                                         @else
                                             <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded border border-blue-300 !text-blue-950 !bg-blue-100" style="color: #0b2545 !important; background-color: #dbeafe !important;">Mahasiswa</span>
                                         @endif
                                         @if($profile?->jurusan)
                                              <span class="text-[10.5px] text-slate-600 dark:text-slate-400 font-sans truncate max-w-[140px]" title="{{ $profile->jurusan }}">
                                                 {{ $profile->jurusan }}
                                             </span>
                                         @endif
                                    </div>
                                    @if($profile?->nim_nis)
                                         <div class="text-[10px] font-monospace text-slate-500 dark:text-slate-400 leading-tight mt-0.5">
                                             NIS/NIM: {{ $profile->nim_nis }}
                                         </div>
                                    @endif
                                </td>

                                <!-- Kolom 4: Periode Magang -->
                                <td class="py-2 px-3 text-nowrap">
                                    <div class="fw-bold !text-slate-900 dark:!text-slate-100 text-xs leading-tight mb-0.5">
                                         {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                                         <span class="text-muted fw-normal mx-0.5">s/d</span>
                                         {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                    </div>
                                    <div class="text-[10.5px] text-muted leading-tight">
                                        @if($intern->periode_selesai)
                                            @if($isTerminated)
                                                <span class="text-rose-700 dark:text-rose-400 font-semibold d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-x-circle-fill"></i> Status dinonaktifkan
                                                </span>
                                            @elseif($isGraduationReached)
                                                <span class="text-emerald-700 dark:text-emerald-400 font-bold d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-calendar-check-fill"></i> Sudah capai akhir magang
                                                </span>
                                            @else
                                                <span class="text-slate-600 dark:text-slate-400 font-medium d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-hourglass-split"></i> Berakhir {{ $intern->periode_selesai->diffForHumans() }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>

                                <!-- Kolom 5: Status Magang / Sertifikat (HANYA 1 BADGE BERSIH & PRESISI) -->
                                <td class="text-center py-2 px-3 text-nowrap">
                                    @if($isTerminated)
                                        {{-- Kondisi A: Dinonaktifkan / Berhenti --}}
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 border border-rose-200">
                                            Dinonaktifkan
                                        </span>
                                    @elseif($certExists)
                                        {{-- Kondisi D: Selesai & Sertifikat Sudah Terbit --}}
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="bi bi-patch-check-fill text-emerald-700"></i> Sertifikat Terbit
                                        </span>
                                    @elseif($isGraduationReached)
                                        {{-- Kondisi C: Selesai Magang & Belum Terbit Sertifikat (now >= end_date) --}}
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                            Selesai Magang
                                        </span>
                                    @elseif($intern->operational_status === 'upcoming')
                                        {{-- Belum Mulai --}}
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                            Belum Mulai
                                        </span>
                                    @else
                                        {{-- Kondisi B: Aktif Magang (now < end_date) --}}
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Aktif Magang
                                        </span>
                                    @endif
                                </td>

                                <!-- Kolom 6: Toolbar Tombol Aksi Horizontal (RATA TENGAH, ANTI-TUMPUK & NO CUT-OFF) -->
                                <td class="text-center py-2 px-3 text-nowrap" style="width: 1%; white-space: nowrap;">
                                    <div class="flex items-center justify-center gap-1 flex-nowrap">
                                        @if($isTerminated)
                                            {{-- Kondisi A: Dinonaktifkan --}}
                                            <span class="px-2.5 py-1 text-xs font-medium text-slate-500 bg-slate-100 rounded border border-slate-200">
                                                Tidak Ada Sertifikat
                                            </span>
                                        @elseif($certExists)
                                            {{-- Kondisi D: Sertifikat Terbit (Preview, Download, Ganti, Hapus) --}}
                                            <!-- Tombol 1: Preview -->
                                            <a href="{{ route('pembimbing.certificates.view', $intern->id) }}"
                                               target="_blank" rel="noopener"
                                               class="px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded border border-slate-300 flex items-center gap-1 text-decoration-none"
                                               title="Lihat / Preview di Tab Baru">
                                                <i class="bi bi-eye"></i>
                                                <span>Preview</span>
                                            </a>

                                            <!-- Tombol 2: Download -->
                                            <a href="{{ route('pembimbing.certificates.download', $intern->id) }}"
                                               class="px-2.5 py-1 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded shadow-sm flex items-center gap-1 text-decoration-none"
                                               title="Unduh Sertifikat PDF">
                                                <i class="bi bi-download"></i>
                                                <span>Download</span>
                                            </a>

                                            <!-- Tombol 3: Ganti -->
                                            <button type="button"
                                                    class="px-2.5 py-1 text-xs font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded shadow-sm flex items-center gap-1 border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#replaceCertModal-{{ $intern->id }}"
                                                    title="Ganti / Perbarui File Sertifikat">
                                                <i class="bi bi-arrow-repeat"></i>
                                                <span>Ganti</span>
                                            </button>

                                            <!-- Tombol 4: Hapus dengan Standard Modal -->
                                            <form method="POST" action="{{ route('pembimbing.certificates.delete', $intern->id) }}" class="inline-block m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                        class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded border border-red-200 flex items-center gap-1 cursor-pointer"
                                                        data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Nama Peserta</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($intern->user?->name ?? '—') }}</div><div class='col-4 text-slate-500 font-semibold'>No. Pendaftaran</div><div class='col-8 font-monospace'>{{ $intern->nomor_pendaftaran }}</div></div>"
                                                        data-simang-title="Hapus Sertifikat Kelulusan"
                                                        data-simang-icon="bi-trash-fill"
                                                        data-simang-theme="rose"
                                                        data-simang-alert="File fisik sertifikat resmi (PDF) akan dihapus permanen. Hak unduh peserta pada portal sertifikat akan dinonaktifkan."
                                                        data-simang-btn-text="Ya, Hapus Sertifikat"
                                                        data-simang-btn-icon="bi-trash-fill"
                                                        title="Hapus Sertifikat">
                                                    <i class="bi bi-trash"></i>
                                                    <span>Hapus</span>
                                                </button>
                                            </form>
                                        @elseif($isGraduationReached)
                                            {{-- Kondisi C: Selesai Magang & Siap Upload (now >= end_date) --}}
                                            <button type="button"
                                                    class="px-3 py-1 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded shadow-sm flex items-center gap-1 border-0"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#uploadCertModal-{{ $intern->id }}"
                                                    title="Unggah dan terbitkan sertifikat untuk peserta ini">
                                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                                <span>Upload Sertifikat</span>
                                            </button>
                                        @else
                                            {{-- Kondisi B: Aktif Magang / Belum Waktunya (now < end_date) --}}
                                            <span class="px-2.5 py-1 text-xs font-medium text-slate-600 bg-slate-100 rounded border border-slate-300 flex items-center gap-1 shadow-sm"
                                                  title="Belum Waktunya Kelulusan: Tombol baru aktif pada hari terakhir magang ({{ $intern->periode_selesai?->translatedFormat('d M Y') ?? 'akhir periode' }})">
                                                <i class="bi bi-lock-fill text-amber-500"></i>
                                                <span>Belum Waktunya</span>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3 text-muted">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-2">
                                        <i class="bi bi-inbox fs-2 text-slate-300 mb-1"></i>
                                        <p class="fw-bold text-slate-800 dark:text-slate-200 mb-0.5 text-xs">Tidak ada data peserta bimbingan yang ditemukan</p>
                                        <p class="text-[11px] text-muted mb-0">Coba ubah kata kunci pencarian atau sesuaikan filter status di atas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer (Compact) -->
        @if($interns->hasPages())
            <div class="card-footer bg-white dark:bg-slate-900 border-top border-slate-200 dark:border-slate-800 py-1.5 px-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span class="text-[11px] text-muted font-medium">
                    Menampilkan <strong>{{ $interns->firstItem() ?? 0 }}</strong> s/d <strong>{{ $interns->lastItem() ?? 0 }}</strong> dari <strong>{{ $interns->total() }}</strong> anak bimbing
                </span>
                <div class="pagination-sm mb-0">
                    {{ $interns->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals: Upload & Ganti Sertifikat (Ultra-Dense & Minimalis Modern) -->
@foreach($interns as $intern)
    @php
        $certExists = ! empty($intern->certificate_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($intern->certificate_path);
        $isGraduationReached = $intern->isGraduationEligible();
    @endphp

    @if(! $certExists && $isGraduationReached)
        <!-- Modal Upload Sertifikat Kelulusan Baru -->
        <div class="modal fade" id="uploadCertModal-{{ $intern->id }}" tabindex="-1" aria-labelledby="uploadCertModalLabel-{{ $intern->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl overflow-hidden bg-white dark:bg-slate-900" x-data="{ certFileName: '' }">
                    <form method="POST" action="{{ route('pembimbing.certificates.upload', $intern->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-primary text-white py-2 px-3">
                            <h5 class="modal-title text-xs mb-0 text-white font-bold uppercase tracking-wider d-flex align-items-center gap-1.5" id="uploadCertModalLabel-{{ $intern->id }}">
                                <i class="bi bi-award-fill"></i> Unggah Sertifikat Kelulusan Magang
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-2.5 sm:p-3 text-xs">
                            <!-- Intern Brief Info -->
                            <div class="p-2 mb-2 rounded border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/70 text-xs">
                                <div class="row g-1">
                                    <div class="col-4 text-slate-500 dark:text-slate-400 font-semibold">Nama Peserta</div>
                                    <div class="col-8 fw-bold !text-slate-900 dark:!text-slate-100">{{ $intern->user?->name ?? '—' }}</div>

                                    <div class="col-4 text-slate-500 dark:text-slate-400 font-semibold">Nomor Pendaftaran</div>
                                    <div class="col-8 font-monospace text-slate-700 dark:text-slate-300">{{ $intern->nomor_pendaftaran }}</div>

                                    <div class="col-4 text-slate-500 dark:text-slate-400 font-semibold">Periode Magang</div>
                                    <div class="col-8 fw-bold text-primary">
                                        {{ $intern->periode_mulai?->translatedFormat('d M Y') }} s/d {{ $intern->periode_selesai?->translatedFormat('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label for="certificate-{{ $intern->id }}" class="text-[11px] font-bold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider mb-0 flex items-center gap-1.5">
                                        <i class="bi bi-filetype-pdf text-rose-500"></i> Berkas Sertifikat (PDF) <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Maks. 5 MB</span>
                                </div>

                                <div class="relative border border-dashed rounded-lg p-2.5 text-center transition-all duration-150"
                                     :class="certFileName ? 'border-primary bg-primary-subtle' : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-primary'">
                                    <input
                                        type="file"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        id="certificate-{{ $intern->id }}"
                                        name="certificate"
                                        accept="application/pdf,.pdf"
                                        @change="certFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                        required>

                                    <!-- Default Prompt -->
                                    <div x-show="!certFileName" class="flex flex-col items-center justify-center gap-1 py-1">
                                        <i class="bi bi-cloud-arrow-up-fill text-primary text-xl"></i>
                                        <span class="font-bold !text-slate-900 dark:!text-slate-100 text-xs">Pilih atau seret berkas PDF sertifikat ke sini</span>
                                        <span class="text-slate-400 text-[10.5px]">Format: PDF (Maksimal 5MB)</span>
                                    </div>

                                    <!-- Active File Selected -->
                                    <div x-show="certFileName" x-cloak class="flex items-center justify-center gap-1.5 py-1">
                                        <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-lg"></i>
                                        <span class="font-bold !text-slate-900 dark:!text-slate-100 text-xs truncate max-w-xs" x-text="certFileName"></span>
                                        <button type="button" @click.stop.prevent="certFileName = ''; document.getElementById('certificate-{{ $intern->id }}').value = ''" class="text-slate-400 hover:text-rose-600 transition-colors ms-1 relative z-20" title="Batal pilih berkas">
                                            <i class="bi bi-x-circle-fill text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info py-1.5 px-2 mt-1.5 mb-0 text-[11px] border border-blue-200 bg-blue-50 text-blue-900 rounded d-flex align-items-start gap-1.5">
                                <i class="bi bi-send-check-fill text-blue-600 mt-0.5 flex-shrink-0"></i>
                                <span>Setelah diunggah, sistem akan secara otomatis mengirimkan email notifikasi kelulusan resmi kepada peserta magang.</span>
                            </div>
                        </div>
                        <div class="modal-footer bg-slate-50 dark:bg-slate-800/60 border-top border-slate-200 dark:border-slate-800 py-1.5 px-3 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary btn-sm px-2.5 py-1 text-xs font-semibold rounded" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                    class="btn btn-primary btn-sm px-3 py-1 text-xs font-bold text-white shadow-xs d-inline-flex align-items-center gap-1 rounded"
                                    :class="!certFileName ? 'opacity-60 cursor-not-allowed' : 'hover:shadow'"
                                    :disabled="!certFileName">
                                <i class="bi bi-upload"></i>
                                <span>Unggah &amp; Terbitkan Sertifikat</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    @if($certExists)
        <!-- Modal Ganti Sertifikat Kelulusan -->
        <div class="modal fade" id="replaceCertModal-{{ $intern->id }}" tabindex="-1" aria-labelledby="replaceCertModalLabel-{{ $intern->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border border-slate-200 dark:border-slate-800 shadow-xl rounded-xl overflow-hidden bg-white dark:bg-slate-900" x-data="{ replaceCertName: '' }">
                    <form method="POST" action="{{ route('pembimbing.certificates.upload', $intern->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header bg-amber-500 text-white py-2 px-3">
                            <h5 class="modal-title text-xs mb-0 text-white font-bold uppercase tracking-wider d-flex align-items-center gap-1.5" id="replaceCertModalLabel-{{ $intern->id }}">
                                <i class="bi bi-arrow-repeat"></i> Ganti File Sertifikat Kelulusan
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-2.5 sm:p-3 text-xs">
                            <div class="alert alert-warning py-1.5 px-2 mb-2 text-[11px] border border-amber-300 bg-amber-50 text-amber-900 rounded d-flex align-items-start gap-1.5">
                                <i class="bi bi-exclamation-triangle-fill text-amber-600 mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <span class="fw-bold d-block">Perhatian Penimpaan File:</span>
                                    File sertifikat baru akan langsung menimpa file lama (<code class="text-[11px] font-monospace font-bold text-slate-800">{{ basename($intern->certificate_path) }}</code>). Peserta otomatis menerima versi terbaru.
                                </div>
                            </div>

                            <div class="mb-2">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label for="replace_cert-{{ $intern->id }}" class="text-[11px] font-bold !text-slate-900 dark:!text-slate-100 uppercase tracking-wider mb-0 flex items-center gap-1.5">
                                        <i class="bi bi-filetype-pdf text-rose-500"></i> Pilih File Sertifikat Baru (PDF) <span class="text-rose-500">*</span>
                                    </label>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">Maks. 5 MB</span>
                                </div>

                                <div class="relative border border-dashed rounded-lg p-2.5 text-center transition-all duration-150"
                                     :class="replaceCertName ? 'border-amber-500 bg-amber-50 dark:bg-amber-950/20' : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 hover:border-amber-400'">
                                    <input
                                        type="file"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                        id="replace_cert-{{ $intern->id }}"
                                        name="certificate"
                                        accept="application/pdf,.pdf"
                                        @change="replaceCertName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                        required>

                                    <!-- Default Dropzone Prompt -->
                                    <div x-show="!replaceCertName" class="flex flex-col items-center justify-center gap-1 py-1">
                                        <i class="bi bi-cloud-arrow-up-fill text-amber-600 text-xl"></i>
                                        <span class="font-bold !text-slate-900 dark:!text-slate-100 text-xs">Pilih atau seret file PDF pengganti ke sini</span>
                                        <span class="text-slate-400 text-[10.5px]">Format: PDF (Ukuran maksimal 5MB)</span>
                                    </div>

                                    <!-- Active Selected File State -->
                                    <div x-show="replaceCertName" x-cloak class="flex items-center justify-center gap-1.5 py-1">
                                        <i class="bi bi-file-earmark-pdf-fill text-rose-500 text-lg"></i>
                                        <span class="font-bold !text-slate-900 dark:!text-slate-100 text-xs truncate max-w-xs" x-text="replaceCertName"></span>
                                        <button type="button" @click.stop.prevent="replaceCertName = ''; document.getElementById('replace_cert-{{ $intern->id }}').value = ''" class="text-slate-400 hover:text-rose-600 transition-colors ms-1 relative z-20" title="Batal pilih file">
                                            <i class="bi bi-x-circle-fill text-xs"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-slate-50 dark:bg-slate-800/60 border-top border-slate-200 dark:border-slate-800 py-1.5 px-3 d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary btn-sm px-2.5 py-1 text-xs font-semibold rounded" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit"
                                    class="btn btn-warning btn-sm px-3 py-1 text-xs font-bold text-dark shadow-xs d-inline-flex align-items-center gap-1 rounded"
                                    :class="!replaceCertName ? 'opacity-60 cursor-not-allowed' : 'hover:shadow'"
                                    :disabled="!replaceCertName">
                                <i class="bi bi-arrow-repeat"></i>
                                <span>Simpan &amp; Timpa Sertifikat</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

<!-- Script Live Search Without Enter & Auto Submit Debounce -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('certificateFilterForm');
    var searchInput = document.getElementById('liveSearchInput');
    var autoSelects = document.querySelectorAll('.filter-auto-submit');
    var searchTimeout = null;
    var SEARCH_DEBOUNCE_MS = 400;

    function doSubmit() {
        if (form) {
            form.submit();
        }
    }

    // Auto submit dropdown changes (status & per_page)
    if (autoSelects && autoSelects.length) {
        autoSelects.forEach(function (sel) {
            sel.addEventListener('change', function () {
                doSubmit();
            });
        });
    }

    // Live search without Enter (debounced input listener)
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }
            searchTimeout = setTimeout(function () {
                searchTimeout = null;
                doSubmit();
            }, SEARCH_DEBOUNCE_MS);
        });

        // Instant submit on search clear icon click
        searchInput.addEventListener('search', function () {
            if (searchTimeout) {
                clearTimeout(searchTimeout);
                searchTimeout = null;
            }
            doSubmit();
        });

        // Prevent double trigger on Enter
        searchInput.addEventListener('keydown', function (e) {
            if (e && (e.key === 'Enter' || e.keyCode === 13)) {
                e.preventDefault();
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                    searchTimeout = null;
                }
                doSubmit();
                return false;
            }
        });
    }
});
</script>
@endsection
