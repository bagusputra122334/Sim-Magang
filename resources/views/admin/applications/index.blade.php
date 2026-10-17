@extends('layouts.admin')

@section('title', 'Daftar Pendaftaran Magang — Admin')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
        <!-- Page Title & Description (Left Side) -->
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Verifikasi Pendaftaran Magang
            </h1>
        </div>
        
        <!-- Export Button (Pushed Strictly to Right Side) -->
        <div class="shrink-0 ms-auto">
            <a href="{{ route('admin.applications.export_pdf', request()->query()) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-colors text-decoration-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor PDF
            </a>
        </div>
    </div>

    <!-- Top Statistics Row (Ultra-Compact, 5 Columns - Wajib 1 Baris di Layar Desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-2 mb-2.5">
        <!-- Card 1: Total -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">TOTAL</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $summary['total'] ?? $total ?? 0 }}</span>
                </div>
                <div class="text-primary rounded bg-blue-100 dark:bg-blue-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-bar-chart-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Submitted -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">SUBMITTED</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $summary['submitted'] ?? $submitted ?? 0 }}</span>
                </div>
                <div class="text-info rounded bg-cyan-100 dark:bg-cyan-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-envelope-fill fs-5 text-cyan-600"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Under Review -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">UNDER REVIEW</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $summary['review'] ?? $underReview ?? 0 }}</span>
                </div>
                <div class="text-warning rounded bg-amber-100 dark:bg-amber-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-clock-fill fs-5 text-amber-600"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Accepted -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">ACCEPTED</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $summary['accepted'] ?? $accepted ?? 0 }}</span>
                </div>
                <div class="text-success rounded bg-emerald-100 dark:bg-emerald-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-check-circle-fill fs-5 text-emerald-600"></i>
                </div>
            </div>
        </div>

        <!-- Card 5: Rejected -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 p-2.5 sm:p-3 col-span-2 lg:col-span-1">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-xs font-extrabold !text-slate-700 dark:!text-slate-200 uppercase tracking-wider mb-0.5">REJECTED</p>
                    <span class="text-xl lg:text-2xl font-black !text-gray-900 dark:!text-white opacity-100 block my-0.5 leading-none">{{ $summary['rejected'] ?? $rejected ?? 0 }}</span>
                </div>
                <div class="text-danger rounded bg-rose-100 dark:bg-rose-900/30 p-2 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 34px; height: 34px;">
                    <i class="bi bi-x-circle-fill fs-5 text-rose-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.applications.index') }}" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="search" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-search me-1"></i> Cari (Nama / No. / Email)
                    </label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="search"
                            id="search"
                            class="form-control form-control-sm text-xs py-1.5 px-2.5"
                            value="{{ request('search', $filters['search'] ?? '') }}"
                            placeholder="Ketik nama atau nomor...">
                        <button type="submit" class="btn btn-sm btn-primary py-1.5 px-2.5 text-xs font-semibold" title="Cari Pendaftaran">
                            <i class="bi bi-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <label for="status" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-filter me-1"></i> Status
                    </label>
                    <select name="status" id="status" class="form-select form-select-sm text-xs py-1.5 px-2.5" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (\App\Enums\RegistrationStatus::cases() as $case)
                            <option value="{{ $case->value }}"
                                @selected(request('status', $filters['status'] ?? '') === $case->value)>
                                {{ $case->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="position_id" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300">
                        <i class="bi bi-briefcase me-1"></i> Posisi Magang
                    </label>
                    <select name="position_id" id="position_id" class="form-select form-select-sm text-xs py-1.5 px-2.5" onchange="this.form.submit()">
                        <option value="">Semua Posisi Magang</option>
                        @foreach ($pilihanPosisi as $pos)
                            <option value="{{ $pos->id }}"
                                @selected(request('position_id', $filters['position_id'] ?? '') == $pos->id)>
                                {{ $pos->nama_posisi }}{{ $pos->status->isAktif() ? '' : ' (Nonaktif)' }}
                            </option>
                        @endforeach
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

    {{-- Tabel List --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg bg-white dark:bg-slate-800">
        <div class="w-full overflow-x-auto overflow-y-hidden rounded-t-lg table-responsive">
            <table class="table table-hover align-middle mb-0 w-full text-xs">
                <thead class="table-light border-bottom text-muted">
                    <tr>
                        <th class="ps-2.5 py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider whitespace-nowrap" style="width: 140px; min-width: 140px;">No. Pendaftaran</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider min-w-[220px]" style="min-width: 220px;">Nama Peserta</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider min-w-[220px]" style="min-width: 220px;">Jenis & Instansi</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider min-w-[250px]" style="min-width: 250px;">Posisi Magang</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider whitespace-nowrap" style="width: 120px; min-width: 120px;">Tgl Submit</th>
                        <th class="py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider text-center whitespace-nowrap" style="width: 130px; min-width: 130px;">Status</th>
                        <th class="pe-2.5 py-1.5 px-2.5 text-xs font-bold uppercase tracking-wider text-end whitespace-nowrap" style="width: 160px; min-width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse ($applications as $app)
                        @php
                            $prof = $app->user?->profile;
                        @endphp
                        <tr>
                            <td class="ps-2.5 py-1.5 px-2.5 font-monospace text-xs text-primary fw-semibold whitespace-nowrap" style="min-width: 140px;">
                                {{ $app->nomor_pendaftaran }}
                            </td>
                            <td class="py-1.5 px-2.5 text-xs min-w-[220px]" style="min-width: 220px;">
                                <div class="fw-bold text-slate-900 dark:text-slate-100 text-xs sm:text-sm">{{ $app->user?->name ?? '—' }}</div>
                                <div class="text-slate-500 text-xs"><i class="bi bi-envelope me-1"></i>{{ $app->user?->email ?? '' }}</div>
                            </td>
                            <td class="py-1.5 px-2.5 text-xs min-w-[220px]" style="min-width: 220px;">
                                @if($prof)
                                    <div class="mb-0.5">
                                        <span class="badge {{ $prof->isSiswa() ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-primary-subtle text-primary border border-primary-subtle' }} text-xs py-0.5 px-2">
                                            {{ $prof->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa' }}
                                        </span>
                                    </div>
                                    <div class="fw-semibold text-xs text-slate-800 dark:text-slate-200">{{ $prof->institusi }}</div>
                                    <div class="text-slate-500 text-xs font-monospace" style="font-size: 0.7rem;">{{ $prof->numberLabel() }}: {{ $prof->numberValue() }}</div>
                                @else
                                    <span class="text-slate-400 text-xs">Profil Belum Terisi</span>
                                @endif
                            </td>
                            <td class="py-1.5 px-2.5 text-xs min-w-[250px]" style="min-width: 250px;">
                                <div class="text-slate-900 dark:text-slate-100 font-semibold text-xs sm:text-sm">{{ $app->position?->nama_posisi ?? '—' }}</div>
                            </td>
                            <td class="py-1.5 px-2.5 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap" style="min-width: 120px;">{{ optional($app->tanggal_submit)->format('d M Y') ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 text-center whitespace-nowrap" style="min-width: 130px;">
                                @php
                                    $statusMap = [
                                        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-primary-subtle text-primary border border-primary-subtle',
                                        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-warning-subtle text-warning border border-warning-subtle',
                                        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-success-subtle text-success border border-success-subtle',
                                        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-danger-subtle text-danger border border-danger-subtle',
                                    ];
                                    $cls = $statusMap[$app->status->value] ?? 'bg-secondary-subtle text-secondary border';
                                @endphp
                                <span class="badge {{ $cls }} text-xs py-1 px-2.5 rounded-pill font-semibold">
                                    {{ $app->status->label() }}
                                </span>
                            </td>
                            <td class="pe-2.5 py-1.5 px-2.5 text-end whitespace-nowrap" style="min-width: 160px;">
                                <div class="d-inline-flex items-center gap-1">
                                    <a href="{{ route('admin.applications.show', $app->id) }}"
                                       class="btn btn-sm btn-outline-primary py-1 px-2 text-xs" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if (! $app->isAccepted() && ! $app->isRejected())
                                        <a href="{{ route('admin.applications.review', $app->id) }}"
                                           class="btn btn-sm btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg px-2.5 py-1 font-semibold text-xs shadow-sm d-inline-flex align-items-center gap-1" title="Review Sekarang">
                                            <i class="bi bi-pencil-square"></i> Review
                                        </a>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 text-xs" disabled title="Sudah diverifikasi final">
                                            Final
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                <p class="mb-1 text-xs fw-medium">Tidak ada pendaftaran yang cocok dengan filter Anda.</p>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-link btn-sm text-xs">
                                    Lihat seluruh pendaftaran
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-transparent border-top py-2.5 px-3">
            <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                <div class="text-muted text-xs">
                    Menampilkan <strong>{{ $applications->firstItem() ?? 0 }}</strong> s.d. <strong>{{ $applications->lastItem() ?? 0 }}</strong> dari <strong>{{ $applications->total() }}</strong> pendaftar
                </div>
                <div>
                    {{ $applications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
