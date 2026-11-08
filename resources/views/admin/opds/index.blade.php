@extends('layouts.admin')

@section('title', 'Manajemen Master OPD — Admin')

@section('content')
<div class="space-y-3">
    <!-- Header Page: Title & Action Button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
                <i class="bi bi-buildings-fill text-primary"></i>
                Manajemen Master OPD
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2 shrink-0 ms-auto">
            <a href="{{ route('admin.opds.export_pdf', request()->query()) }}" class="btn btn-sm btn-outline-danger py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm d-inline-flex align-items-center gap-1.5 text-decoration-none" title="Ekspor data OPD ke file PDF sesuai filter yang aktif">
                <i class="bi bi-file-earmark-pdf"></i>
                Ekspor PDF
            </a>
            <a href="{{ route('admin.opds.create') }}" class="btn btn-sm btn-primary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm d-inline-flex align-items-center gap-1.5 text-decoration-none">
                <i class="bi bi-plus-lg"></i>
                Tambah OPD Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 text-xs rounded-lg mb-2.5" role="alert">
            <i class="bi bi-check-circle-fill text-emerald-600 fs-6"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- 4 Metrics Statistic Cards Row (Strictly 1 Line / Segaris) -->
    <div class="row g-2 mb-2.5">
        <!-- Card 1: Total OPD -->
        <div class="col-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-100 bg-white dark:bg-slate-800 py-2 px-2.5 sm:px-3">
                <div class="d-flex align-items-center justify-content-between gap-1.5">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5 truncate">TOTAL OPD</p>
                        <span class="text-base sm:text-xl font-extrabold text-slate-900 dark:text-white leading-none block">{{ number_format($statistics['total_opd'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                        <i class="bi bi-buildings text-primary text-xs sm:text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: OPD Aktif -->
        <div class="col-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-100 bg-white dark:bg-slate-800 py-2 px-2.5 sm:px-3">
                <div class="d-flex align-items-center justify-content-between gap-1.5">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5 truncate">OPD AKTIF</p>
                        <span class="text-base sm:text-xl font-extrabold text-slate-900 dark:text-white leading-none block">{{ number_format($statistics['active_opd'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                        <i class="bi bi-check-circle-fill text-primary text-xs sm:text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Formasi -->
        <div class="col-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-100 bg-white dark:bg-slate-800 py-2 px-2.5 sm:px-3">
                <div class="d-flex align-items-center justify-content-between gap-1.5">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5 truncate">FORMASI MAGANG</p>
                        <span class="text-base sm:text-xl font-extrabold text-slate-900 dark:text-white leading-none block">{{ number_format($statistics['total_positions'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                        <i class="bi bi-briefcase-fill text-primary text-xs sm:text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Peserta Aktif -->
        <div class="col-3">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-100 bg-white dark:bg-slate-800 py-2 px-2.5 sm:px-3">
                <div class="d-flex align-items-center justify-content-between gap-1.5">
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5 truncate">PESERTA AKTIF</p>
                        <span class="text-base sm:text-xl font-extrabold text-slate-900 dark:text-white leading-none block">{{ number_format($statistics['active_interns'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                        <i class="bi bi-people-fill text-primary text-xs sm:text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar Card -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.opds.index') }}" class="row g-2 align-items-center mb-0" role="search">
                <!-- Search Input -->
                <div class="col-12 col-md-6 col-lg-7">
                    <div class="input-group">
                        <span class="input-group-text bg-white dark:bg-slate-700 text-muted border-end-0 py-1.5 px-2.5 text-xs">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama OPD, kode singkatan, atau nama kepala dinas / NIP..." class="form-control form-control-sm text-xs py-1.5 px-2.5 border-start-0 ps-0">
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="status" class="form-select form-select-sm text-xs py-1.5 px-2.5 font-semibold text-slate-700 dark:text-slate-200" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif Saja</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif Saja</option>
                    </select>
                </div>

                <!-- Per Page -->
                <div class="col-6 col-md-3 col-lg-2">
                    <select name="perPage" class="form-select form-select-sm text-xs py-1.5 px-2.5 font-semibold text-slate-700 dark:text-slate-200" onchange="this.form.submit()">
                        <option value="5" {{ (string)request('perPage', $perPage ?? 10) === '5' ? 'selected' : '' }}>5 baris</option>
                        <option value="10" {{ (string)request('perPage', $perPage ?? 10) === '10' ? 'selected' : '' }}>10 baris</option>
                        <option value="15" {{ (string)request('perPage', $perPage ?? 10) === '15' ? 'selected' : '' }}>15 baris</option>
                        <option value="20" {{ (string)request('perPage', $perPage ?? 10) === '20' ? 'selected' : '' }}>20 baris</option>
                        <option value="25" {{ (string)request('perPage', $perPage ?? 10) === '25' ? 'selected' : '' }}>25 baris</option>
                        <option value="50" {{ (string)request('perPage', $perPage ?? 10) === '50' ? 'selected' : '' }}>50 baris</option>
                        <option value="100" {{ (string)request('perPage', $perPage ?? 10) === '100' ? 'selected' : '' }}>100 baris</option>
                        <option value="all" {{ (string)request('perPage') === 'all' || (int)($perPage ?? 10) >= 1000 ? 'selected' : '' }}>Semua (All)</option>
                    </select>
                </div>

                <!-- Reset Filter Button -->
                @if(request('search') || request('status') || (request('perPage') && request('perPage') != 10))
                    <div class="col-12 col-lg-1 d-flex align-items-center">
                        <a href="{{ route('admin.opds.index') }}" class="btn btn-sm btn-outline-danger py-1.5 px-2.5 text-xs font-semibold w-100 d-inline-flex align-items-center justify-center gap-1" title="Reset Pencarian & Filter">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Main Data Table Container -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg bg-white dark:bg-slate-800">
        <div class="w-full overflow-x-auto rounded-t-lg table-responsive mb-0">
            <table class="table table-hover align-middle mb-0 w-full text-xs">
                <thead class="table-light border-bottom text-muted">
                    <tr>
                        <th scope="col" class="ps-3 py-2 text-center" style="width: 50px;">NO</th>
                        <th scope="col" class="py-2" style="min-width: 280px;">NAMA OPD & KODE</th>
                        <th scope="col" class="py-2" style="min-width: 200px;">KEPALA DINAS / NIP</th>
                        <th scope="col" class="py-2 text-center" style="width: 90px;">FORMASI</th>
                        <th scope="col" class="py-2 text-center" style="width: 110px;">PESERTA AKTIF</th>
                        <th scope="col" class="py-2 text-center" style="width: 80px;">ADMIN</th>
                        <th scope="col" class="py-2 text-center" style="width: 95px;">STATUS</th>
                        <th scope="col" class="pe-3 py-2 text-end" style="width: 140px; min-width: 140px;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($opds as $opd)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="ps-3 py-2.5 text-center text-muted font-monospace text-xs">
                                {{ $loop->iteration + $opds->firstItem() - 1 }}
                            </td>
                            <td class="py-2.5">
                                <div class="fw-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm mb-1 leading-snug">
                                    {{ $opd->name }}
                                </div>
                                <div class="d-flex align-items-center gap-2 flex-wrap text-muted">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-600">
                                        {{ $opd->code }}
                                    </span>
                                    @if($opd->email)
                                        <span class="text-xs text-slate-500 dark:text-slate-400 d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-envelope"></i> {{ $opd->email }}
                                        </span>
                                    @endif
                                    @if($opd->phone)
                                        <span class="text-xs text-slate-500 dark:text-slate-400 d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-telephone"></i> {{ $opd->phone }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5">
                                @if($opd->head_of_office_name)
                                    <div class="fw-semibold text-slate-800 dark:text-slate-200 text-xs mb-0.5">
                                        {{ $opd->head_of_office_name }}
                                    </div>
                                    <div class="text-muted text-[11px] font-monospace d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-person-badge"></i>
                                        {{ $opd->head_of_office_nip ? 'NIP: ' . $opd->head_of_office_nip : 'NIP: -' }}
                                    </div>
                                @else
                                    <span class="text-muted fst-italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-2.5 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800" title="{{ $opd->positions_count ?? 0 }} Formasi Magang">
                                    {{ $opd->positions_count ?? 0 }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800" title="{{ $opd->active_interns_count ?? 0 }} Peserta Magang Aktif">
                                    {{ $opd->active_interns_count ?? 0 }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center">
                                <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800" title="{{ $opd->admins_count ?? 0 }} Akun Admin">
                                    {{ $opd->admins_count ?? 0 }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center whitespace-nowrap">
                                @if($opd->is_active)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700">
                                        <i class="bi bi-check-circle-fill text-[10px]"></i> Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400 border border-slate-300 dark:border-slate-600">
                                        <i class="bi bi-x-circle-fill text-[10px]"></i> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="pe-3 py-2.5 text-end whitespace-nowrap" style="width: 140px; min-width: 140px;">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1.5">
                                    <a href="{{ route('admin.opds.edit', $opd->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 text-xs font-semibold d-inline-flex align-items-center gap-1 rounded" title="Edit Data OPD">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>
                                    <a href="{{ route('admin.opds.toggle-status', $opd->id) }}" class="btn btn-sm {{ $opd->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} py-1 px-2 text-xs font-semibold d-inline-flex align-items-center rounded" title="{{ $opd->is_active ? 'Nonaktifkan OPD' : 'Aktifkan OPD' }}" onclick="return confirm('Apakah Anda yakin ingin {{ $opd->is_active ? 'menonaktifkan' : 'mengaktifkan' }} OPD {{ addslashes($opd->name) }}?')">
                                        <i class="bi {{ $opd->is_active ? 'bi-toggle-on text-danger' : 'bi-toggle-off text-success' }} fs-6"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="bi bi-buildings text-3xl mb-1 block"></i>
                                Belum ada Master Data OPD yang sesuai filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($opds->hasPages())
            <div class="card-footer py-2.5 px-3 bg-white dark:bg-slate-800 border-top border-slate-200 dark:border-slate-700">
                {{ $opds->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
