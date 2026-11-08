@extends('layouts.admin')

@section('title', 'Manajemen Akun Admin OPD — Admin')

@section('content')
<div class="space-y-3">
    <!-- Header Page: Title & Action Button -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
                <i class="bi bi-people-fill text-primary"></i>
                Manajemen Akun Admin OPD
            </h1>
        </div>
        <div class="shrink-0 ms-auto">
            <a href="{{ route('admin.opd-admins.create') }}" class="btn btn-sm btn-primary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm d-inline-flex align-items-center gap-1.5 text-decoration-none">
                <i class="bi bi-person-plus-fill"></i>
                Tambah Admin Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 text-xs rounded-lg mb-2.5" role="alert">
            <i class="bi bi-check-circle-fill text-emerald-600 fs-6"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 text-xs rounded-lg mb-2.5" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-rose-600 fs-6"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Filter & Search Toolbar Card -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.opd-admins.index') }}" class="row g-2 align-items-center mb-0" role="search">
                <!-- Search Input -->
                <div class="col-12 col-md-5 col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white dark:bg-slate-700 text-muted border-end-0 py-1.5 px-2.5 text-xs">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, email, atau jabatan..." class="form-control form-control-sm text-xs py-1.5 px-2.5 border-start-0 ps-0">
                    </div>
                </div>

                <!-- OPD Filter -->
                <div class="col-6 col-md-4 col-lg-3">
                    <select name="opd_id" class="form-select form-select-sm text-xs py-1.5 px-2.5 font-semibold text-slate-700 dark:text-slate-200" onchange="this.form.submit()">
                        <option value="">Semua Wilayah OPD</option>
                        @foreach($opds as $opd)
                            <option value="{{ $opd->id }}" {{ (string)request('opd_id') === (string)$opd->id ? 'selected' : '' }}>
                                {{ $opd->name }}
                            </option>
                        @endforeach
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
                @if(request('search') || request('opd_id') || (request('perPage') && request('perPage') != 10))
                    <div class="col-12 col-lg-1 d-flex align-items-center">
                        <a href="{{ route('admin.opd-admins.index') }}" class="btn btn-sm btn-outline-danger py-1.5 px-2.5 text-xs font-semibold w-100 d-inline-flex align-items-center justify-center gap-1" title="Reset Pencarian & Filter">
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
                        <th scope="col" class="ps-3 py-2 text-center" style="width: 45px;">NO</th>
                        <th scope="col" class="py-2" style="min-width: 200px;">NAMA ADMINISTRATOR</th>
                        <th scope="col" class="py-2" style="min-width: 170px;">NIP & JABATAN</th>
                        <th scope="col" class="py-2" style="min-width: 220px;">WILAYAH PENUGASAN OPD</th>
                        <th scope="col" class="py-2" style="width: 120px;">DIBUAT PADA</th>
                        <th scope="col" class="pe-3 py-2 text-end" style="width: 120px; min-width: 120px;">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="ps-3 py-2.5 text-center text-muted font-monospace text-xs">
                                {{ $loop->iteration + $admins->firstItem() - 1 }}
                            </td>
                            <td class="py-2.5">
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 d-flex align-items-center justify-content-center fw-bold text-xs flex-shrink-0" style="width: 32px; height: 32px;">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm">
                                            {{ $admin->name }}
                                        </div>
                                        <div class="text-muted text-xs d-inline-flex align-items-center gap-1">
                                            <i class="bi bi-envelope"></i> {{ $admin->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5">
                                <div class="fw-semibold text-slate-800 dark:text-slate-200 text-xs mb-0.5">
                                    {{ $admin->position_title ?? 'Staf Administrator' }}
                                </div>
                                <div class="text-muted text-[11px] font-monospace d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-person-badge"></i>
                                    {{ $admin->nip ? 'NIP: ' . $admin->nip : 'NIP: -' }}
                                </div>
                            </td>
                            <td class="py-2.5">
                                @if($admin->opd)
                                    <div class="fw-bold text-slate-800 dark:text-slate-100 text-xs mb-0.5">
                                        <i class="bi bi-building text-primary me-1"></i>{{ $admin->opd->name }}
                                    </div>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-200 border border-slate-300 dark:border-slate-600">
                                        {{ $admin->opd->code }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic text-xs">Belum Ditugaskan</span>
                                @endif
                            </td>
                            <td class="py-2.5 text-muted text-xs">
                                {{ $admin->created_at ? $admin->created_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="pe-3 py-2.5 text-end whitespace-nowrap" style="width: 120px; min-width: 120px;">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1.5">
                                    <a href="{{ route('admin.opd-admins.edit', $admin->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 text-xs font-semibold d-inline-flex align-items-center gap-1 rounded" title="Edit Akun Admin">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>
                                    <form action="{{ route('admin.opd-admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun admin {{ addslashes($admin->name) }}? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 text-xs font-semibold d-inline-flex align-items-center rounded" title="Hapus Akun Admin">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="bi bi-person-x text-3xl mb-1 block"></i>
                                Belum ada akun Administrator OPD yang sesuai filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($admins->hasPages())
            <div class="card-footer py-2.5 px-3 bg-white dark:bg-slate-800 border-top border-slate-200 dark:border-slate-700">
                {{ $admins->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
