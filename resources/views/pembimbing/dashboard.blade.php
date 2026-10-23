@extends('layouts.app')
@section('title', 'Dashboard Pembimbing')
@section('content')
@php
    $hasSearch = trim((string) ($search ?? '')) !== '';
    $activeStatus = in_array($status ?? 'all', ['all', 'active', 'inactive'], true) ? (string) ($status ?? 'all') : 'all';
    $searchVal = e((string) ($search ?? ''));
    $perPageVal = in_array($perPageRaw ?? '10', ['10','20','50','all'], true) ? (string) ($perPageRaw ?? '10') : '10';
    $isAllPerPage = (bool) ($isAllPerPage ?? ($perPageVal === 'all'));
@endphp
<div class="container-fluid" style="padding-top: 0.25rem !important;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2.5">
        <h1 class="h5 mb-0 text-gray-900 dark:text-white fw-bold" style="line-height: 1.2;">Dashboard Pembimbing ({{ $user->division->nama_divisi ?? ($user->division->name ?? '-') }})</h1>
    </div>

    {{-- 4 Stat Cards: Formal Slim & Minimalis (Max 2 Warna Resmi) --}}
    <div class="row g-2 mb-2.5">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-slate-600 dark:text-slate-400" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                                Total Penugasan
                            </div>
                            <div class="h6 mb-0 fw-bold text-slate-800 dark:text-slate-100" style="font-size: 1.1rem; line-height: 1.2;">
                                {{ $totalPenugasan ?? 0 }}
                            </div>
                        </div>
                        <div class="text-slate-400 dark:text-slate-500 flex-shrink-0">
                            <i class="bi bi-journal-bookmark text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800 rounded-lg bg-white dark:bg-slate-900 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-slate-600 dark:text-slate-400" style="font-size: 0.6875rem; letter-spacing: 0.05em;">
                                Total Anak Bimbing
                            </div>
                            <div class="h6 mb-0 fw-bold text-slate-800 dark:text-slate-100" style="font-size: 1.1rem; line-height: 1.2;">
                                {{ $statsTotalInterns ?? 0 }}
                            </div>
                        </div>
                        <div class="text-slate-400 dark:text-slate-500 flex-shrink-0">
                            <i class="bi bi-people text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-lg-2">
            <div class="card shadow-sm border border-emerald-200 dark:border-emerald-900/60 rounded-lg bg-white dark:bg-slate-900 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-emerald-800 dark:text-emerald-400" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                Aktif
                            </div>
                            <div class="h6 mb-0 fw-bold text-emerald-900 dark:text-emerald-300" style="font-size: 1.05rem; line-height: 1.2;">
                                {{ $statsActiveInterns ?? 0 }}
                            </div>
                        </div>
                        <div class="text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                            <i class="bi bi-person-check text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-sm-6 col-lg-2">
            <div class="card shadow-sm border border-rose-200 dark:border-rose-900/60 rounded-lg bg-white dark:bg-slate-900 h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <div class="min-w-0">
                            <div class="text-uppercase fw-bold mb-0.5 text-rose-800 dark:text-rose-400" style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                Nonaktif
                            </div>
                            <div class="h6 mb-0 fw-bold text-rose-900 dark:text-rose-300" style="font-size: 1.05rem; line-height: 1.2;">
                                {{ $statsInactiveInterns ?? 0 }}
                            </div>
                        </div>
                        <div class="text-rose-600 dark:text-rose-400 flex-shrink-0">
                            <i class="bi bi-person-x text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Panel: Daftar Anak Bimbing (Ultra-Dense) --}}
    <div class="card shadow-sm border-0 mb-2.5">
        <form method="GET" action="{{ route('pembimbing.dashboard') }}" id="mentorInternFilterForm" role="search" class="m-0 p-0">
            <button type="submit" class="visually-hidden" aria-hidden="true" tabindex="-1">Submit</button>
            <div class="px-3 py-2 bg-slate-50/70 border-b border-slate-200/80 flex items-center justify-between gap-2.5 w-full flex-nowrap overflow-x-auto">
                <!-- Kiri: Judul Utama (Tetap Sederet & Sejajar) -->
                <div class="flex items-center gap-2 shrink-0">
                    <h2 class="text-sm font-bold text-blue-600 tracking-tight whitespace-nowrap mb-0">Daftar Anak Bimbing</h2>
                </div>

                <!-- Kanan: Seluruh Filter + Tombol Reset + Tombol Ekspor PDF (Wajib Sederet 1 Baris) -->
                <div class="flex items-center gap-2 sm:gap-2.5 flex-nowrap shrink-0">
                    <!-- Dropdown Tampilkan -->
                    <div class="shrink-0">
                        <label class="form-label visually-hidden" for="internPerPage">Tampilkan per halaman</label>
                        <select
                            id="internPerPage"
                            name="per_page"
                            class="h-8 px-3 pr-7 min-w-[95px] bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-2xs mentor-filter-control"
                            data-auto-submit-select="1"
                            aria-label="Jumlah baris per halaman"
                            onchange="this.form.submit()">
                            <option value="10" @selected((string)request('per_page', $perPageVal ?? 10) === '10')>10 data</option>
                            <option value="20" @selected((string)request('per_page', $perPageVal ?? 10) === '20')>20 data</option>
                            <option value="25" @selected((string)request('per_page', $perPageVal ?? 10) === '25')>25 data</option>
                            <option value="50" @selected((string)request('per_page', $perPageVal ?? 10) === '50')>50 data</option>
                            <option value="all" @selected((string)request('per_page', $perPageVal ?? 10) === 'all')>Semua</option>
                        </select>
                    </div>

                    <!-- Input Cari -->
                    <div class="w-44 lg:w-48 shrink-0">
                        <label class="form-label visually-hidden" for="internSearchInput">Cari anak bimbing</label>
                        <input
                            type="search"
                            name="search"
                            id="internSearchInput"
                            value="{{ $searchVal }}"
                            placeholder="Cari nama / asal instansi..."
                            class="h-8 w-full px-3 bg-white border border-slate-300 rounded-md text-xs text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 placeholder:text-slate-400 mentor-filter-control shadow-2xs"
                            data-auto-submit-search="1"
                            autocomplete="off">
                    </div>

                    <!-- Dropdown Status -->
                    <div class="shrink-0">
                        <label class="form-label visually-hidden" for="internStatusFilter">Filter status</label>
                        <select
                            id="internStatusFilter"
                            name="status"
                            class="h-8 px-3 pr-7 min-w-[125px] bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-2xs mentor-filter-control"
                            data-auto-submit-select="1"
                            aria-label="Filter status keaktifan"
                            onchange="this.form.submit()">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>

                    <!-- Dropdown Tahun -->
                    <div class="shrink-0">
                        <label class="form-label visually-hidden" for="internYearFilter">Filter tahun</label>
                        <select
                            id="internYearFilter"
                            name="year"
                            class="h-8 px-3 pr-7 min-w-[125px] bg-white border border-slate-300 rounded-md text-xs font-medium text-slate-700 focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer shadow-2xs mentor-filter-control"
                            data-auto-submit-select="1"
                            aria-label="Filter tahun pelaksanaan"
                            onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @if(isset($availableYears))
                                @foreach($availableYears as $y)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Tombol Reset (Hanya Muncul Jika Ada Filter & Dibuat Ultra-Compact) -->
                    @if($hasSearch || $activeStatus !== 'all' || !empty($year) || $perPageVal !== '10')
                        <a href="{{ route('pembimbing.dashboard') }}" class="h-8 px-2.5 inline-flex items-center gap-1 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-md text-xs font-semibold transition-all shrink-0 text-decoration-none shadow-2xs" title="Reset filter">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                    @endif

                    <!-- Tombol Ekspor PDF -->
                    <a href="{{ route('pembimbing.dashboard.export-pdf', request()->query()) }}" target="_blank" class="h-8 px-3 inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white rounded-md text-xs font-semibold shadow-2xs transition-all shrink-0 text-decoration-none" title="Ekspor Laporan PDF Sesuai Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Ekspor PDF
                    </a>
                </div>
            </div>
        </form>

        <div class="card-body p-2.5">
            @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                <div class="mb-2 d-flex flex-wrap align-items-center gap-1.5 text-xs">
                    <span class="text-muted" style="font-size: 0.72rem;">Filter aktif:</span>
                    @if($hasSearch)
                        <span class="badge rounded-pill px-2 py-0.5" style="background-color: #e0e7ff !important; color: #3730a3 !important; font-weight: 600; font-size: 0.7rem;">
                            <i class="bi bi-search me-1"></i>Kata kunci: {{ $searchVal }}
                        </span>
                    @endif
                    @if($activeStatus !== 'all')
                        <span class="badge rounded-pill px-2 py-0.5" style="background-color: {{ $activeStatus === 'active' ? '#d1fae5 !important; color: #065f46' : '#fee2e2 !important; color: #991b1b' }}; font-weight: 600; font-size: 0.7rem;">
                            <i class="bi bi-funnel me-1"></i>Status: {{ $activeStatus === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    @endif
                    @if($perPageVal !== '10')
                        <span class="badge rounded-pill px-2 py-0.5" style="background-color: #e0f2fe !important; color: #075985 !important; font-weight: 600; font-size: 0.7rem;">
                            <i class="bi bi-list-ul me-1"></i>Per halaman: {{ $perPageVal === 'all' ? 'Semua' : $perPageVal }}
                        </span>
                    @endif
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.8125rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center py-1.5 px-2 text-nowrap" style="width: 45px;">No</th>
                            <th class="py-1.5 px-2" style="width: 32%;">Nama</th>
                            <th class="py-1.5 px-2">Asal Instansi</th>
                            <th class="text-center py-1.5 px-2 text-nowrap" style="width: 110px;">Status</th>
                            <th class="text-center py-1.5 px-2 text-nowrap" style="width: 125px;">Absensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interns as $intern)
                            <tr>
                                <td class="text-center text-muted fw-semibold py-1.5 px-2">
                                    {{ $interns->firstItem() + $loop->index }}
                                </td>
                                <td class="py-1.5 px-2 text-start">
                                    <div class="d-flex flex-column text-start">
                                        <div class="fw-semibold text-gray-900 dark:text-white text-truncate" style="color: #0f172a !important; font-size: 0.8125rem; line-height: 1.25;">
                                            {{ $intern->name }}
                                        </div>
                                        <small class="text-muted text-truncate" style="font-size: 0.7rem; line-height: 1.2;">
                                            {{ $intern->email }}
                                            @if(!empty($intern->user?->profile?->jurusan))
                                                <span class="mx-1 text-gray-300">•</span>
                                                {{ $intern->user?->profile?->jurusan }}
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td class="py-1.5 px-2">
                                    @if(!empty($intern->institution) && $intern->institution !== '-')
                                        <div class="d-flex flex-column align-items-start text-start">
                                            <span class="fw-medium d-flex align-items-center gap-1.5 text-truncate" style="color: #0f172a !important; font-size: 0.8125rem; line-height: 1.25;">
                                                <i class="bi bi-building flex-shrink-0 text-muted" style="font-size: 0.75rem;"></i>
                                                {{ $intern->institution }}
                                            </span>
                                            @if(!empty($intern->periode_label))
                                                <span class="text-muted d-flex align-items-center gap-1.5 mt-0.5 text-truncate" style="font-size: 0.6875rem; line-height: 1.2;">
                                                    <i class="bi bi-calendar3 flex-shrink-0"></i>
                                                    {{ $intern->periode_label }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small" style="font-size: 0.75rem;">Tidak ada data instansi</span>
                                    @endif
                                </td>
                                <td class="text-center py-1.5 px-2 text-nowrap">
                                    @if($intern->operational_status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            Aktif Magang
                                        </span>
                                    @elseif($intern->operational_status === 'upcoming')
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                            Belum Mulai
                                        </span>
                                    @elseif($intern->operational_status === 'terminated' || !empty($intern->is_terminated))
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 border border-rose-200">
                                            Dinonaktifkan / Berhenti
                                        </span>
                                    @elseif($intern->operational_status === 'completed')
                                        @if(!empty($intern->certificate_path))
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                Selesai &amp; Sertifikat Terbit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800 border border-slate-200">
                                                Selesai
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $intern->operational_status_label }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center py-1.5 px-2 text-nowrap">
                                    <a href="{{ route('pembimbing.attendance.show', $intern) }}"
                                       class="btn btn-sm d-inline-flex align-items-center justify-content-center gap-1 fw-semibold py-1 px-2 text-nowrap"
                                       style="background-color: #2563eb !important; color: #ffffff !important; border-radius: 0.375rem; font-size: 0.75rem; white-space: nowrap;">
                                       <i class="bi bi-calendar2-check" style="font-size: 0.75rem;"></i>
                                       Rekap Absensi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-2.5">
                                    <div class="d-flex flex-column align-items-center gap-1.5">
                                        <i class="bi bi-inbox fs-2 text-muted"></i>
                                        <div class="fw-semibold text-muted small" style="font-size: 0.78rem;">
                                            @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                                                Tidak ada anak bimbing yang cocok dengan filter yang diterapkan.
                                            @else
                                                Belum ada anak bimbing di divisi ini.
                                            @endif
                                        </div>
                                        @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                                            <a href="{{ route('pembimbing.dashboard') }}" class="btn btn-sm mt-1 py-1 px-2.5 text-xs" style="background-color: #2563eb !important; color: #ffffff !important; border-radius: 0.375rem; font-weight: 600; font-size: 0.75rem;">
                                                Hapus filter
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($interns->hasPages() && !$isAllPerPage)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2 pt-2 border-top small">
                    <div class="text-muted" style="font-size: 0.75rem;">
                        Menampilkan
                        <span class="fw-semibold text-gray-900 dark:text-white" style="color: #0f172a !important;">{{ $interns->firstItem() }}</span>
                        s/d
                        <span class="fw-semibold text-gray-900 dark:text-white" style="color: #0f172a !important;">{{ $interns->lastItem() }}</span>
                        dari total
                        <span class="fw-semibold text-gray-900 dark:text-white" style="color: #0f172a !important;">{{ $interns->total() }}</span>
                        anak bimbing
                    </div>
                    <div>
                        {{ $interns->onEachSide(1)->links() }}
                    </div>
                </div>
            @elseif($isAllPerPage)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-2 pt-2 border-top small">
                    <div class="text-muted" style="font-size: 0.75rem;">
                        Menampilkan
                        <span class="fw-semibold text-gray-900 dark:text-white" style="color: #0f172a !important;">{{ $interns->total() }}</span>
                        dari total
                        <span class="fw-semibold text-gray-900 dark:text-white" style="color: #0f172a !important;">{{ $interns->total() }}</span>
                        anak bimbing (Semua data)
                    </div>
                    <div class="text-muted">
                        <span class="badge rounded-pill px-2 py-0.5" style="background-color: #e0f2fe !important; color: #075985 !important; font-weight: 600; font-size: 0.7rem;">
                            <i class="bi bi-list-ul me-1"></i>Mode Semua Data
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
(function () {
    var SEARCH_DEBOUNCE_MS = 350;
    var form = document.getElementById('mentorInternFilterForm');
    if (!form) return;

    var searchInput = form.querySelector('[data-auto-submit-search="1"]');
    var selects = form.querySelectorAll('[data-auto-submit-select="1"]');
    var searchTimer = null;

    function triggerSubmit() {
        if (searchTimer) {
            clearTimeout(searchTimer);
            searchTimer = null;
        }
        try {
            form.requestSubmit();
        } catch (e) {
            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && typeof submitBtn.click === 'function') {
                submitBtn.click();
            } else {
                if (typeof form.submit === 'function') form.submit();
            }
        }
    }

    if (selects && selects.length) {
        selects.forEach(function (sel) {
            sel.addEventListener('change', function () {
                triggerSubmit();
            });
        });
    }

    if (searchInput) {
        var fireChange = function () {
            var val = typeof searchInput.value === 'string' ? searchInput.value.trim() : '';
            if (searchTimer) clearTimeout(searchTimer);
            searchTimer = setTimeout(function () {
                searchTimer = null;
                triggerSubmit();
            }, SEARCH_DEBOUNCE_MS);
        };
        searchInput.addEventListener('input', fireChange);
        searchInput.addEventListener('search', function () {
            if (searchTimer) clearTimeout(searchTimer);
            searchTimer = null;
            triggerSubmit();
        });
        searchInput.addEventListener('keydown', function (e) {
            if (e && (e.key === 'Enter' || e.keyCode === 13)) {
                e.preventDefault();
                if (searchTimer) clearTimeout(searchTimer);
                searchTimer = null;
                triggerSubmit();
                return false;
            }
        });
    }

    form.addEventListener('submit', function () {
        if (searchTimer) {
            clearTimeout(searchTimer);
            searchTimer = null;
        }
    });
})();
</script>
@endsection
