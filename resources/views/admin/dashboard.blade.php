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
    <div class="page-heading">
        <div class="page-heading-copy">
            <h1 class="h3 mb-1">Dashboard Administrator</h1>
            <p class="text-muted mb-0">
                Selamat datang, <strong>Administrator Diskominfo Tuban</strong> — Monitoring pendaftaran magang Diskominfo SP Kabupaten Tuban.
            </p>
        </div>
        <div class="heading-actions">
            <a href="{{ route('admin.applications.index') }}" class="btn btn-primary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="bi bi-journal-check" aria-hidden="true"></i>
                <span>Kelola Pendaftaran</span>
            </a>
            <a href="{{ route('admin.positions.index') }}" class="btn btn-primary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2">
                <i class="bi bi-briefcase" aria-hidden="true"></i>
                <span>Kelola Posisi</span>
            </a>
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
        <div class="panel-header">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-journal-text" aria-hidden="true"></i>
                    <span>Pendaftaran Magang Terbaru</span>
                </h2>
                <p class="text-muted mb-0">10 pendaftaran magang paling akhir diajukan oleh peserta.</p>
            </div>
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.applications.index') }}">
                <i class="bi bi-eye me-1" aria-hidden="true"></i> Lihat Semua Pendaftaran
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Kode Pendaftaran</th>
                        <th scope="col">Nama Pemohon</th>
                        <th scope="col">Posisi Magang</th>
                        <th scope="col">Tanggal Daftar</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentApplications as $reg)
                        @php
                            $sv = $reg->status->value;
                            $badgeClass = $statusBadgeMap[$sv] ?? 'bg-secondary';
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-bold font-monospace text-primary">
                                    {{ $reg->nomor_pendaftaran }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold rounded-2">
                                        {{ mb_substr($reg->user?->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="fw-semibold mb-0">{{ $reg->user?->name ?? 'N/A' }}</p>
                                        <p class="text-muted small mb-0">{{ $reg->user?->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $reg->position?->nama_posisi ?? '-' }}</span>
                            </td>
                            <td>
                                <div>{{ $reg->tanggal_submit?->translatedFormat('d M Y') ?? '-' }}</div>
                                <small class="text-muted">{{ $reg->tanggal_submit?->translatedFormat('H:i') ?? '' }} WIB</small>
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                    {{ $reg->status->label() }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-light btn-sm me-1">
                                    <i class="bi bi-eye me-1" aria-hidden="true"></i> Detail
                                </a>
                                @if ($reg->isAccepted())
                                    <a href="{{ route('admin.applications.reply-letter', $reg->id) }}" class="btn btn-outline-danger btn-sm">
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
