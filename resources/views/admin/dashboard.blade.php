@extends('layouts.admin')

@php
    $statusBadgeMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-primary-subtle text-primary border border-primary-subtle',
        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-warning-subtle text-warning border border-warning-subtle',
        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-success-subtle text-success border border-success-subtle',
        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-danger-subtle text-danger border border-danger-subtle',
    ];
@endphp

@section('title', 'Dashboard')

@section('content')
    {{-- Header --}}
    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <h1 class="h3 mb-1">Dashboard Administrator</h1>
            <p class="text-muted mb-0">
                Selamat datang, <strong>{{ auth()->user()->name }}</strong> — Monitoring pendaftaran magang Diskominfo SP Kabupaten Tuban.
            </p>
        </div>
    </div>

    {{-- Statistics Cards (6 Metrics Grid) --}}
    <section class="row g-3" aria-label="Ringkasan Statistik Magang">
        {{-- Card 1: Total Posisi Magang --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Posisi Magang</span>
                    <span class="metric-icon"><i class="bi bi-briefcase" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ number_format($stats['total_positions'], 0, ',', '.') }}</div>
                <div class="metric-meta">
                    <span class="text-success fw-semibold">{{ $stats['total_positions_aktif'] }} aktif</span>
                    <span>dari {{ $stats['total_positions'] }} posisi</span>
                </div>
            </article>
        </div>

        {{-- Card 2: Total Pelamar / Peserta --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Total Peserta Terdaftar</span>
                    <span class="metric-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ number_format($stats['total_peserta'], 0, ',', '.') }}</div>
                <div class="metric-meta">
                    <span class="text-success fw-semibold">{{ $stats['total_peserta_verified'] }} terverifikasi</span>
                    <span>akun email</span>
                </div>
            </article>
        </div>

        {{-- Card 3: Pending Applications --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Perlu Verifikasi</span>
                    <span class="metric-icon"><i class="bi bi-clock-history" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ number_format($stats['verifikasi_pending'], 0, ',', '.') }}</div>
                <div class="metric-meta">
                    <span class="text-warning fw-semibold">{{ $stats['status_submitted'] }} diajukan</span>
                    <span>perlu ditinjau</span>
                </div>
            </article>
        </div>

        {{-- Card 4: Under Review Applications --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <article class="metric-card metric-warning">
                <div class="metric-top">
                    <span class="metric-label">Under Review</span>
                    <span class="metric-icon"><i class="bi bi-search" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ number_format($stats['status_under_review'], 0, ',', '.') }}</div>
                <div class="metric-meta">
                    <span>Sedang diproses tim verifikator</span>
                </div>
            </article>
        </div>

        {{-- Card 5: Accepted Applications --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <article class="metric-card metric-success">
                <div class="metric-top">
                    <span class="metric-label">Pendaftaran Diterima</span>
                    <span class="metric-icon"><i class="bi bi-check-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ number_format($stats['status_accepted'], 0, ',', '.') }}</div>
                <div class="metric-meta">
                    <span>{{ $stats['percent_accepted'] }}% dari total pendaftaran</span>
                </div>
            </article>
        </div>

        {{-- Card 6: Rejected Applications --}}
        <div class="col-12 col-sm-6 col-xl-4">
            <article class="metric-card metric-danger">
                <div class="metric-top">
                    <span class="metric-label">Pendaftaran Ditolak</span>
                    <span class="metric-icon"><i class="bi bi-x-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ number_format($stats['status_rejected'], 0, ',', '.') }}</div>
                <div class="metric-meta">
                    <span>{{ $stats['total_registrations'] > 0 ? round(($stats['status_rejected'] / $stats['total_registrations']) * 100, 1) : 0 }}% dari total pendaftaran</span>
                </div>
            </article>
        </div>
    </section>

    {{-- Table Panel: Recent Applications --}}
    <section class="panel mt-4">
        <div class="panel-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-3">
            <div>
                <h2 class="h5 mb-1 section-title text-lg md:text-xl font-bold">
                    <i class="bi bi-journal-text me-1" aria-hidden="true"></i>
                    <span>Pendaftaran Magang Terbaru</span>
                </h2>
                <p class="text-muted mb-0 small">10 pendaftaran magang paling akhir diajukan oleh peserta.</p>
            </div>
            <a class="btn btn-outline-secondary bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg px-3 py-1.5 font-medium text-xs shadow-sm hover:shadow-md transition-all duration-200 w-100 sm:w-auto text-center" href="{{ route('admin.applications.index') }}">
                <i class="bi bi-eye me-1" aria-hidden="true"></i> Lihat Semua Pendaftaran
            </a>
        </div>

        <div class="w-full overflow-x-auto overflow-y-hidden border border-slate-200 rounded-xl table-responsive">
            <table class="table align-middle mb-0 w-full">

                <thead class="table-light border-bottom">
                    <tr>
                        <th scope="col" class="whitespace-nowrap w-[1%] ps-3 py-2.5">Kode Pendaftaran</th>
                        <th scope="col" class="w-1/3 py-2.5">Nama Pemohon</th>
                        <th scope="col" class="w-1/3 py-2.5">Posisi Magang</th>
                        <th scope="col" class="whitespace-nowrap w-[1%] py-2.5">Tanggal Daftar</th>
                        <th scope="col" class="whitespace-nowrap w-[1%] text-center py-2.5">Status</th>
                        <th scope="col" class="whitespace-nowrap w-[1%] text-end pe-3 py-2.5">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentApplications as $reg)
                        @php
                            $sv = $reg->status->value;
                            $badgeClass = $statusBadgeMap[$sv] ?? 'bg-secondary';
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap ps-3 font-monospace small text-primary fw-semibold">
                                {{ $reg->nomor_pendaftaran }}
                            </td>
                            <td class="whitespace-normal break-words">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold rounded-2 flex-shrink-0">
                                        {{ mb_substr($reg->user?->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="fw-semibold text-slate-900 mb-0">{{ $reg->user?->name ?? 'N/A' }}</p>
                                        <p class="text-slate-500 small mb-0"><i class="bi bi-envelope me-1"></i>{{ $reg->user?->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-normal break-words">
                                <span class="fw-semibold text-slate-900">{{ $reg->position?->nama_posisi ?? '-' }}</span>
                            </td>
                            <td class="whitespace-nowrap">
                                <div class="fw-medium text-slate-800 small">{{ $reg->tanggal_submit?->translatedFormat('d M Y') ?? '-' }}</div>
                                <small class="text-slate-500">{{ $reg->tanggal_submit?->translatedFormat('H:i') ?? '' }} WIB</small>
                            </td>
                            <td class="text-center whitespace-nowrap">
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1.5">
                                    {{ $reg->status->label() }}
                                </span>
                            </td>
                            <td class="text-end pe-3 whitespace-nowrap">
                                <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-light bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg px-2.5 py-1 font-medium text-xs shadow-sm transition-all duration-200 me-1">
                                    <i class="bi bi-eye me-1" aria-hidden="true"></i> Detail
                                </a>
                                @if ($reg->isAccepted())
                                    <a href="{{ route('admin.applications.reply-letter', $reg->id) }}" class="btn btn-outline-danger bg-white hover:bg-rose-50 text-rose-600 border border-rose-200 rounded-lg px-2.5 py-1 font-medium text-xs shadow-sm transition-all duration-200">
                                        <i class="bi bi-file-earmark-pdf me-1" aria-hidden="true"></i> Surat
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty

                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted opacity-50 mb-2">
                                    <i class="bi bi-journal-x fs-1"></i>
                                </div>
                                <p class="fw-semibold text-muted mb-1">Belum ada data pendaftaran magang</p>
                                <small class="text-muted">Data pendaftaran terbaru dari peserta akan muncul di sini.</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
