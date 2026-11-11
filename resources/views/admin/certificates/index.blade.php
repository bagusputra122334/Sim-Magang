@extends('layouts.admin')

@section('title', 'Sertifikat Magang — SIMAGANG')

@section('content')
@php
    $searchVal = e((string) ($search ?? ''));
    $perPageVal = (int) ($perPage ?? 10);
    $selectedStatus = $status ?? 'all';
@endphp

<div class="container-fluid" style="padding-top: 0.25rem !important;">
    <!-- Top Header (Segaris dengan Judul & Tombol Ekspor PDF) -->
    <div class="d-flex flex-row justify-content-between align-items-center mb-2.5 gap-2 w-full w-100">
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 d-flex align-items-center gap-2" style="line-height: 1.2;">
                <i class="bi bi-award-fill text-primary"></i>
                <span>Kelola Sertifikat Magang Digital</span>
            </h1>
        </div>
        
        <!-- Export Button (Pushed Strictly to Right Side, Segaris dengan Judul) -->
        <div class="shrink-0 ms-auto">
            <a href="{{ route('admin.certificates.export-pdf', request()->query()) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors text-decoration-none"
               title="Ekspor Laporan PDF Sesuai Filter">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span>Ekspor PDF</span>
            </a>
        </div>
    </div>

    <!-- 4 Summary Metric Cards -->
    <div class="row g-2.5 mb-2.5">
        <div class="col-6 col-md-3">
            <div class="card shadow-xs border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-2.5">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-500 dark:text-slate-400 tracking-wider">
                                Total Peserta
                            </div>
                            <div class="h5 mb-0 fw-black text-slate-800 dark:text-slate-100 leading-none">
                                {{ $stats['total'] ?? 0 }}
                            </div>
                        </div>
                        <div class="p-2 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <i class="bi bi-people fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-xs border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-2.5">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-500 dark:text-slate-400 tracking-wider">
                                Sertifikat Terbit
                            </div>
                            <div class="h5 mb-0 fw-black text-slate-800 dark:text-slate-100 leading-none">
                                {{ $stats['issued'] ?? 0 }}
                            </div>
                        </div>
                        <div class="p-2 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <i class="bi bi-patch-check fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-xs border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-2.5">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-500 dark:text-slate-400 tracking-wider">
                                Menunggu Terbit
                            </div>
                            <div class="h5 mb-0 fw-black text-slate-800 dark:text-slate-100 leading-none">
                                {{ $stats['ready_to_issue'] ?? 0 }}
                            </div>
                        </div>
                        <div class="p-2 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card shadow-xs border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100 overflow-hidden">
                <div class="card-body p-2.5">
                    <div class="d-flex align-items-center justify-content-between gap-1.5">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-[10px] text-slate-500 dark:text-slate-400 tracking-wider">
                                Sedang Berjalan
                            </div>
                            <div class="h5 mb-0 fw-black text-slate-800 dark:text-slate-100 leading-none">
                                {{ $stats['in_progress'] ?? 0 }}
                            </div>
                        </div>
                        <div class="p-2 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400">
                            <i class="bi bi-hourglass-split fs-5"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar (Presisi Segaris Desktop) -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-xs rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.certificates.index') }}" class="row g-2 align-items-end text-xs">
                @php
                    $hasFilter = !empty($search) || ($status !== 'all' && !empty($status)) || !empty($year) || (!empty($selectedOpdId) && !empty($isSuperAdmin));
                @endphp

                {{-- Filter 1: Cari --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-12 col-sm-6 col-lg-3' : 'col-12 col-sm-6 col-lg-4' }}">
                    <label for="search" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-search me-1"></i> Cari (Nama / Instansi / Posisi)
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control form-control-sm text-xs py-1.5 px-2.5"
                            value="{{ request('search', $search) }}"
                            placeholder="Ketik nama, instansi...">
                        <button type="submit" class="btn btn-sm btn-primary py-1.5 px-2.5 text-xs font-semibold" title="Cari Peserta">
                            <i class="bi bi-search"></i>
                        </button>
                        @if($hasFilter)
                            <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm btn-outline-danger py-1.5 px-2 text-xs font-semibold" title="Reset Semua Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Filter 2: Wilayah OPD (Khusus Super Admin) --}}
                @if(!empty($isSuperAdmin))
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label for="opd_id" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                            <i class="bi bi-buildings me-1"></i> Wilayah OPD
                        </label>
                        <select name="opd_id" id="opd_id" class="form-select form-select-sm text-xs py-1.5 px-2 font-semibold text-primary w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                            <option value="">-- Pilih Wilayah OPD --</option>
                            <option value="all" @selected((string)request('opd_id', $selectedOpdId ?? '') === 'all')>Semua Wilayah OPD (Agregat Tuban)</option>
                            @foreach ($opds as $opdItem)
                                <option value="{{ $opdItem->id }}" @selected((string)request('opd_id', $selectedOpdId ?? '') === (string)$opdItem->id)>
                                    {{ $opdItem->name }} ({{ $opdItem->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter 3: Status Sertifikat --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-4 col-lg-2' : 'col-6 col-sm-3 col-lg-3' }}">
                    <label for="status" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-funnel me-1"></i> Status Sertifikat
                    </label>
                    <select name="status" id="status" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="all" @selected($selectedStatus === 'all')>Semua Status</option>
                        <option value="issued" @selected($selectedStatus === 'issued')>Sertifikat Terbit</option>
                        <option value="ready_to_issue" @selected($selectedStatus === 'ready_to_issue')>Siap Diterbitkan</option>
                        <option value="in_progress" @selected($selectedStatus === 'in_progress')>Sedang Magang</option>
                    </select>
                </div>

                {{-- Filter 4: Filter Tahun --}}
                <div class="col-6 col-sm-4 col-lg-2">
                    <label for="year" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-calendar-event me-1"></i> Filter Tahun
                    </label>
                    <select name="year" id="year" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($availableYears ?? [] as $y)
                            <option value="{{ $y }}" @selected((string)request('year', $year ?? '') === (string)$y)>Tahun {{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter 5: Baris Per Halaman --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-12 col-sm-4 col-lg-2' : 'col-12 col-sm-3 col-lg-3' }}">
                    <label for="per_page" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-list-ol me-1"></i> Baris
                    </label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" onchange="this.form.submit()">
                        <option value="5" @selected((string)request('per_page', $perPage) === '5')>5 baris</option>
                        <option value="10" @selected((string)request('per_page', $perPage) === '10')>10 baris</option>
                        <option value="15" @selected((string)request('per_page', $perPage) === '15')>15 baris</option>
                        <option value="20" @selected((string)request('per_page', $perPage) === '20')>20 baris</option>
                        <option value="25" @selected((string)request('per_page', $perPage) === '25')>25 baris</option>
                        <option value="50" @selected((string)request('per_page', $perPage) === '50')>50 baris</option>
                        <option value="100" @selected((string)request('per_page', $perPage) === '100')>100 baris</option>
                        <option value="all" @selected((string)request('per_page', $perPage) === '10000' || (string)request('per_page', $perPage) === 'all')>Semua (All)</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg overflow-hidden bg-white dark:bg-slate-800">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-xs">
                <thead class="bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 uppercase font-bold">
                    <tr>
                        <th class="ps-3 py-2.5 text-center" style="width: 50px;">NO.</th>
                        <th class="py-2.5 px-3">PESERTA & INSTANSI</th>
                        <th class="py-2.5 px-3">POSISI & DIVISI</th>
                        <th class="py-2.5 px-3">PERIODE MAGANG</th>
                        <th class="py-2.5 px-3 text-center">STATUS SERTIFIKAT</th>
                        <th class="pe-3 py-2.5 text-end" style="min-width: 170px;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($interns as $index => $intern)
                        @php
                            $prof = $intern->user?->profile;
                            $certExists = !empty($intern->certificate_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($intern->certificate_path);
                            $targetOpd = $intern->opd ?? $intern->position?->opd;
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="ps-3 py-2.5 text-center font-bold text-slate-500">
                                {{ ($interns->currentPage() - 1) * $interns->perPage() + $loop->iteration }}
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 font-bold d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 11px;">
                                        {{ strtoupper(substr($intern->user?->name ?? 'P', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-slate-900 dark:text-slate-100 text-xs truncate">
                                            {{ $intern->user?->name ?? 'Peserta' }}
                                        </div>
                                        <div class="text-[11px] text-muted truncate">
                                            {{ $prof?->institusi ?? $intern->user?->email ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-xs">
                                <div class="fw-bold text-slate-800 dark:text-slate-200">
                                    {{ $intern->position?->nama_posisi ?? '—' }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                    @if($targetOpd)
                                        <span class="inline-flex items-center gap-1">
                                            <i class="bi bi-buildings text-primary"></i> {{ $targetOpd->name }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-xs whitespace-nowrap">
                                <div class="fw-medium text-slate-800 dark:text-slate-200">
                                    {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                                    <span class="text-slate-400 mx-1">s/d</span>
                                    {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    {{ $intern->nomor_pendaftaran }}
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-center whitespace-nowrap">
                                @if($certExists)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        <i class="bi bi-patch-check-fill text-emerald-600"></i> Sertifikat Terbit
                                    </span>
                                @elseif($intern->is_graduation_eligible)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-900 border border-amber-300" title="Magang telah selesai, siap diterbitkan sertifikat">
                                        <i class="bi bi-clock-history text-amber-700"></i> Menunggu Terbit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-300">
                                        <i class="bi bi-hourglass-split text-slate-500"></i> Sedang Magang
                                    </span>
                                @endif
                            </td>
                            <td class="pe-3 py-2.5 text-end whitespace-nowrap">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    @if($certExists)
                                        <a href="{{ route('admin.certificates.view', $intern->id) }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary py-1 px-2 text-xs font-semibold rounded shadow-xs" title="Pratinjau PDF Sertifikat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.certificates.download', $intern->id) }}"
                                           class="btn btn-sm btn-outline-secondary py-1 px-2 text-xs font-semibold rounded shadow-xs" title="Unduh File PDF">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        @if(empty($isSuperAdmin))
                                            <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2 text-xs font-semibold rounded shadow-xs"
                                                    data-bs-toggle="modal" data-bs-target="#modalReplaceCert-{{ $intern->id }}" title="Ganti File Sertifikat">
                                                <i class="bi bi-arrow-repeat"></i> Ganti
                                            </button>
                                            <form method="POST" action="{{ route('admin.certificates.delete', $intern->id) }}" class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus sertifikat untuk peserta ini? File fisik akan dihapus permanen.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 text-xs font-semibold rounded shadow-xs" title="Hapus Sertifikat">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @elseif($intern->is_graduation_eligible)
                                        @if(!empty($isSuperAdmin))
                                            <span class="border border-slate-300 py-1.5 px-2.5 text-xs font-semibold rounded d-inline-flex align-items-center" style="background-color: #f1f5f9 !important; color: #334155 !important;" title="Menunggu penerbitan oleh Administrator OPD terkait">
                                                <i class="bi bi-eye text-slate-400 me-1"></i> View Only
                                            </span>
                                        @else
                                            <button type="button" class="btn btn-sm btn-primary py-1 px-2.5 text-xs font-bold rounded shadow-xs d-inline-flex align-items-center gap-1"
                                                    data-bs-toggle="modal" data-bs-target="#modalUploadCert-{{ $intern->id }}"
                                                    title="Periode magang telah selesai. Klik untuk mengunggah sertifikat kelulusan.">
                                                <i class="bi bi-upload"></i> Unggah Sertifikat
                                            </button>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-sm btn-secondary opacity-50 py-1 px-2.5 text-xs font-medium rounded shadow-none d-inline-flex align-items-center gap-1 cursor-not-allowed"
                                                disabled
                                                title="Sertifikat baru dapat dimasukkan setelah periode magang selesai ({{ $intern->periode_selesai?->translatedFormat('d M Y') ?? 'akhir periode' }}).">
                                            <i class="bi bi-lock-fill text-amber-300"></i> Belum Waktunya
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @if(empty($isSuperAdmin) && !$certExists && $intern->is_graduation_eligible)
                            <!-- Modal Upload Sertifikat -->
                            <div class="modal fade" id="modalUploadCert-{{ $intern->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-xl">
                                        <form method="POST" action="{{ route('admin.certificates.upload', $intern->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header py-2.5 px-3 bg-primary text-white">
                                                <h6 class="modal-title text-xs font-bold text-white d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-award-fill"></i> Unggah Sertifikat Kelulusan Magang
                                                </h6>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-3 text-xs">
                                                <div class="p-2.5 rounded bg-slate-50 dark:bg-slate-900 border mb-3">
                                                    <div class="fw-bold text-slate-800 dark:text-slate-200">{{ $intern->user?->name }}</div>
                                                    <div class="text-muted text-[11px]">{{ $intern->nomor_pendaftaran }} • {{ $intern->position?->nama_posisi }}</div>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label font-bold text-slate-700 dark:text-slate-300">Pilih Berkas Sertifikat (PDF) <span class="text-danger">*</span></label>
                                                    <input type="file" name="certificate" class="form-control form-control-sm text-xs" accept="application/pdf" required>
                                                    <small class="text-muted text-[11px] d-block mt-1">Format PDF, maksimal ukuran 5 MB. Peserta akan otomatis menerima email notifikasi.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2 px-3 bg-light">
                                                <button type="button" class="btn btn-sm btn-secondary text-xs" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-primary text-xs font-bold">
                                                    <i class="bi bi-upload me-1"></i> Terbitkan Sertifikat
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(empty($isSuperAdmin) && $certExists)
                            <!-- Modal Ganti Sertifikat -->
                            <div class="modal fade" id="modalReplaceCert-{{ $intern->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-xl">
                                        <form method="POST" action="{{ route('admin.certificates.upload', $intern->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header py-2.5 px-3 bg-warning text-dark">
                                                <h6 class="modal-title text-xs font-bold d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-arrow-repeat"></i> Ganti File Sertifikat Kelulusan
                                                </h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-3 text-xs">
                                                <div class="p-2.5 rounded bg-slate-50 dark:bg-slate-900 border mb-3">
                                                    <div class="fw-bold text-slate-800 dark:text-slate-200">{{ $intern->user?->name }}</div>
                                                    <div class="text-muted text-[11px]">Sertifikat saat ini: <code>{{ basename($intern->certificate_path ?? '') }}</code></div>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label font-bold text-slate-700 dark:text-slate-300">Pilih Berkas Sertifikat Baru (PDF) <span class="text-danger">*</span></label>
                                                    <input type="file" name="certificate" class="form-control form-control-sm text-xs" accept="application/pdf" required>
                                                    <small class="text-muted text-[11px] d-block mt-1">Berkas lama akan langsung ditimpa dan peserta akan menerima pembaruan.</small>
                                                </div>
                                            </div>
                                            <div class="modal-footer py-2 px-3 bg-light">
                                                <button type="button" class="btn btn-sm btn-secondary text-xs" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-sm btn-warning text-dark text-xs font-bold">
                                                    <i class="bi bi-arrow-repeat me-1"></i> Simpan & Timpa Sertifikat
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                @if(!empty($isSuperAdmin) && empty($selectedOpdId))
                                    <div class="d-inline-flex p-3 rounded-circle bg-blue-50 dark:bg-blue-900/30 text-blue-600 mb-2">
                                        <i class="bi bi-buildings fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-slate-800 dark:text-slate-100 mb-1 text-sm">Pilih Wilayah OPD Terlebih Dahulu</h6>
                                    <p class="text-xs text-muted mb-0 max-w-md mx-auto">Silakan pilih salah satu Organisasi Perangkat Daerah (OPD) pada filter di atas untuk mengelola sertifikat magang.</p>
                                @else
                                    <i class="bi bi-award fs-1 d-block mb-2 opacity-50 text-muted"></i>
                                    <p class="mb-0.5 text-xs font-semibold text-muted">Tidak ada data sertifikat peserta magang yang cocok dengan kriteria filter.</p>
                                    <a href="{{ route('admin.certificates.index') }}" class="btn btn-link btn-sm py-0 text-xs">
                                        Reset Filter Sertifikat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-transparent border-top py-2.5 px-3">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <div class="text-muted text-xs">
                    Menampilkan <strong>{{ $interns->firstItem() ?? 0 }}</strong> s.d. <strong>{{ $interns->lastItem() ?? 0 }}</strong> dari <strong>{{ $interns->total() }}</strong> peserta magang
                </div>
                <div>
                    {{ $interns->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

