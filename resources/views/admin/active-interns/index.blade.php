@extends('layouts.admin')

@section('title', 'Monitoring Status Magang')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
    <!-- Page Title -->
    <div>
        <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Monitoring Status Magang
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

    <!-- Top Statistics Row (Ultra-Compact, 4 Columns - Wajib 1 Baris di Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 mb-2.5">
        <!-- Card 1: Total Diterima -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">TOTAL DITERIMA</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['total'] ?? $totalDiterima ?? 0 }}</span>
                </div>
                <div class="text-primary rounded bg-blue-100 dark:bg-blue-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-person-check-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Aktif Magang -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">AKTIF MAGANG</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['active'] ?? $aktifMagang ?? 0 }}</span>
                </div>
                <div class="text-success rounded bg-emerald-100 dark:bg-emerald-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-play-circle-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Selesai Magang -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">SELESAI MAGANG</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['completed'] ?? $selesaiMagang ?? 0 }}</span>
                </div>
                <div class="text-secondary rounded bg-slate-100 dark:bg-slate-700 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Dinonaktifkan -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">DINONAKTIFKAN</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $statistics['terminated'] ?? $dinonaktifkan ?? 0 }}</span>
                </div>
                <div class="text-danger rounded bg-rose-100 dark:bg-rose-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-x-circle-fill fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.active-interns.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-search me-1"></i> Cari (Nama / Instansi / Posisi)
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control form-control-sm text-xs py-1.5 px-2.5"
                            value="{{ request('search', $search) }}"
                            placeholder="Ketik nama peserta, instansi, atau posisi magang...">
                        <button type="submit" class="btn btn-sm btn-primary py-1.5 px-2.5 text-xs font-semibold" title="Cari Peserta">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label for="op_status" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-funnel me-1"></i> Status Operasional
                    </label>
                    <select name="op_status" id="op_status" class="form-select form-select-sm text-xs py-1.5 px-2.5" onchange="this.form.submit()">
                        <option value="">Semua Status Operasional</option>
                        <option value="active" @selected(request('op_status', $opStatus) === 'active')>Aktif Magang</option>
                        <option value="completed" @selected(request('op_status', $opStatus) === 'completed')>Selesai Magang</option>
                        <option value="terminated" @selected(request('op_status', $opStatus) === 'terminated')>Dinonaktifkan / Berhenti</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="year" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-calendar-event me-1"></i> Filter Tahun
                    </label>
                    <select name="year" id="year" class="form-select form-select-sm text-xs py-1.5 px-2.5" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @php $currentYear = (int) date('Y'); @endphp
                        @for($i = $currentYear + 1; $i >= $currentYear - 3; $i--)
                            <option value="{{ $i }}" @selected((string)request('year', $year ?? '') === (string)$i)>Tahun {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label for="per_page" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-list-ol me-1"></i> Per Halaman
                    </label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm text-xs py-1.5 px-2.5" onchange="this.form.submit()">
                        <option value="5" @selected((string)request('per_page', $perPage) === '5')>5 data</option>
                        <option value="10" @selected((string)request('per_page', $perPage) === '10')>10 data</option>
                        <option value="15" @selected((string)request('per_page', $perPage) === '15')>15 data</option>
                        <option value="25" @selected((string)request('per_page', $perPage) === '25')>25 data</option>
                        <option value="50" @selected((string)request('per_page', $perPage) === '50')>50 data</option>
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
                        <th class="ps-2.5 py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider whitespace-nowrap w-[1%]">No.</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider w-1/3">Nama Peserta & Instansi</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider w-1/3">Posisi & Pembimbing</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider w-1/4">Periode Magang</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider text-center whitespace-nowrap w-[1%]">Status Operasional</th>
                        <th class="pe-2.5 py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider text-end whitespace-nowrap w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($interns as $intern)
                        @php
                            $prof = $intern->user?->profile;
                        @endphp
                        <tr>
                            <td class="ps-2.5 py-1.5 px-2.5 font-medium text-slate-500 whitespace-nowrap text-xs">{{ ($interns->firstItem() ?? 1) + $loop->index }}</td>
                            <td class="whitespace-normal break-words py-1.5 px-2.5 text-xs">
                                <div class="d-flex flex-column gap-0.5">
                                    <div class="fw-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm">{{ $intern->user?->name ?? '—' }}</div>
                                    <div class="text-xs text-slate-600 dark:text-slate-300">
                                        <i class="bi bi-building me-1 text-slate-400"></i>{{ $prof?->instansi ?? '—' }}
                                    </div>
                                    @if($prof?->jurusan)
                                        <div class="text-xs text-slate-500 dark:text-slate-400">
                                            <i class="bi bi-mortarboard me-1 text-slate-400"></i>{{ $prof->jurusan }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-monospace" style="font-size: 0.7rem;">
                                        <i class="bi bi-envelope me-1 text-slate-400"></i>{{ $intern->user?->email }}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-normal break-words py-1.5 px-2.5 text-xs">
                                <div class="d-flex flex-column gap-0.5">
                                    <div class="fw-semibold text-slate-900 dark:text-slate-100 text-xs sm:text-sm">
                                        {{ $intern->position?->nama_posisi ?? '—' }}
                                    </div>
                                    @if($intern->position?->mentor_name)
                                        <div class="text-xs text-slate-500 dark:text-slate-400 d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-person-badge text-indigo-600"></i>
                                            <span>Pembimbing: {{ $intern->position->mentor_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-normal break-words py-1.5 px-2.5 text-xs">
                                <div class="d-flex flex-column gap-0.5">
                                    <div class="fw-semibold text-slate-800 dark:text-slate-200 text-xs whitespace-nowrap">
                                        {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                                        <span class="text-slate-400 mx-1">s/d</span>
                                        {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 font-monospace" style="font-size: 0.7rem;">
                                        <i class="bi bi-ticket-perforated me-1 text-slate-400"></i>{{ $intern->nomor_pendaftaran }}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center whitespace-nowrap py-1.5 px-2.5">
                                <span class="badge {{ $intern->operational_status_badge_class }} rounded-pill px-2.5 py-1 text-xs font-semibold">
                                    {{ $intern->operational_status_label }}
                                </span>
                            </td>
                            <td class="text-end pe-2.5 py-1.5 px-2.5 whitespace-nowrap">
                                <a href="{{ route('admin.active-interns.show', $intern->id) }}"
                                   class="btn btn-sm btn-primary-subtle bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 rounded-lg py-1 px-2.5 font-semibold d-inline-flex align-items-center gap-1 text-xs shadow-sm"
                                   title="Lihat Detail Peserta Magang">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-1 opacity-50"></i>
                                <p class="mb-0.5 text-xs font-semibold">Tidak ada data peserta magang yang cocok dengan kriteria pencarian.</p>
                                <a href="{{ route('admin.active-interns.index') }}" class="btn btn-link btn-sm py-0 text-xs">
                                    Lihat seluruh status magang
                                </a>
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
