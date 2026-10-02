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
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h1 class="h4 mb-0 text-gray-800" style="line-height: 1.2;">Dashboard Pembimbing ({{ $user->division->nama_divisi ?? ($user->division->name ?? '-') }})</h1>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <div class="card shadow h-100" style="border-left: 3px solid #2563eb !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.6875rem; line-height: 1.15; color: #1e40af !important; letter-spacing: 0.05em;">
                                Total Penugasan
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #0f172a !important; line-height: 1.15; font-size: 1.1rem;">
                                {{ $totalPenugasan ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journal-bookmark-fill" style="font-size: 1.25rem; color: #93c5fd !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow h-100" style="border-left: 3px solid #0ea5e9 !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.6875rem; line-height: 1.15; color: #0369a1 !important; letter-spacing: 0.05em;">
                                Total Anak Bimbing
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #0f172a !important; line-height: 1.15; font-size: 1.1rem;">
                                {{ $statsTotalInterns ?? 0 }}
                                <small class="text-muted fw-semibold" style="font-size: 0.65rem;">org</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people-fill" style="font-size: 1.25rem; color: #7dd3fc !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow h-100" style="border-left: 3px solid #10b981 !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.65rem; line-height: 1.15; color: #047857 !important; letter-spacing: 0.05em;">
                                Aktif
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #065f46 !important; line-height: 1.15; font-size: 1.05rem;">
                                {{ $statsActiveInterns ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-check-fill" style="font-size: 1.15rem; color: #6ee7b7 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow h-100" style="border-left: 3px solid #ef4444 !important; border-radius: 0.5rem;">
                <div class="card-body py-2 px-3">
                    <div class="row no-gutters align-items-center g-2">
                        <div class="col mr-2">
                            <div class="text-uppercase fw-bold mb-0.5" style="font-size: 0.65rem; line-height: 1.15; color: #b91c1c !important; letter-spacing: 0.05em;">
                                Nonaktif
                            </div>
                            <div class="h6 mb-0 fw-bold" style="color: #991b1b !important; line-height: 1.15; font-size: 1.05rem;">
                                {{ $statsInactiveInterns ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-x-fill" style="font-size: 1.15rem; color: #fca5a5 !important;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-2">
        <div class="card-header py-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="m-0 font-weight-bold text-primary" style="font-size: 0.95rem;">Daftar Anak Bimbing</h6>

            <form method="GET" action="{{ route('pembimbing.dashboard') }}" class="d-flex flex-wrap align-items-stretch gap-2" id="mentorInternFilterForm" role="search">
                <button type="submit" class="visually-hidden" aria-hidden="true" tabindex="-1">Submit</button>
                <div class="d-flex align-items-stretch" style="min-width: 170px;">
                    <label class="form-label visually-hidden" for="internPerPage">Tampilkan per halaman</label>
                    <select
                        id="internPerPage"
                        name="per_page"
                        class="form-select bg-white mentor-filter-control"
                        data-auto-submit-select="1"
                        aria-label="Jumlah baris per halaman"
                        style="border-radius: 0.5rem; box-shadow: none !important; min-height: 36px; font-size: 0.85rem;">
                        <option value="10" @selected($perPageVal === '10')>Tampilkan 10</option>
                        <option value="20" @selected($perPageVal === '20')>Tampilkan 20</option>
                        <option value="50" @selected($perPageVal === '50')>Tampilkan 50</option>
                        <option value="all" @selected($perPageVal === 'all')>Tampilkan Semua</option>
                    </select>
                </div>

                <div class="d-flex align-items-stretch flex-grow-1" style="min-width: 240px; max-width: 420px;">
                    <label class="form-label visually-hidden" for="internSearchInput">Cari anak bimbing</label>
                    <span class="input-group-text bg-white border-end-0" id="internSearchAddon" style="border-radius: 0.5rem 0 0 0.5rem; font-size: 0.85rem;">
                        <i class="bi bi-search" style="color: #64748b;"></i>
                    </span>
                    <input
                        type="search"
                        name="search"
                        id="internSearchInput"
                        class="form-control border-start-0 border bg-white mentor-filter-control"
                        data-auto-submit-search="1"
                        placeholder="Cari nama / asal instansi ... (ketik otomatis filter)"
                        value="{{ $searchVal }}"
                        aria-label="Cari anak bimbing"
                        aria-describedby="internSearchAddon"
                        autocomplete="off"
                        style="border-radius: 0 0.5rem 0.5rem 0; box-shadow: none !important; font-size: 0.85rem; min-height: 36px;">
                </div>

                <div class="d-flex align-items-stretch" style="min-width: 150px;">
                    <label class="form-label visually-hidden" for="internStatusFilter">Filter status</label>
                    <select
                        id="internStatusFilter"
                        name="status"
                        class="form-select bg-white mentor-filter-control"
                        data-auto-submit-select="1"
                        aria-label="Filter status keaktifan"
                        style="border-radius: 0.5rem; box-shadow: none !important; min-height: 36px; font-size: 0.85rem;">
                        <option value="all" @selected($activeStatus === 'all')>Semua Status</option>
                        <option value="active" @selected($activeStatus === 'active')>Aktif</option>
                        <option value="inactive" @selected($activeStatus === 'inactive')>Nonaktif</option>
                    </select>
                </div>

                @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                    <a href="{{ route('pembimbing.dashboard') }}" class="btn btn-sm px-3 py-2 ms-auto" style="background-color: #64748b !important; color: #ffffff !important; border-radius: 0.5rem; font-weight: 600; font-size: 0.85rem;">
                        <i class="bi bi-x-lg me-1"></i> Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="card-body pt-2 pb-2">
            @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                <div class="mb-2 d-flex flex-wrap align-items-center gap-2 small">
                    <span class="text-muted">Filter aktif:</span>
                    @if($hasSearch)
                        <span class="badge rounded-pill px-3 py-1" style="background-color: #e0e7ff !important; color: #3730a3 !important; font-weight: 600; font-size: 0.72rem;">
                            <i class="bi bi-search me-1"></i>Kata kunci: {{ $searchVal }}
                        </span>
                    @endif
                    @if($activeStatus !== 'all')
                        <span class="badge rounded-pill px-3 py-1" style="background-color: {{ $activeStatus === 'active' ? '#d1fae5 !important; color: #065f46' : '#fee2e2 !important; color: #991b1b' }}; font-weight: 600; font-size: 0.72rem;">
                            <i class="bi bi-funnel me-1"></i>Status: {{ $activeStatus === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    @endif
                    @if($perPageVal !== '10')
                        <span class="badge rounded-pill px-3 py-1" style="background-color: #e0f2fe !important; color: #075985 !important; font-weight: 600; font-size: 0.72rem;">
                            <i class="bi bi-list-ul me-1"></i>Per halaman: {{ $perPageVal === 'all' ? 'Semua' : $perPageVal }}
                        </span>
                    @endif
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 30%;">Nama</th>
                            <th>Asal Instansi</th>
                            <th class="text-center" style="width: 130px;">Status</th>
                            <th class="text-center" style="width: 170px;">Absensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interns as $intern)
                            <tr>
                                <td class="text-center text-muted fw-semibold">
                                    {{ $interns->firstItem() + $loop->index }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0 fw-bold text-white"
                                             style="width: 30px; height: 30px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-size: 0.78rem;">
                                            {{ \Illuminate\Support\Str::limit(mb_substr($intern->name ?? '?', 0, 1), 1, '') }}
                                        </div>
                                        <div class="min-w-0 d-flex flex-column">
                                            <div class="fw-semibold text-gray-900 text-truncate" style="color: #0f172a !important;">
                                                {{ $intern->name }}
                                            </div>
                                            <small class="text-muted text-truncate" style="font-size: 0.72rem;">
                                                {{ $intern->email }}
                                                @if(!empty($intern->user?->profile?->jurusan))
                                                    <span class="mx-1 text-gray-300">•</span>
                                                    {{ $intern->user?->profile?->jurusan }}
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(!empty($intern->institution) && $intern->institution !== '-')
                                        <div class="d-flex flex-column align-items-start text-start">
                                            <span class="fw-medium d-flex align-items-center gap-2 text-truncate" style="color: #0f172a !important;">
                                                <i class="bi bi-building flex-shrink-0" style="color: #475569; font-size: 0.85rem;"></i>
                                                {{ $intern->institution }}
                                            </span>
                                            @if(!empty($intern->periode_label))
                                                <span class="text-muted d-flex align-items-center gap-2 mt-1 text-truncate" style="font-size: 0.7rem;">
                                                    <i class="bi bi-calendar3 flex-shrink-0"></i>
                                                    {{ $intern->periode_label }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted fst-italic small">Tidak ada data instansi</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $intern->operational_status_badge_class }} rounded-pill px-3 py-1 fw-semibold border" style="font-size: 0.72rem;">
                                        @if($intern->operational_status === 'active' || $intern->operational_status === 'upcoming')
                                            <i class="bi bi-dot me-1" style="color: #16a34a !important;"></i>
                                        @else
                                            <i class="bi bi-dot me-1" style="color: #ef4444 !important;"></i>
                                        @endif
                                        {{ $intern->operational_status_label }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('pembimbing.attendance.show', $intern) }}"
                                       class="btn btn-sm d-inline-flex align-items-center justify-content-center gap-1.5 fw-semibold"
                                       style="background-color: #2563eb !important; color: #ffffff !important; border-radius: 0.5rem; min-width: 112px; font-size: 0.75rem;">
                                        <i class="bi bi-calendar2-check" style="font-size: 0.85rem;"></i>
                                        Rekap Absensi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <i class="bi bi-inbox display-6 text-muted"></i>
                                        <div class="fw-semibold text-muted small">
                                            @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                                                Tidak ada anak bimbing yang cocok dengan filter yang diterapkan.
                                            @else
                                                Belum ada anak bimbing di divisi ini.
                                            @endif
                                        </div>
                                        @if($hasSearch || $activeStatus !== 'all' || $perPageVal !== '10')
                                            <a href="{{ route('pembimbing.dashboard') }}" class="btn btn-sm mt-1" style="background-color: #2563eb !important; color: #ffffff !important; border-radius: 0.5rem; font-weight: 600; font-size: 0.82rem;">
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
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-2 border-top small">
                    <div class="text-muted">
                        Menampilkan
                        <span class="fw-semibold" style="color: #0f172a !important;">{{ $interns->firstItem() }}</span>
                        s/d
                        <span class="fw-semibold" style="color: #0f172a !important;">{{ $interns->lastItem() }}</span>
                        dari total
                        <span class="fw-semibold" style="color: #0f172a !important;">{{ $interns->total() }}</span>
                        anak bimbing
                    </div>
                    <div>
                        {{ $interns->onEachSide(1)->links() }}
                    </div>
                </div>
            @elseif($isAllPerPage)
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3 pt-2 border-top small">
                    <div class="text-muted">
                        Menampilkan
                        <span class="fw-semibold" style="color: #0f172a !important;">{{ $interns->total() }}</span>
                        dari total
                        <span class="fw-semibold" style="color: #0f172a !important;">{{ $interns->total() }}</span>
                        anak bimbing (Semua data)
                    </div>
                    <div class="text-muted">
                        <span class="badge rounded-pill px-2 py-1" style="background-color: #e0f2fe !important; color: #075985 !important; font-weight: 600; font-size: 0.7rem;">
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
