@extends('layouts.admin')

@section('title', 'Monitoring Status Magang')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
    <!-- Page Title -->
    <div>
        <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            @if(!empty($isSuperAdmin))
                Monitoring Status Magang Seluruh Tuban
            @else
                Monitoring Status Magang
            @endif
        </h1>
    </div>
    
    <!-- Export Button (Pushed to Right) -->
    <div class="shrink-0 ms-auto">
        <a href="{{ route('admin.interns.export_pdf_active', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors text-decoration-none">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Ekspor PDF
        </a>
    </div>
</div>

    <!-- Top Statistics Row (Compact Minimalist, 4 Columns - Wajib 1 Baris di Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-3 mb-2.5">
        <!-- Card 1: Total Diterima -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">TOTAL DITERIMA</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $statistics['total'] ?? $totalDiterima ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-person-check-fill text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Aktif Magang -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">AKTIF MAGANG</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $statistics['active'] ?? $aktifMagang ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-play-circle-fill text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Selesai Magang -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">SELESAI MAGANG</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $statistics['completed'] ?? $selesaiMagang ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-check-circle-fill text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Dinonaktifkan -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">DINONAKTIFKAN</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $statistics['terminated'] ?? $dinonaktifkan ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-x-circle-fill text-base"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar (5 Filter Presisi Segaris 1 Baris di Layar Desktop) --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.active-interns.index') }}" class="row g-2 align-items-end text-xs">
                @php
                    $hasFilter = request('search') || request('op_status') || request('year') || (request('opd_id') && !empty($isSuperAdmin));
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
                            <a href="{{ route('admin.active-interns.index') }}" class="btn btn-sm btn-outline-danger py-1.5 px-2 text-xs font-semibold" title="Reset Semua Filter">
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

                {{-- Filter 3: Status Operasional --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-4 col-lg-2' : 'col-12 col-sm-6 col-lg-4' }}">
                    <label for="op_status" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-funnel me-1"></i> Status Operasional
                    </label>
                    <select name="op_status" id="op_status" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="">Semua Status Operasional</option>
                        <option value="active" @selected(request('op_status', $opStatus) === 'active')>Aktif Magang</option>
                        <option value="completed" @selected(request('op_status', $opStatus) === 'completed')>Selesai Magang</option>
                        <option value="terminated" @selected(request('op_status', $opStatus) === 'terminated')>Dinonaktifkan / Berhenti</option>
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
                <div class="col-12 col-sm-4 col-lg-2">
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
                        <option value="all" @selected((string)request('per_page') === 'all' || (int)request('per_page') >= 1000 || (int)$perPage >= 1000)>Semua (All)</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Data Magang Aktif --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg bg-white dark:bg-slate-800">
        <div class="w-full overflow-x-auto overflow-y-hidden rounded-t-lg table-responsive">
            <table class="table table-hover align-middle mb-0 w-full text-xs">
                <thead class="table-light border-bottom text-muted">
                    <tr>
                        <th class="ps-3 py-2.5 px-3 text-xs font-bold uppercase tracking-wider whitespace-nowrap w-[1%]">No.</th>
                        <th class="py-2.5 px-3 text-xs font-bold uppercase tracking-wider min-w-[200px]">Peserta & Instansi</th>
                        <th class="py-2.5 px-3 text-xs font-bold uppercase tracking-wider min-w-[180px]">Posisi & Pembimbing</th>
                        <th class="py-2.5 px-3 text-xs font-bold uppercase tracking-wider whitespace-nowrap min-w-[150px]">Periode Magang</th>
                        <th class="py-2.5 px-3 text-xs font-bold uppercase tracking-wider text-center whitespace-nowrap w-[1%]">Status Operasional</th>
                        <th class="pe-3 py-2.5 px-3 text-xs font-bold uppercase tracking-wider text-end whitespace-nowrap w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($interns as $intern)
                        @php
                            $prof = $intern->user?->profile;
                            $instansiText = $prof?->instansi ?? '—';
                            $jurusanText = $prof?->jurusan ?? '';
                            $fullInstansi = $jurusanText ? "{$instansiText} • {$jurusanText}" : $instansiText;
                        @endphp
                        <tr class="cursor-pointer hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition-colors group"
                            data-bs-toggle="modal"
                            data-bs-target="#quickPreviewModal-{{ $intern->id }}"
                            title="Klik baris untuk melihat pratinjau ringkas status">
                            <td class="ps-3 py-2.5 px-3 font-medium text-slate-500 whitespace-nowrap text-xs">{{ ($interns->firstItem() ?? 1) + $loop->index }}</td>
                            <td class="py-2.5 px-3 text-xs">
                                <div class="max-w-[240px]">
                                    <div class="fw-semibold text-slate-900 dark:text-slate-100 truncate text-xs sm:text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" title="{{ $intern->user?->name ?? '—' }}">
                                        {{ $intern->user?->name ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate" title="{{ $fullInstansi }}">
                                        <i class="bi bi-building me-1 text-slate-400"></i>{{ $instansiText }}
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-xs">
                                <div class="max-w-[220px]">
                                    <div class="fw-semibold text-slate-900 dark:text-slate-100 truncate text-xs sm:text-sm" title="{{ $intern->position?->nama_posisi ?? '—' }}">
                                        {{ $intern->position?->nama_posisi ?? '—' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate" title="Pembimbing: {{ $intern->position?->mentor_name ?? 'Belum ditentukan' }}">
                                        <i class="bi bi-person-badge text-indigo-500 me-1"></i>{{ $intern->position?->mentor_name ?? 'Belum ada mentor' }}
                                    </div>
                                    @php
                                        $targetOpd = $intern->opd ?? $intern->position?->opd;
                                    @endphp
                                    @if($targetOpd)
                                        <div class="mt-1 d-inline-flex align-items-center gap-1">
                                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[10px]">
                                                <i class="bi bi-buildings"></i> {{ $targetOpd->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-3 text-xs whitespace-nowrap">
                                <div class="fw-medium text-slate-800 dark:text-slate-200 text-xs">
                                    {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                                    <span class="text-slate-400 mx-1">s/d</span>
                                    {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">
                                    {{ $intern->nomor_pendaftaran }}
                                </div>
                            </td>
                            <td class="text-center whitespace-nowrap py-2.5 px-3">
                                @if ($intern->operational_status === 'completed')
                                    @if (! empty($intern->certificate_path))
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            <i class="bi bi-patch-check-fill text-emerald-600"></i> Selesai &amp; Sertifikat Terbit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-800 border border-slate-200" title="Peserta telah selesai magang dan menantikan penerbitan sertifikat">
                                            <i class="bi bi-award text-slate-600"></i> Selesai (Menunggu Sertifikat)
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-full {{ $intern->operational_status_badge_class }}">
                                        {{ $intern->operational_status_label }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-3 py-2.5 px-3 whitespace-nowrap" onclick="event.stopPropagation();">
                                @if(!empty($isSuperAdmin))
                                    <a href="{{ route('admin.active-interns.show', $intern->id) }}"
                                       class="btn btn-sm btn-outline-primary py-1 px-2.5 rounded-lg text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-1.5"
                                       onclick="event.stopPropagation();"
                                       title="Lihat Detail Peserta Magang">
                                        <i class="bi bi-eye"></i>
                                        <span>Detail</span>
                                    </a>
                                @else
                                    <a href="{{ route('admin.active-interns.show', $intern->id) }}"
                                       class="btn btn-sm btn-outline-secondary py-1 px-2.5 rounded-lg text-xs hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors inline-flex items-center gap-1.5"
                                       onclick="event.stopPropagation();"
                                       title="Buka Halaman Manajemen Peserta">
                                        <i class="bi bi-arrow-up-right-square"></i>
                                        <span>Kelola</span>
                                    </a>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL: POPUP PREVIEW RINGKAS (Ketika Baris Tabel Diklik) --}}
                        <div class="modal fade text-start" id="quickPreviewModal-{{ $intern->id }}" tabindex="-1" aria-labelledby="quickPreviewModalLabel-{{ $intern->id }}" aria-hidden="true" onclick="event.stopPropagation();">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content border border-slate-200 dark:border-slate-800 shadow-xl rounded-2xl overflow-hidden bg-white dark:bg-slate-900">
                                    <div class="modal-header py-2.5 px-4 bg-slate-800 text-white d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-lightning-charge-fill text-amber-400"></i>
                                            <h5 class="modal-title text-xs font-bold text-white mb-0" id="quickPreviewModalLabel-{{ $intern->id }}">
                                                Pratinjau Ringkas Status Magang
                                            </h5>
                                        </div>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                    </div>

                                    <div class="modal-body p-4 text-xs space-y-3">
                                        <div class="d-flex items-start gap-3 pb-3 border-b border-slate-100 dark:border-slate-800">
                                            <div class="w-14 h-14 rounded-full overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0 d-flex align-items-center justify-content-center shadow-xs">
                                                @if($prof?->foto_url)
                                                    <img src="{{ $prof->foto_url }}" alt="{{ $intern->user?->name }}" class="w-full h-full object-cover">
                                                @else
                                                    <i class="bi bi-person-fill text-slate-400 fs-2"></i>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-grow-1">
                                                <div class="d-flex items-center justify-between gap-2 mb-0.5">
                                                    <h6 class="font-bold text-slate-900 dark:text-slate-100 text-sm truncate mb-0">{{ $intern->user?->name ?? '—' }}</h6>
                                                    <span class="text-[11px] text-slate-500 font-mono bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700 shrink-0">{{ $intern->nomor_pendaftaran }}</span>
                                                </div>
                                                <div class="text-xs text-slate-600 dark:text-slate-300 font-medium truncate mb-1">
                                                    <i class="bi bi-building me-1 text-slate-400"></i>{{ $prof?->instansi ?? '—' }}
                                                    @if($prof?->jurusan)
                                                        <span class="text-slate-400 mx-1">•</span>{{ $prof->jurusan }}
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 dark:text-slate-400 pt-0.5">
                                                    <span class="inline-flex items-center gap-1 font-mono text-[11px] text-slate-600 dark:text-slate-300">
                                                        <i class="bi bi-envelope text-blue-500"></i>
                                                        <span>{{ $intern->user?->email ?? '-' }}</span>
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 font-mono text-[11px] text-slate-600 dark:text-slate-300">
                                                        <i class="bi bi-whatsapp text-emerald-600"></i>
                                                        <span>{{ $prof?->no_hp ?? ($prof?->no_telepon ?? ($intern->user?->phone ?? '-')) }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-2 bg-slate-50 dark:bg-slate-800/60 p-3 rounded-xl border border-slate-200 dark:border-slate-700">
                                            <div class="d-flex justify-content-between align-items-center py-1 border-b border-slate-200/60 dark:border-slate-700/60">
                                                <span class="text-slate-500 text-xs">Status Operasional</span>
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full {{ $intern->operational_status_badge_class }}">
                                                    {{ $intern->operational_status_label }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1 border-b border-slate-200/60 dark:border-slate-700/60">
                                                <span class="text-slate-500 text-xs">Posisi Magang</span>
                                                <span class="font-semibold text-slate-800 dark:text-slate-200 text-end">{{ $intern->position?->nama_posisi ?? '—' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1 border-b border-slate-200/60 dark:border-slate-700/60">
                                                <span class="text-slate-500 text-xs">Divisi / Bidang</span>
                                                <span class="font-medium text-slate-700 dark:text-slate-300 text-end">{{ $intern->position?->departemen ?? 'Dinas Kominfo SP Tuban' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1 border-b border-slate-200/60 dark:border-slate-700/60">
                                                <span class="text-slate-500 text-xs">Pembimbing Lapangan</span>
                                                <span class="font-medium text-slate-700 dark:text-slate-300 text-end">{{ $intern->position?->mentor_name ?? 'Belum ditentukan' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <span class="text-slate-500 text-xs">Rentang Periode</span>
                                                <span class="font-medium text-slate-700 dark:text-slate-300 text-end">
                                                    {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }} s/d {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer bg-slate-50 dark:bg-slate-800/60 border-top border-slate-200 dark:border-slate-800 py-2.5 px-4 d-flex justify-content-between align-items-center">
                                        <button type="button" class="btn btn-sm btn-secondary px-3 py-1.5 text-xs font-semibold rounded-lg" data-bs-dismiss="modal">
                                            Tutup
                                        </button>
                                        <a href="{{ route('admin.active-interns.show', $intern->id) }}" class="btn btn-sm btn-primary bg-indigo-600 hover:bg-indigo-700 text-white px-3.5 py-1.5 text-xs font-semibold rounded-lg shadow-sm d-inline-flex align-items-center gap-1.5">
                                            <span>Buka Halaman Manajemen Peserta</span>
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                @if(!empty($isSuperAdmin) && empty($selectedOpdId))
                                    <div class="d-inline-flex p-3 rounded-circle bg-blue-50 dark:bg-blue-900/30 text-blue-600 mb-2">
                                        <i class="bi bi-buildings fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-slate-800 dark:text-slate-100 mb-1 text-sm">Pilih Wilayah OPD Terlebih Dahulu</h6>
                                    <p class="text-xs text-muted mb-0 max-w-md mx-auto">Silakan pilih salah satu Organisasi Perangkat Daerah (OPD) pada filter di atas untuk memantau status peserta magang aktif.</p>
                                @else
                                    <i class="bi bi-people fs-2 d-block mb-1 opacity-50 text-muted"></i>
                                    <p class="mb-0.5 text-xs font-semibold text-muted">Tidak ada data peserta magang yang cocok dengan kriteria pencarian.</p>
                                    <a href="{{ route('admin.active-interns.index') }}" class="btn btn-link btn-sm py-0 text-xs">
                                        Lihat seluruh status magang
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-transparent border-top py-2 px-3">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <div class="text-muted small" style="font-size: 0.75rem;">
                    Menampilkan <strong>{{ $interns->firstItem() ?? 0 }}</strong> s.d. <strong>{{ $interns->lastItem() ?? 0 }}</strong> dari <strong>{{ $interns->total() }}</strong> peserta magang
                </div>
                <div>
                    {{ $interns->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
