@extends('layouts.admin')

@section('title', !empty($isSuperAdmin) ? 'Monitoring Pembimbing Magang — SIMAGANG' : 'Daftar Pembimbing Magang — SIMAGANG')

@section('content')
@php
    $searchVal = e((string) request('search', ''));
    $perPageVal = (int) request('per_page', request('perPage', $perPage ?? 10));
    $selectedOpdId = request('opd_id', $selectedOpdId ?? null);
    $selectedDivisionId = request('division_id');
    $hasFilter = request()->filled('search') || request()->filled('opd_id') || request()->filled('division_id') || ($perPageVal !== 10 && request()->filled('per_page'));
@endphp

<div class="space-y-3">
    <!-- Top Header: Title & Action Button Segaris Presisi -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
                <i class="bi bi-person-workspace text-primary"></i>
                <span>{{ !empty($isSuperAdmin) ? 'Monitoring Pembimbing Magang Seluruh Tuban' : 'Daftar Pembimbing Magang' }}</span>
            </h1>
        </div>

        <div class="shrink-0 ms-auto d-flex align-items-center gap-2">
            @if(!empty($isSuperAdmin))
                <a href="{{ route('admin.pembimbing.export-pdf', request()->query()) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors text-decoration-none"
                   title="Ekspor Laporan PDF Sesuai Filter">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Ekspor PDF</span>
                </a>
            @else
                <a href="{{ route('admin.pembimbing.create') }}" class="btn btn-sm btn-primary py-1.5 px-3 text-xs font-bold rounded-lg shadow-sm d-inline-flex align-items-center gap-1.5 text-decoration-none">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Pembimbing</span>
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 px-3 text-xs rounded-lg mb-2.5 shadow-xs" role="alert">
            <i class="bi bi-check-circle-fill text-emerald-600 fs-6"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning d-flex align-items-center gap-2 py-2 px-3 text-xs rounded-lg mb-2.5 shadow-xs" role="alert">
            <i class="bi bi-exclamation-circle-fill text-amber-600 fs-6"></i>
            <div>{{ session('warning') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 py-2 px-3 text-xs rounded-lg mb-2.5 shadow-xs" role="alert">
            <i class="bi bi-exclamation-triangle-fill text-rose-600 fs-6"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <!-- Top Statistics Row (Compact Minimalist, 4 Columns - Wajib 1 Baris di Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3 mb-2.5">
        <!-- Card 1: Total Pembimbing -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">TOTAL PEMBIMBING</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $stats['total'] ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-people-fill text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Wilayah OPD -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">WILAYAH OPD</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $stats['total_opd'] ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-buildings text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Divisi / Bidang -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">DIVISI / BIDANG</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $stats['total_divisions'] ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-diagram-3-fill text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Peserta Dibimbing -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">PESERTA DIBIMBING</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $stats['total_interns'] ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-mortarboard-fill text-base"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Card (Presisi Segaris Desktop) -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.pembimbing.index') }}" class="row g-2 align-items-end text-xs" role="search">
                {{-- Filter 1: Cari Nama / NIP / Email --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-12 col-sm-6 col-lg-4' : 'col-12 col-sm-6 col-lg-5' }}">
                    <label for="search" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-search me-1"></i> Cari (Nama / NIP / Email)
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control form-control-sm text-xs py-1.5 px-2.5"
                            value="{{ request('search') }}"
                            placeholder="Ketik nama, NIP, atau email...">
                        <button type="submit" class="btn btn-sm btn-primary py-1.5 px-2.5 text-xs font-semibold" title="Cari Pembimbing">
                            <i class="bi bi-search"></i>
                        </button>
                        @if($hasFilter)
                            <a href="{{ route('admin.pembimbing.index') }}" class="btn btn-sm btn-outline-danger py-1.5 px-2 text-xs font-semibold" title="Reset Semua Filter">
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

                {{-- Filter 3: Divisi / Bidang --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-4 col-lg-3' : 'col-12 col-sm-6 col-lg-4' }}">
                    <label for="division_id" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-diagram-3 me-1"></i> Divisi / Bidang
                    </label>
                    <select name="division_id" id="division_id" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="">Semua Divisi / Bidang</option>
                        @foreach ($divisions as $divItem)
                            <option value="{{ $divItem->id }}" @selected((string)request('division_id') === (string)$divItem->id)>
                                {{ $divItem->nama_divisi }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter 4: Baris Per Halaman --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-4 col-lg-2' : 'col-12 col-sm-6 col-lg-3' }}">
                    <label for="per_page" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-list-ol me-1"></i> Baris
                    </label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" onchange="this.form.submit()">
                        <option value="5" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '5')>5 baris</option>
                        <option value="10" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '10')>10 baris</option>
                        <option value="15" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '15')>15 baris</option>
                        <option value="20" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '20')>20 baris</option>
                        <option value="25" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '25')>25 baris</option>
                        <option value="50" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '50')>50 baris</option>
                        <option value="100" @selected((string)request('per_page', request('perPage', $perPage ?? 10)) === '100')>100 baris</option>
                        <option value="all" @selected((string)request('per_page', request('perPage')) === 'all' || (int)($perPage ?? 10) >= 1000)>Semua (All)</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Data Table Container -->
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg bg-white dark:bg-slate-800">
        <div class="w-full overflow-x-auto rounded-t-lg table-responsive mb-0">
            <table class="table table-hover align-middle mb-0 w-full text-xs">
                <thead class="table-light border-bottom text-muted">
                    <tr>
                        <th scope="col" class="ps-3 py-2.5 text-center text-xs font-bold uppercase tracking-wider w-[1%]">NO</th>
                        <th scope="col" class="py-2.5 text-xs font-bold uppercase tracking-wider min-w-[200px]">NAMA PEMBIMBING</th>
                        <th scope="col" class="py-2.5 text-xs font-bold uppercase tracking-wider min-w-[170px]">NIP & KONTAK</th>
                        @if(!empty($isSuperAdmin))
                            <th scope="col" class="py-2.5 text-xs font-bold uppercase tracking-wider min-w-[220px]">WILAYAH OPD</th>
                        @endif
                        <th scope="col" class="py-2.5 text-xs font-bold uppercase tracking-wider min-w-[150px]">DIVISI / BIDANG</th>
                        <th scope="col" class="py-2.5 text-center text-xs font-bold uppercase tracking-wider whitespace-nowrap">AKTIVITAS BIMBINGAN</th>
                        <th scope="col" class="pe-3 py-2.5 text-center text-xs font-bold uppercase tracking-wider whitespace-nowrap w-[1%]">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($pembimbings as $pembimbing)
                        @php
                            $targetOpdName = $pembimbing->opd?->name ?? $pembimbing->division?->opd?->name ?? 'Dinas Komunikasi dan Informatika Tuban';
                            $materialsCount = $pembimbing->materials->count();
                            $tasksCount = $pembimbing->tasks->count();
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors">
                            <td class="ps-3 py-2.5 text-center font-medium text-slate-500 whitespace-nowrap">
                                {{ ($pembimbings->firstItem() ?? 1) + $loop->index }}
                            </td>
                            <td class="py-2.5">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 font-bold d-flex align-items-center justify-center flex-shrink-0 text-xs">
                                        {{ strtoupper(substr($pembimbing->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0 max-w-[220px]">
                                        <div class="fw-bold text-slate-900 dark:text-slate-100 truncate text-xs sm:text-sm" title="{{ $pembimbing->name }}">
                                            {{ $pembimbing->name }}
                                        </div>
                                        <div class="text-[11px] text-slate-400 truncate">
                                            ID Akun: #{{ $pembimbing->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5">
                                <div class="font-mono text-slate-800 dark:text-slate-200 fw-semibold text-xs" title="NIP">
                                    <i class="bi bi-person-badge text-slate-400 me-1"></i>{{ $pembimbing->nip ?? '-' }}
                                </div>
                                <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5" title="Email Kontak">
                                    <i class="bi bi-envelope text-slate-400 me-1"></i>{{ $pembimbing->email }}
                                </div>
                            </td>
                            @if(!empty($isSuperAdmin))
                                <td class="py-2.5">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 text-[11px] font-semibold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 rounded-md">
                                        <i class="bi bi-buildings"></i> {{ $targetOpdName }}
                                    </span>
                                </td>
                            @endif
                            <td class="py-2.5">
                                <span class="px-2.5 py-1 text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-md inline-block">
                                    {{ $pembimbing->division->nama_divisi ?? '-' }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center whitespace-nowrap">
                                <div class="d-inline-flex align-items-center gap-1.5">
                                    <span class="badge bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 font-semibold text-[10px] py-1 px-1.5" title="Modul Materi yang Dibuat">
                                        <i class="bi bi-journal-text text-primary me-0.5"></i> {{ $materialsCount }} Materi
                                    </span>
                                    <span class="badge bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 font-semibold text-[10px] py-1 px-1.5" title="Tugas yang Diberikan">
                                        <i class="bi bi-list-task text-primary me-0.5"></i> {{ $tasksCount }} Tugas
                                    </span>
                                </div>
                            </td>
                            <td class="pe-3 py-2.5 text-center whitespace-nowrap">
                                @if(!empty($isSuperAdmin))
                                    {{-- Khusus Super Admin: Mode Pengawasan View Only (Detail Modal) Tanpa Edit/Hapus --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary py-1 px-2.5 text-xs font-semibold rounded-lg d-inline-flex align-items-center gap-1 shadow-2xs"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detailModal-{{ $pembimbing->id }}"
                                            title="Lihat Rincian Data Pembimbing">
                                        <i class="bi bi-eye"></i>
                                        <span>Detail</span>
                                    </button>
                                @else
                                    {{-- Khusus Admin OPD: Edit & Hapus --}}
                                    <div class="inline-flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.pembimbing.edit', $pembimbing->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2 text-xs font-semibold rounded-lg d-inline-flex align-items-center gap-1" title="Edit Data Pembimbing">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Edit</span>
                                        </a>
                                        <form action="{{ route('admin.pembimbing.destroy', $pembimbing->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger py-1 px-2 text-xs font-semibold rounded-lg d-inline-flex align-items-center gap-1"
                                                    data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Nama</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($pembimbing->name) }}</div><div class='col-4 text-slate-500 font-semibold'>NIP</div><div class='col-8 font-monospace'>{{ $pembimbing->nip ?? '—' }}</div><div class='col-4 text-slate-500 font-semibold'>Divisi</div><div class='col-8'>{{ $pembimbing->division->nama_divisi ?? '—' }}</div></div>"
                                                    data-simang-title="Hapus Akun Pembimbing"
                                                    data-simang-icon="bi-trash-fill"
                                                    data-simang-theme="rose"
                                                    data-simang-alert="Akun pembimbing ini akan dihapus. Hak akses login pembimbing ke portal SIMAGANG akan dinonaktifkan."
                                                    data-simang-btn-text="Ya, Hapus Pembimbing"
                                                    data-simang-btn-icon="bi-trash-fill"
                                                    title="Hapus Akun Pembimbing">
                                                <i class="bi bi-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>

                        @if(!empty($isSuperAdmin))
                            <!-- Modal Detail Pembimbing (Khusus Super Admin) -->
                            <div class="modal fade" id="detailModal-{{ $pembimbing->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $pembimbing->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content rounded-xl shadow-lg border border-slate-200 dark:border-slate-700">
                                        <div class="modal-header border-bottom border-slate-100 dark:border-slate-700 py-3 px-4">
                                            <h5 class="modal-title text-sm font-bold text-slate-800 dark:text-slate-100 d-flex align-items-center gap-2" id="detailModalLabel-{{ $pembimbing->id }}">
                                                <i class="bi bi-person-badge-fill text-primary"></i>
                                                <span>Rincian Pembimbing Magang</span>
                                            </h5>
                                            <button type="button" class="btn-close text-xs" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4 text-xs">
                                            <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600">
                                                <div class="w-12 h-12 rounded-full bg-blue-600 text-white font-bold d-flex align-items-center justify-center flex-shrink-0 text-base shadow-sm">
                                                    {{ strtoupper(substr($pembimbing->name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="fw-bold text-slate-900 dark:text-white text-sm">
                                                        {{ $pembimbing->name }}
                                                    </div>
                                                    <div class="text-slate-500 dark:text-slate-400 font-mono text-[11px]">
                                                        NIP: {{ $pembimbing->nip ?? '—' }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <div class="row g-2 py-1 border-bottom border-slate-100 dark:border-slate-700">
                                                    <div class="col-4 text-slate-500 font-semibold">Wilayah OPD</div>
                                                    <div class="col-8 fw-bold text-slate-800 dark:text-slate-100">{{ $targetOpdName }}</div>
                                                </div>
                                                <div class="row g-2 py-1 border-bottom border-slate-100 dark:border-slate-700">
                                                    <div class="col-4 text-slate-500 font-semibold">Divisi / Bidang</div>
                                                    <div class="col-8 font-semibold text-slate-800 dark:text-slate-100">{{ $pembimbing->division->nama_divisi ?? '—' }}</div>
                                                </div>
                                                <div class="row g-2 py-1 border-bottom border-slate-100 dark:border-slate-700">
                                                    <div class="col-4 text-slate-500 font-semibold">Alamat Email</div>
                                                    <div class="col-8 font-mono text-slate-800 dark:text-slate-100">{{ $pembimbing->email }}</div>
                                                </div>
                                                <div class="row g-2 py-1 border-bottom border-slate-100 dark:border-slate-700">
                                                    <div class="col-4 text-slate-500 font-semibold">Akun Terdaftar</div>
                                                    <div class="col-8 text-slate-700 dark:text-slate-300">{{ $pembimbing->created_at?->translatedFormat('d F Y, H:i') ?? '—' }} WIB</div>
                                                </div>
                                                <div class="row g-2 py-1">
                                                    <div class="col-4 text-slate-500 font-semibold">Aktivitas Modul</div>
                                                    <div class="col-8 text-slate-700 dark:text-slate-300">
                                                        <span class="badge bg-blue-50 text-blue-700 border border-blue-200 me-1">{{ $materialsCount }} Materi Modul</span>
                                                        <span class="badge bg-emerald-50 text-emerald-700 border border-emerald-200">{{ $tasksCount }} Penugasan</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top border-slate-100 dark:border-slate-700 py-2.5 px-4">
                                            <button type="button" class="btn btn-sm btn-secondary text-xs px-3" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ !empty($isSuperAdmin) ? 7 : 6 }}" class="text-center py-5">
                                @if(!empty($isSuperAdmin) && empty($selectedOpdId))
                                    <div class="d-inline-flex p-3 rounded-circle bg-blue-50 dark:bg-blue-900/30 text-blue-600 mb-2">
                                        <i class="bi bi-buildings fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-slate-800 dark:text-slate-100 mb-1 text-sm">Pilih Wilayah OPD Terlebih Dahulu</h6>
                                    <p class="text-xs text-muted mb-0 max-w-md mx-auto">Silakan pilih salah satu Organisasi Perangkat Daerah (OPD) pada filter di atas untuk memantau data pembimbing magang.</p>
                                @else
                                    <i class="bi bi-person-workspace fs-1 d-block mb-2 text-slate-300 dark:text-slate-600"></i>
                                    <span class="fw-semibold text-xs text-slate-500">Belum ada data pembimbing yang cocok dengan filter atau kriteria pencarian.</span>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembimbings->hasPages())
            <div class="card-footer bg-white dark:bg-slate-800 border-top border-slate-200 dark:border-slate-700 p-2.5">
                {{ $pembimbings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
