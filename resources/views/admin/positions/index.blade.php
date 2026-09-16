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
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 fw-bold">Kelola Posisi Magang</h1>
            <p class="text-muted mb-0">Tambah, ubah, atau nonaktifkan posisi magang untuk Peserta Magang Diskominfo Tuban.</p>
        </div>
        <a href="{{ route('admin.positions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium rounded-lg shadow-sm transition-colors text-decoration-none">
            <i class="bi bi-plus-lg me-1"></i> Tambah Posisi
        </a>

    </div>

    {{-- Statistic Cards (Ultra-Compact) --}}
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Posisi -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Posisi</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['total'] ?? $totalPosisi ?? 0 }}</h5>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-briefcase-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Posisi Aktif -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Posisi Aktif</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['aktif'] ?? $posisiAktif ?? 0 }}</h5>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Posisi Nonaktif -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Posisi Nonaktif</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['nonaktif'] ?? $posisiNonaktif ?? 0 }}</h5>
                    </div>
                    <div class="text-secondary">
                        <i class="bi bi-dash-circle-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + Filter (Auto-Submit) --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('admin.positions.index') }}" method="GET" class="mb-0">
                <div class="row g-2 align-items-center">
                    <!-- 1. Search Input (Wider) -->
                    <div class="col-12 col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari berdasarkan nama, deskripsi, slug, atau kualifikasi... (Tekan Enter)" value="{{ request('search', $filters['search'] ?? '') }}">
                        </div>
                    </div>

                    <!-- 2. Per Page Dropdown (Auto-submit) -->
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center">
                            <span class="text-muted small me-2 text-nowrap d-none d-lg-inline">Per Halaman:</span>
                            <select name="per_page" class="form-select text-muted" onchange="this.form.submit()">
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
    <div class="card border shadow-sm">
        <div class="w-full overflow-x-auto overflow-y-hidden border border-slate-200 rounded-xl table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 60px;">No.</th>
                        <th scope="col" style="min-width: 350px;" class="min-w-[350px]">
                            <a href="{{ sortUrl('nama_posisi', $currentSort, $nextDir, $filters) }}" class="text-body text-decoration-none">
                                Nama Posisi {!! sortIcon('nama_posisi', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th scope="col" style="width: 120px;">
                            Pendaftar
                        </th>
                        <th scope="col" style="width: 120px;">
                            <a href="{{ sortUrl('status', $currentSort, $nextDir, $filters) }}" class="text-body text-decoration-none">
                                Status {!! sortIcon('status', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th scope="col" style="width: 170px;">
                            <a href="{{ sortUrl('updated_at', $currentSort, $nextDir, $filters) }}" class="text-body text-decoration-none">
                                Terakhir Diperbarui {!! sortIcon('updated_at', $currentSort, $currentDir) !!}
                            </a>
                        </th>
                        <th scope="col" class="text-end" style="width: 210px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($positions as $position)
                        <tr>
                            <th scope="row" class="fw-medium text-muted">{{ ($positions->firstItem() ?? 1) + $loop->index }}</th>
                            <td style="min-width: 350px;" class="min-w-[350px]">
                                <div class="text-slate-900 font-semibold text-base mb-1.5">{{ $position->nama_posisi }}</div>
                                @if(!empty($position->mentor_name))
                                    <div class="d-flex align-items-start gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600">
                                        <i class="bi bi-person-badge text-indigo-600 fs-6 mt-0.5 flex-shrink-0"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold text-slate-800">{{ $position->mentor_name }}</div>
                                            @if(!empty($position->mentor_nip))
                                                <small class="text-slate-500 font-mono d-block mt-0.5">NIP: {{ $position->mentor_nip }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>


                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 fs-6">
                                    <i class="bi bi-people me-1"></i>{{ $position->registrations()->count() }} pendaftar
                                </span>
                            </td>
                            <td>
                                @if ($position->status->isAktif())
                                    <span class="badge bg-success rounded-pill px-3 py-1">
                                        <i class="bi bi-toggle-on me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-1">
                                        <i class="bi bi-toggle-off me-1"></i> Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $position->updated_at?->translatedFormat('d F Y') ?? '-' }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex items-center gap-1.5" role="group" aria-label="Aksi Posisi">
                                    @if ($position->status->isAktif())
                                        <a
                                            href="{{ route('admin.positions.toggle-status', $position) }}"
                                            class="inline-flex items-center justify-center p-2 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 hover:text-amber-900 border border-amber-200 rounded-lg transition-colors text-decoration-none"
                                            title="Nonaktifkan Posisi"
                                            onclick="return confirm('Anda yakin ingin menonaktifkan posisi {{ $position->nama_posisi }}?');"
                                        >
                                            <i class="bi bi-power"></i>
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('admin.positions.toggle-status', $position) }}"
                                            class="inline-flex items-center justify-center p-2 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 hover:text-emerald-900 border border-emerald-200 rounded-lg transition-colors text-decoration-none"
                                            title="Aktifkan Posisi"
                                            onclick="return confirm('Anda yakin ingin mengaktifkan posisi {{ $position->nama_posisi }}?');"
                                        >
                                            <i class="bi bi-power"></i>
                                        </a>
                                    @endif
                                    <a
                                        href="{{ route('admin.positions.edit', $position) }}"
                                        class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 hover:text-blue-800 border border-blue-200 rounded-lg transition-colors text-decoration-none"
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
                                        <button type="submit" class="inline-flex items-center justify-center p-2 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 hover:text-red-800 border border-red-200 rounded-lg transition-colors" title="Hapus Posisi">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
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
            <div class="card-footer border-0 bg-transparent pt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="small text-muted">
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
