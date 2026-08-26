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
        <a href="{{ route('admin.positions.create') }}" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Tambah Posisi
        </a>
    </div>

    {{-- Statistic Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Total Posisi</p>
                            <h3 class="fw-bold mb-0">{{ $statistics['total'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary-subtle rounded-circle p-3">
                            <i class="bi bi-briefcase text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Posisi Aktif</p>
                            <h3 class="fw-bold text-success mb-0">{{ $statistics['aktif'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success-subtle rounded-circle p-3">
                            <i class="bi bi-toggle-on text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Posisi Nonaktif</p>
                            <h3 class="fw-bold text-secondary mb-0">{{ $statistics['nonaktif'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-secondary-subtle rounded-circle p-3">
                            <i class="bi bi-toggle-off text-secondary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + Filter --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.positions.index') }}" class="row g-3 align-items-end">
                <div class="col-md-7">
                    <label for="search" class="form-label">Cari Posisi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search text-muted"></i></span>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            placeholder="Cari berdasarkan nama, deskripsi, slug, atau kualifikasi..."
                            value="{{ $filters['search'] ?? '' }}"
                        >
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="per_page" class="form-label">Tampilkan per Halaman</label>
                    <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') === '10' ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') === '25' ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') === '50' ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') === '100' ? 'selected' : '' }}>100</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel me-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="card border shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 60px;">No.</th>
                        <th scope="col">
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
                            <td>
                                <div class="fw-semibold">{{ $position->nama_posisi }}</div>
                                <small class="text-muted d-block mb-1"><code>{{ $position->slug }}</code></small>
                                @if(!empty($position->mentor_name))
                                    <div class="text-primary small d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-person-badge me-1"></i>
                                        <strong>Pembimbing:</strong> {{ $position->mentor_name }}
                                        @if(!empty($position->mentor_nip))
                                            <span class="text-muted">(NIP: {{ $position->mentor_nip }})</span>
                                        @endif
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
                                <div class="btn-group btn-group-sm" role="group" aria-label="Aksi Posisi">
                                    <a
                                        href="{{ route('admin.positions.toggle-status', $position) }}"
                                        class="btn btn-outline-{{ $position->status->isAktif() ? 'warning' : 'success' }}"
                                        title="{{ $position->status->isAktif() ? 'Nonaktifkan' : 'Aktifkan' }}"
                                        onclick="return confirm('Anda yakin ingin {{ $position->status->isAktif() ? 'menonaktifkan' : 'mengaktifkan' }} posisi {{ $position->nama_posisi }}?');"
                                    >
                                        <i class="bi bi-power"></i>
                                    </a>
                                    <a
                                        href="{{ route('admin.positions.edit', $position) }}"
                                        class="btn btn-outline-primary"
                                        title="Edit Posisi"
                                    >
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.positions.destroy', $position) }}"
                                        onsubmit="return confirm('PERINGATAN: Posisi ini akan dihapus. Jika posisi sudah memiliki pendaftar, hapus akan gagal. Yakin menghapus posisi \'{{ $position->nama_posisi }}\'?');"
                                        style="display: inline-block;"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus Posisi">
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
