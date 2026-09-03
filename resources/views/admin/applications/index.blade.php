@extends('layouts.admin')

@section('title', 'Daftar Pendaftaran Magang — Admin')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 w-full w-100">
        <!-- Page Title & Description (Left Side) -->
        <div class="flex-1">
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Verifikasi Pendaftaran Magang
            </h2>
            <p class="text-slate-500 text-sm mt-1">Administrasi seleksi peserta MAGANG Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.</p>
        </div>
        
        <!-- Export Button (Pushed Strictly to Right Side) -->
        <div class="shrink-0 ms-auto">
            <a href="{{ route('admin.applications.export_pdf') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor PDF (Resmi)
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-6 mt-4">
        <!-- Card 1: Total -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $summary['total'] ?? 0 }}</h3> 
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Submitted -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Submitted</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $summary['submitted'] ?? 0 }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-sky-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Under Review -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Under Review</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $summary['review'] ?? 0 }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Accepted -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Accepted</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $summary['accepted'] ?? 0 }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 5: Rejected -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rejected</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $summary['rejected'] ?? 0 }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-rose-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border shadow-sm mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.applications.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label mb-1 small fw-semibold">
                        <i class="bi bi-search me-1"></i> Cari (Nama / No. / Email)
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control"
                            value="{{ request('search', $filters['search'] ?? '') }}"
                            placeholder="Ketik nama atau nomor...">
                        <button type="submit" class="btn btn-primary" title="Cari Pendaftaran">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label mb-1 small fw-semibold">
                        <i class="bi bi-filter me-1"></i> Status
                    </label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (\App\Enums\RegistrationStatus::cases() as $case)
                            <option value="{{ $case->value }}"
                                @selected(request('status', $filters['status'] ?? '') === $case->value)>
                                {{ $case->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="position_id" class="form-label mb-1 small fw-semibold">
                        <i class="bi bi-briefcase me-1"></i> Posisi Magang
                    </label>
                    <select name="position_id" id="position_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Posisi Magang</option>
                        @foreach ($pilihanPosisi as $pos)
                            <option value="{{ $pos->id }}"
                                @selected(request('position_id', $filters['position_id'] ?? '') == $pos->id)>
                                {{ $pos->nama_posisi }}{{ $pos->status->isAktif() ? '' : ' (Nonaktif)' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
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

    {{-- Tabel List --}}
    <div class="card border shadow-sm">
        <div class="card-body p-0 w-full overflow-x-auto overflow-y-hidden border border-slate-200 rounded-xl table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th class="ps-3 py-2" style="width: 140px; min-width: 140px;">No. Pendaftaran</th>
                        <th class="py-2 min-w-[220px]" style="min-width: 220px;">Nama Peserta</th>
                        <th class="py-2 min-w-[220px]" style="min-width: 220px;">Jenis & Instansi</th>
                        <th class="py-2 min-w-[250px]" style="min-width: 250px;">Posisi Magang</th>
                        <th class="py-2" style="width: 120px; min-width: 120px;">Tgl Submit</th>
                        <th class="py-2 text-center" style="width: 130px; min-width: 130px;">Status</th>
                        <th class="py-2 text-end pe-3" style="width: 160px; min-width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $app)
                        @php
                            $prof = $app->user?->profile;
                        @endphp
                        <tr>
                            <td class="ps-3 font-monospace small text-primary fw-semibold" style="min-width: 140px;">
                                {{ $app->nomor_pendaftaran }}
                            </td>
                            <td class="min-w-[220px]" style="min-width: 220px;">
                                <div class="fw-semibold text-slate-900">{{ $app->user?->name ?? '—' }}</div>
                                <div class="text-slate-500 small"><i class="bi bi-envelope me-1"></i>{{ $app->user?->email ?? '' }}</div>
                            </td>
                            <td class="min-w-[220px]" style="min-width: 220px;">
                                @if($prof)
                                    <div class="mb-1">
                                        <span class="badge {{ $prof->isSiswa() ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }}">
                                            {{ $prof->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa' }}
                                        </span>
                                    </div>
                                    <div class="fw-medium small text-slate-800">{{ $prof->institusi }}</div>
                                    <div class="text-slate-500 small font-monospace">{{ $prof->numberLabel() }}: {{ $prof->numberValue() }}</div>
                                @else
                                    <span class="text-slate-400 small">Profil Belum Terisi</span>
                                @endif
                            </td>
                            <td class="min-w-[250px]" style="min-width: 250px;">
                                <div class="text-slate-900 font-semibold text-sm">{{ $app->position?->nama_posisi ?? '—' }}</div>
                            </td>
                            <td class="small text-slate-600" style="min-width: 120px;">{{ optional($app->tanggal_submit)->format('d M Y') ?? '-' }}</td>
                            <td class="text-center" style="min-width: 130px;">
                                @php
                                    $statusMap = [
                                        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-warning-subtle text-warning border border-warning-subtle',
                                        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-success-subtle text-success border border-success-subtle',
                                        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    ];
                                    $cls = $statusMap[$app->status->value] ?? 'bg-secondary-subtle text-secondary border';
                                @endphp
                                <span class="badge {{ $cls }}">
                                    {{ $app->status->label() }}
                                </span>
                            </td>
                            <td class="text-end pe-3" style="min-width: 160px;">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.applications.show', $app->id) }}"
                                       class="btn btn-outline-primary" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if (! $app->isAccepted() && ! $app->isRejected())
                                        <a href="{{ route('admin.applications.review', $app->id) }}"
                                           class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg px-2.5 py-1 font-semibold text-xs shadow-sm" title="Review Sekarang">
                                            <i class="bi bi-pencil-square me-1"></i> Review
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary" disabled title="Sudah diverifikasi final">
                                            Final
                                        </button>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                <p class="mb-1">Tidak ada pendaftaran yang cocok dengan filter Anda.</p>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-link btn-sm">
                                    Lihat seluruh pendaftaran
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
                    Menampilkan <strong>{{ $applications->firstItem() ?? 0 }}</strong> s.d. <strong>{{ $applications->lastItem() ?? 0 }}</strong> dari <strong>{{ $applications->total() }}</strong> pendaftar
                </div>
                <div>
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
