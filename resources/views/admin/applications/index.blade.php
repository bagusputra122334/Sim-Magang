@extends('layouts.admin')

@section('title', 'Daftar Pendaftaran Magang — Admin')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2.5 gap-2 w-full w-100">
        <!-- Page Title & Description (Left Side) -->
        <div>
            <h1 class="h4 mb-0 fw-bold text-slate-800 dark:text-slate-100 flex items-center gap-2" style="line-height: 1.2;">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                @if(!empty($isSuperAdmin))
                    Monitoring Pendaftaran Magang Seluruh Tuban
                @else
                    Verifikasi Pendaftaran Magang
                @endif
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

    <!-- Top Statistics Row (Compact Minimalist, 5 Columns - Wajib 1 Baris di Layar Desktop) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-2.5 sm:gap-3 mb-2.5">
        <!-- Card 1: Total -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">TOTAL</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $summary['total'] ?? $total ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-bar-chart-fill text-base"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Submitted -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">SUBMITTED</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $summary['submitted'] ?? $submitted ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-envelope-fill text-base text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Under Review -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">UNDER REVIEW</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $summary['review'] ?? $underReview ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-clock-fill text-base text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Accepted -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">ACCEPTED</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $summary['accepted'] ?? $accepted ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-check-circle-fill text-base text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Card 5: Rejected -->
        <div class="card shadow-sm border border-slate-200 dark:border-slate-700 rounded-lg h-full bg-white dark:bg-slate-800 py-2.5 px-3.5 col-span-2 sm:col-span-1">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div class="min-w-0">
                    <p class="text-[11px] font-bold !text-slate-600 dark:!text-slate-300 uppercase tracking-wider mb-0.5 truncate">REJECTED</p>
                    <span class="text-lg lg:text-xl font-extrabold !text-gray-900 dark:!text-white opacity-100 block my-0 leading-none">{{ $summary['rejected'] ?? $rejected ?? 0 }}</span>
                </div>
                <div class="text-primary rounded-lg bg-blue-50 dark:bg-blue-900/30 p-1.5 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                    <i class="bi bi-x-circle-fill text-base text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar (Presisi Segaris 1 Baris di Layar Desktop) --}}
    <div class="card border border-slate-200 dark:border-slate-700 shadow-sm rounded-lg mb-2.5 bg-white dark:bg-slate-800">
        <div class="card-body p-2.5">
            <form method="GET" action="{{ route('admin.applications.index') }}" class="row g-2 align-items-end text-xs">
                @php
                    $hasAppFilter = !empty($filters['search']) || !empty($filters['status']) || !empty($filters['position_id']) || !empty($filters['year']) || (!empty($filters['opd_id']) && !empty($isSuperAdmin));
                @endphp

                {{-- Filter 1: Cari --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-12 col-sm-6 col-lg-3' : 'col-12 col-sm-6 col-lg-3' }}">
                    <label for="search" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
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
                        @if($hasAppFilter)
                            <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-outline-danger py-1.5 px-2 text-xs font-semibold" title="Reset Semua Filter">
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
                            <option value="all" @selected((string)request('opd_id', $filters['opd_id'] ?? '') === 'all')>Semua Wilayah OPD (Agregat Tuban)</option>
                            @foreach ($opds as $opdItem)
                                <option value="{{ $opdItem->id }}" @selected((string)request('opd_id', $filters['opd_id'] ?? '') === (string)$opdItem->id)>
                                    {{ $opdItem->name }} ({{ $opdItem->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Filter 3: Status --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-4 col-lg-2' : 'col-6 col-sm-3 col-lg-2' }}">
                    <label for="status" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-filter me-1"></i> Status
                    </label>
                    <select name="status" id="status" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach (\App\Enums\RegistrationStatus::cases() as $case)
                            <option value="{{ $case->value }}"
                                @selected(request('status', $filters['status'] ?? '') === $case->value)>
                                {{ $case->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter 4: Posisi Magang --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-4 col-lg-2' : 'col-12 col-sm-6 col-lg-3' }}">
                    <label for="position_id" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-briefcase me-1"></i> Posisi Magang
                    </label>
                    <select name="position_id" id="position_id" class="form-select form-select-sm text-xs py-1.5 px-2 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="">Semua Posisi</option>
                        @foreach ($pilihanPosisi as $pos)
                            <option value="{{ $pos->id }}"
                                @selected(request('position_id', $filters['position_id'] ?? '') == $pos->id)>
                                {{ $pos->nama_posisi }}{{ $pos->status->isAktif() ? '' : ' (Nonaktif)' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter 5: Tahun --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-2 col-lg-1' : 'col-6 col-sm-3 col-lg-2' }}">
                    <label for="year" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-calendar-event me-1"></i> Tahun
                    </label>
                    <select name="year" id="year" class="form-select form-select-sm text-xs py-1.5 px-1.5 w-100" style="text-overflow: ellipsis;" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @foreach ($availableYears ?? [] as $y)
                            <option value="{{ $y }}" @selected((string)request('year', $filters['year'] ?? '') === (string)$y)>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter 6: Baris --}}
                <div class="{{ !empty($isSuperAdmin) ? 'col-6 col-sm-2 col-lg-1' : 'col-6 col-sm-3 col-lg-2' }}">
                    <label for="per_page" class="form-label mb-0.5 text-xs font-bold text-slate-700 dark:text-slate-300 text-truncate d-block">
                        <i class="bi bi-list-ol me-1"></i> Baris
                    </label>
                    <select name="per_page" id="per_page" class="form-select form-select-sm text-xs py-1.5 px-1.5 w-100" onchange="this.form.submit()">
                        <option value="5" @selected((string)request('per_page', $perPage) === '5')>5</option>
                        <option value="10" @selected((string)request('per_page', $perPage) === '10')>10</option>
                        <option value="15" @selected((string)request('per_page', $perPage) === '15')>15</option>
                        <option value="20" @selected((string)request('per_page', $perPage) === '20')>20</option>
                        <option value="25" @selected((string)request('per_page', $perPage) === '25')>25</option>
                        <option value="50" @selected((string)request('per_page', $perPage) === '50')>50</option>
                        <option value="100" @selected((string)request('per_page', $perPage) === '100')>100</option>
                        <option value="all" @selected((string)request('per_page') === 'all' || (int)request('per_page') >= 1000 || (int)$perPage >= 1000)>Semua (All)</option>
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
                                @php
                                    $targetOpd = $app->opd ?? $app->position?->opd;
                                @endphp
                                @if($targetOpd)
                                    <div class="mt-1 d-inline-flex align-items-center gap-1">
                                        <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 text-[10px]">
                                            <i class="bi bi-buildings"></i> {{ $targetOpd->name }}
                                        </span>
                                    </div>
                                @endif
                            </td>
                            <td class="py-1.5 px-2.5 text-xs text-slate-600 dark:text-slate-300 whitespace-nowrap" style="min-width: 120px;">{{ optional($app->tanggal_submit)->format('d M Y') ?? '-' }}</td>
                            <td class="py-1.5 px-2.5 text-center whitespace-nowrap" style="min-width: 130px;">
                                @php
                                    $statusMap = [
                                        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-slate-100 text-slate-800 border-slate-200',
                                        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-amber-100 text-amber-800 border-amber-200',
                                        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-rose-100 text-rose-800 border-rose-200',
                                    ];
                                    $cls = $statusMap[$app->status->value] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full border {{ $cls }}">
                                    {{ $app->status->label() }}
                                </span>
                            </td>
                            <td class="pe-2.5 py-1.5 px-2.5 text-end whitespace-nowrap" style="min-width: 140px;">
                                @if(!empty($isSuperAdmin))
                                    <a href="{{ route('admin.applications.show', $app->id) }}"
                                       class="btn btn-sm btn-outline-primary py-1 px-2.5 text-xs font-semibold d-inline-flex align-items-center gap-1 rounded" title="Lihat Detail & Berkas Pelamar">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @else
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.applications.show', $app->id) }}"
                                           class="btn btn-sm btn-outline-primary py-1 px-2 text-xs" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if (! $app->isAccepted() && ! $app->isRejected())
                                            <a href="{{ route('admin.applications.review', $app->id) }}"
                                               class="btn btn-sm btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-lg px-2.5 py-1 font-semibold text-xs shadow-sm d-inline-flex align-items-center gap-1" title="Verifikasi Sekarang">
                                                <i class="bi bi-pencil-square"></i> Verifikasi
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary py-1 px-2 text-xs" disabled title="Sudah diverifikasi final">
                                                Final
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                @if(!empty($isSuperAdmin) && empty($filters['opd_id']))
                                    <div class="d-inline-flex p-3 rounded-circle bg-blue-50 dark:bg-blue-900/30 text-blue-600 mb-2">
                                        <i class="bi bi-buildings fs-2"></i>
                                    </div>
                                    <h6 class="fw-bold text-slate-800 dark:text-slate-100 mb-1 text-sm">Pilih Wilayah OPD Terlebih Dahulu</h6>
                                    <p class="text-xs text-muted mb-0 max-w-md mx-auto">Silakan pilih salah satu Organisasi Perangkat Daerah (OPD) pada filter di atas untuk memantau data pendaftaran magang.</p>
                                @else
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50 text-muted"></i>
                                    <p class="mb-1 text-xs fw-medium text-muted">Tidak ada pendaftaran yang cocok dengan filter Anda.</p>
                                    <a href="{{ route('admin.applications.index') }}" class="btn btn-link btn-sm text-xs">
                                        Lihat seluruh pendaftaran
                                    </a>
                                @endif
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
