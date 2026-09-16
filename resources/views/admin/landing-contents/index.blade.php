@extends('layouts.admin')

@section('title', 'Kelola Konten Landing Page')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
        <div>
            <h1 class="h3 fw-bold text-slate-800 mb-1">Kelola Konten Landing Page</h1>
            <p class="text-muted mb-0">Kelola daftar item dinamis untuk bagian Tentang, Keunggulan, Alur Pendaftaran, dan FAQ.</p>
        </div>
        <a href="{{ route('admin.landing-contents.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-lg px-3 py-2 shadow-sm font-medium">
            <i class="bi bi-plus-lg"></i> Tambah Konten Baru
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-xl shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Top Statistics Row (Ultra-Compact, 4 Columns) -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Konten -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Konten</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['total'] ?? $totalKonten ?? 0 }}</h5>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-collection-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Status Aktif -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Status Aktif</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['active'] ?? $statusAktif ?? 0 }}</h5>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Alur -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Alur</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['workflow'] ?? $alur ?? 0 }}</h5>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-diagram-3-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: FAQ -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">FAQ</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $statistics['faq'] ?? $faq ?? 0 }}</h5>
                    </div>
                    <div style="color: #6f42c1;">
                        <i class="bi bi-question-circle-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div class="card border-0 shadow-sm rounded-xl mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('admin.landing-contents.index') }}" class="row g-2 align-items-center">
                <!-- 1. Search Input (Wider) -->
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white text-muted border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Cari judul atau deskripsi... (Tekan Enter)" value="{{ request('search') }}">
                    </div>
                </div>

                <!-- 2. Category Dropdown (Auto-submit) -->
                <div class="col-12 col-md-4">
                    <select name="section" class="form-select text-muted" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($sections as $key => $label)
                            <option value="{{ $key }}" {{ request('section') === $key ? 'selected' : '' }}>
                                {{ $label }} ({{ $statistics[$key] ?? 0 }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- 3. Per Page Dropdown (Auto-submit) -->
                <div class="col-12 col-md-3">
                    <select name="per_page" class="form-select text-muted" onchange="this.form.submit()">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 data per halaman</option>
                        <option value="10" {{ request('per_page', 10) == '10' ? 'selected' : '' }}>10 data per halaman</option>
                        <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15 data per halaman</option>
                        <option value="20" {{ request('per_page') == '20' ? 'selected' : '' }}>20 data per halaman</option>
                        <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50 data per halaman</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Content List Table --}}
    <div class="card border-0 shadow-sm rounded-xl overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 70px;" class="text-center">Urutan</th>
                        <th style="width: 140px;">Kategori</th>
                        <th style="width: 70px;" class="text-center">Ikon</th>
                        <th>Judul & Deskripsi</th>
                        <th style="width: 110px;" class="text-center">Status</th>
                        <th style="width: 140px;" class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($contents as $item)
                        <tr>
                            <td class="text-center">
                                <span class="badge bg-slate-100 text-slate-700 border font-mono px-2 py-1 rounded">
                                    #{{ $item->order }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($item->section) {
                                        'about'     => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        'advantage' => 'bg-info-subtle text-info border border-info-subtle',
                                        'workflow'  => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                                        'faq'       => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                                        default     => 'bg-light text-dark',
                                    };
                                    $sectionLabel = match($item->section) {
                                        'about'     => 'TENTANG',
                                        'advantage' => 'KEUNGGULAN',
                                        'workflow'  => 'ALUR',
                                        'faq'       => 'FAQ',
                                        default     => strtoupper($item->section),
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-1 font-semibold text-uppercase" style="font-size: 0.7rem;">
                                    {{ $sectionLabel }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if ($item->icon)
                                    @if (str_starts_with($item->icon, 'bi-'))
                                        <div class="d-inline-flex align-items-center justify-content-center rounded bg-slate-100 text-primary" style="width: 34px; height: 34px;">
                                            <i class="bi {{ $item->icon }} fs-5"></i>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-dark text-truncate" style="max-width: 80px;" title="{{ $item->icon }}">
                                            {{ $item->icon }}
                                        </span>
                                    @endif
                                @else
                                    <span class="text-muted">&mdash;</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-slate-800 mb-1">{{ $item->title }}</div>
                                @if ($item->description)
                                    <div class="text-muted text-xs line-clamp-2" style="max-width: 500px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $item->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        <i class="bi bi-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1">
                                        <i class="bi bi-x-circle me-1"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.landing-contents.edit', $item->id) }}" class="btn btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.landing-contents.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-slate-300"></i>
                                Tidak ada data konten yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($contents->hasPages())
            <div class="card-footer bg-white border-top p-3">
                {{ $contents->links() }}
            </div>
        @endif
    </div>
@endsection
