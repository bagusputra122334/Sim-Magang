@extends('layouts.admin')

@section('title', 'Kelola Posisi Magang')

@php
    $currentSort = $filters['sort'] ?? 'id';
    $currentDir = $filters['direction'] ?? 'desc';
    $nextDir = $currentDir === 'asc' ? 'desc' : 'asc';

    if (! function_exists('sortUrl')) {
        function sortUrl($column, $currentSort, $nextDir, $filters): string
        {
            $filters['sort'] = $column;
            $filters['direction'] = ($currentSort === $column && ($filters['direction'] ?? 'desc') === 'asc')
                ? 'desc'
                : 'asc';

            return request()->fullUrlWithQuery($filters);
        }
    }

    if (! function_exists('sortIcon')) {
        function sortIcon($column, $currentSort, $currentDir): string
        {
            if ($currentSort !== $column) {
                return '<i class="bi bi-arrow-down-up text-muted ms-1"></i>';
            }

            return $currentDir === 'asc'
                ? '<i class="bi bi-sort-up text-primary ms-1"></i>'
                : '<i class="bi bi-sort-down text-primary ms-1"></i>';
        }
    }
@endphp

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-2.5">
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                Kelola Posisi Magang
            </h1>
        </div>
        <a href="{{ route('admin.positions.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-xs font-bold rounded-lg shadow-sm transition-colors text-decoration-none">
            <i class="bi bi-plus-lg"></i> Tambah Posisi
        </a>
    </div>

    {{-- Statistic Cards (Ultra-Compact - Wajib 1 Baris di Layar Desktop) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-2.5">
        <!-- Card 1: Total Posisi -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">Total Posisi</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['total'] ?? $totalPosisi ?? 0 }}</span>
                </div>
                <div class="text-primary rounded bg-blue-100 dark:bg-blue-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-briefcase-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Posisi Aktif -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">Posisi Aktif</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['aktif'] ?? $posisiAktif ?? 0 }}</span>
                </div>
                <div class="text-success rounded bg-emerald-100 dark:bg-emerald-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Posisi Nonaktif -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">Posisi Nonaktif</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['nonaktif'] ?? $posisiNonaktif ?? 0 }}</span>
                </div>
                <div class="text-secondary rounded bg-slate-100 dark:bg-slate-700 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-dash-circle-fill fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + Filter (Auto-Submit) --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form action="{{ route('admin.positions.index') }}" method="GET" class="mb-0">
                <div class="row g-2 align-items-center">
                    <!-- 1. Search Input (Wider) -->
                    <div class="col-12 col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white dark:bg-slate-700 text-muted border-end-0 py-1.5 px-2.5 text-xs"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control form-control-sm text-xs py-1.5 px-2.5 border-start-0 ps-0" placeholder="Cari berdasarkan nama, deskripsi, slug, atau kualifikasi... (Tekan Enter)" value="{{ request('search', $filters['search'] ?? '') }}">
                        </div>
                    </div>

                    <!-- 2. Per Page Dropdown (Auto-submit) -->
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center justify-content-md-end">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 me-2 text-nowrap d-none d-lg-inline">Per Halaman:</span>
                            <select name="per_page" class="form-select form-select-sm text-xs py-1.5 px-2.5 w-auto" onchange="this.form.submit()">
                                <option value="5" {{ request('per_page', $filters['per_page'] ?? '') == '5' ? 'selected' : '' }}>5 data</option>
                                <option value="10" {{ request('per_page', $filters['per_page'] ?? '10') == '10' ? 'selected' : '' }}>10 data</option>
                                <option value="15" {{ request('per_page', $filters['per_page'] ?? '') == '15' ? 'selected' : '' }}>15 data</option>
                                <option value="20" {{ request('per_page', $filters['per_page'] ?? '') == '20' ? 'selected' : '' }}>20 data</option>
                                <option value="25" {{ request('per_page', $filters['per_page'] ?? '') == '25' ? 'selected' : '' }}>25 data</option>
                                <option value="50" {{ request('per_page', $filters['per_page'] ?? '') == '50' ? 'selected' : '' }}>50 data</option>
                                <option value="100" {{ request('per_page', $filters['per_page'] ?? '') == '100' ? 'selected' : '' }}>100 data</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg bg-white dark:bg-slate-800">
        <div class="w-full overflow-x-auto overflow-y-hidden rounded-t-lg table-responsive">
            <table class="table table-hover align-middle mb-0 w-full text-xs">
                <thead class="table-light border-bottom text-muted">
                    <tr>
                        <th scope="col" class="ps-2.5 py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider whitespace-nowrap" style="width: 50px;">No.</th>
                        <th scope="col" class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider" style="min-width: 300px;">
                            <a href="{{ sortUrl('nama_posisi', $currentSort, $nextDir, $filters) }}" class="text-body text-decoration-none">
                                Nama Posisi {!! sortIcon('nama_posisi', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th scope="col" class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider" style="width: 120px;">
                            Pendaftar
                        </th>
                        <th scope="col" class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider" style="width: 120px;">
                            <a href="{{ sortUrl('status', $currentSort, $nextDir, $filters) }}" class="text-body text-decoration-none">
                                Status {!! sortIcon('status', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th scope="col" class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider" style="width: 150px;">
                            <a href="{{ sortUrl('updated_at', $currentSort, $nextDir, $filters) }}" class="text-body text-decoration-none">
                                Terakhir Diperbarui {!! sortIcon('updated_at', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th scope="col" class="pe-2.5 py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($positions as $position)
                        <tr>
                            <td class="ps-2.5 py-1.5 px-2.5 font-medium text-slate-500 whitespace-nowrap text-xs">{{ ($positions->firstItem() ?? 1) + $loop->index }}</td>
                            <td class="py-1.5 px-2.5 text-xs" style="min-width: 300px;">
                                <div class="text-slate-900 dark:text-slate-100 font-bold text-xs sm:text-sm mb-0.5">{{ $position->nama_posisi }}</div>
                                @if(!empty($position->mentor_name))
                                    <div class="d-flex align-items-center gap-1.5 p-1.5 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded text-xs text-slate-600 dark:text-slate-300">
                                        <i class="bi bi-person-badge text-indigo-600"></i>
                                        <span class="fw-semibold">{{ $position->mentor_name }}</span>
                                        @if(!empty($position->mentor_nip))
                                            <span class="text-slate-400 font-mono ms-1">(NIP: {{ $position->mentor_nip }})</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="py-1.5 px-2.5 text-xs">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 text-xs font-semibold">
                                    <i class="bi bi-people me-1"></i>{{ $position->registrations()->count() }} pendaftar
                                </span>
                            </td>
                            <td class="py-1.5 px-2.5 text-xs">
                                @if ($position->status->isAktif())
                                    <span class="badge bg-success rounded-pill px-2.5 py-1 text-xs font-semibold">
                                        <i class="bi bi-toggle-on me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-2.5 py-1 text-xs font-semibold">
                                        <i class="bi bi-toggle-off me-1"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-1.5 px-2.5 text-xs text-muted whitespace-nowrap">
                                {{ $position->updated_at?->translatedFormat('d M Y') ?? '-' }}
                            </td>
                            <td class="pe-2.5 py-1.5 px-2.5 text-xs text-end whitespace-nowrap">
                                <div class="d-inline-flex items-center gap-1" role="group" aria-label="Aksi Posisi">
                                    @if ($position->status->isAktif())
                                        <a
                                            href="{{ route('admin.positions.toggle-status', $position) }}"
                                            class="inline-flex items-center justify-center p-1.5 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 hover:text-amber-900 border border-amber-200 rounded transition-colors text-decoration-none"
                                            title="Nonaktifkan Posisi"
                                            onclick="return confirm('Anda yakin ingin menonaktifkan posisi {{ $position->nama_posisi }}?');"
                                        >
                                            <i class="bi bi-power"></i>
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('admin.positions.toggle-status', $position) }}"
                                            class="inline-flex items-center justify-center p-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-900 border border-emerald-200 rounded transition-colors text-decoration-none"
                                            title="Aktifkan Posisi"
                                            onclick="return confirm('Anda yakin ingin mengaktifkan posisi {{ $position->nama_posisi }}?');"
                                        >
                                            <i class="bi bi-power"></i>
                                        </a>
                                    @endif
                                    <a
                                        href="{{ route('admin.positions.edit', $position) }}"
                                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-800 border border-blue-200 rounded transition-colors text-decoration-none"
                                        title="Edit Posisi"
                                    >
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.positions.destroy', $position) }}"
                                        onsubmit="return confirm('PERINGATAN: Posisi ini akan dihapus. Jika posisi sudah memiliki pendaftar, hapus akan gagal. Yakin menghapus posisi \'{{ $position->nama_posisi }}\'?');"
                                        style="display: inline-block;"
                                        class="m-0 p-0"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center p-1.5 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-800 border border-red-200 rounded transition-colors" title="Hapus Posisi">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                <p class="mb-1 fw-medium">Tidak ada data posisi magang.</p>
                                <small>
                                    @if (! empty($filters['search'] ?? ''))
                                        Coba ganti kata kunci pencarian, atau
                                    @endif
                                    <a href="{{ route('admin.positions.create') }}" class="text-decoration-none">
                                        <i class="bi bi-plus"></i> buat posisi baru
                                    </a>.
                                </small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($positions->hasPages())
            <div class="card-footer border-0 bg-transparent py-2.5 px-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-xs text-muted">
                    Menampilkan
                    <strong>{{ $positions->firstItem() }}</strong>
                    sampai
                    <strong>{{ $positions->lastItem() }}</strong>
                    dari
                    <strong>{{ $positions->total() }}</strong>
                    data posisi.
                </div>
                <div>
                    {{ $positions->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>
@endsection
