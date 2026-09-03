@extends('layouts.admin')

@section('title', 'Monitoring Magang Aktif')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <!-- Page Title & Description -->
    <div>
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Monitoring Magang Aktif
        </h2>
        <p class="text-slate-500 text-sm mt-1">Pantau daftar peserta magang yang telah diterima, lacak periode pelaksanaan, dan kelola status operasional.</p>
    </div>
    
    <!-- Export Button (Pushed to Right) -->
    <div class="shrink-0">
        <a href="{{ route('admin.interns.export_pdf_active') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-md transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Ekspor PDF (Resmi)
        </a>
    </div>
</div>

    {{-- Ringkasan Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 mt-4">
        <!-- Card 1: Total Diterima -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Diterima</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $statistics['total'] }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Aktif Magang -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Aktif Magang</p>
                <h3 class="text-2xl font-extrabold text-emerald-600">{{ $statistics['active'] }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Selesai Magang -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Selesai Magang</p>
                <h3 class="text-2xl font-extrabold text-slate-700">{{ $statistics['completed'] }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-slate-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Dinonaktifkan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Dinonaktifkan</p>
                <h3 class="text-2xl font-extrabold text-rose-600">{{ $statistics['terminated'] }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-rose-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.active-interns.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="search" class="form-label mb-1 small fw-semibold">
                        <i class="bi bi-search me-1"></i> Cari (Nama / Instansi / Posisi)
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            value="{{ request('search', $search) }}"
                            placeholder="Ketik nama peserta, instansi, atau posisi magang...">
                        <button type="submit" class="btn btn-primary" title="Cari Peserta">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="op_status" class="form-label mb-1 small fw-semibold">
                        <i class="bi bi-funnel me-1"></i> Filter Status Operasional
                    </label>
                    <select name="op_status" id="op_status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status Operasional</option>
                        <option value="active" @selected(request('op_status', $opStatus) === 'active')>Aktif Magang</option>
                        <option value="completed" @selected(request('op_status', $opStatus) === 'completed')>Selesai Magang</option>
                        <option value="terminated" @selected(request('op_status', $opStatus) === 'terminated')>Dinonaktifkan / Berhenti</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="per_page" class="form-label mb-1 small fw-semibold">
                        <i class="bi bi-list-ol me-1"></i> Per Halaman
                    </label>
                    <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
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
    <div class="card border shadow-sm">
        <div class="w-full overflow-x-auto overflow-y-hidden border border-slate-200 rounded-xl table-responsive">
            <table class="table table-hover align-middle mb-0 w-full">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-3 py-2.5 whitespace-nowrap w-[1%]">No.</th>
                        <th class="py-2.5 w-1/3">Nama Peserta & Instansi</th>
                        <th class="py-2.5 w-1/3">Posisi & Pembimbing</th>
                        <th class="py-2.5 w-1/4">Periode Magang</th>
                        <th class="py-2.5 text-center whitespace-nowrap w-[1%]">Status Operasional</th>
                        <th class="py-2.5 text-end pe-3 whitespace-nowrap w-[1%]">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($interns as $intern)
                        @php
                            $prof = $intern->user?->profile;
                        @endphp
                        <tr>
                            <td class="ps-3 font-medium text-slate-500 whitespace-nowrap">{{ ($interns->firstItem() ?? 1) + $loop->index }}</td>
                            <td class="whitespace-normal break-words">
                                <div class="d-flex flex-column gap-1 py-1">
                                    <div class="fw-semibold text-slate-900 text-sm">{{ $intern->user?->name ?? '—' }}</div>
                                    <div class="text-sm text-slate-600">
                                        <i class="bi bi-building me-1 text-slate-400"></i>{{ $prof?->instansi ?? '—' }}
                                    </div>
                                    @if($prof?->jurusan)
                                        <div class="text-xs text-slate-500">
                                            <i class="bi bi-mortarboard me-1 text-slate-400"></i>{{ $prof->jurusan }}
                                        </div>
                                    @endif
                                    <div class="text-xs text-slate-500 font-monospace">
                                        <i class="bi bi-envelope me-1 text-slate-400"></i>{{ $intern->user?->email }}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-normal break-words">
                                <div class="d-flex flex-column gap-1 py-1">
                                    <div class="fw-semibold text-slate-900 text-sm">
                                        {{ $intern->position?->nama_posisi ?? '—' }}
                                    </div>
                                    @if($intern->position?->mentor_name)
                                        <div class="text-xs text-slate-500 d-flex align-items-center gap-1">
                                            <i class="bi bi-person-badge text-indigo-600"></i>
                                            <span>Pembimbing: {{ $intern->position->mentor_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-normal break-words">
                                <div class="d-flex flex-column gap-1 py-1">
                                    <div class="fw-medium text-slate-800 text-sm whitespace-nowrap">
                                        {{ $intern->periode_mulai?->translatedFormat('d M Y') ?? '-' }}
                                        <span class="text-slate-400 mx-1">s/d</span>
                                        {{ $intern->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-monospace">
                                        <i class="bi bi-ticket-perforated me-1 text-slate-400"></i>{{ $intern->nomor_pendaftaran }}
                                    </div>
                                </div>
                            </td>

                            <td class="text-center whitespace-nowrap">
                                <span class="badge {{ $intern->operational_status_badge_class }} rounded-pill px-3 py-1.5 small">
                                    {{ $intern->operational_status_label }}
                                </span>
                            </td>
                            <td class="text-end pe-3 whitespace-nowrap">
                                <a href="{{ route('admin.active-interns.show', $intern->id) }}"
                                   class="btn btn-sm btn-primary-subtle bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border border-indigo-200 rounded-lg px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm"
                                   title="Lihat Detail Peserta Magang">
                                    <i class="bi bi-person-lines-fill"></i>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-2 opacity-50"></i>
                                <p class="mb-1">Tidak ada data peserta magang yang cocok dengan kriteria pencarian.</p>
                                <a href="{{ route('admin.active-interns.index') }}" class="btn btn-link btn-sm">
                                    Lihat seluruh magang aktif
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-transparent border-top py-3 px-3">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <div class="text-muted small">
                    Menampilkan <strong>{{ $interns->firstItem() ?? 0 }}</strong> s.d. <strong>{{ $interns->lastItem() ?? 0 }}</strong> dari <strong>{{ $interns->total() }}</strong> peserta magang
                </div>
                <div>
                    {{ $interns->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
