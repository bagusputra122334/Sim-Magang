@extends('layouts.participant')

@php
    $statusBadgeMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-primary-subtle text-primary border border-primary-subtle',
        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-warning-subtle text-warning border border-warning-subtle',
        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-success-subtle text-success border border-success-subtle',
        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-danger-subtle text-danger border border-danger-subtle',
    ];

    $reg = $latestRegistration;
    $sv = $reg?->status?->value;
    $badgeClass = $sv !== null ? ($statusBadgeMap[$sv] ?? 'bg-secondary-subtle text-secondary border') : 'bg-secondary-subtle text-secondary border';
@endphp

@section('title', 'Dashboard')

@section('content')
    {{-- Header --}}
    <div class="page-heading">
        <div class="page-heading-copy">
            <h1 class="h3 mb-1">Selamat Datang, {{ $user->name }}!</h1>
            <p class="text-muted mb-0">
                Pantau status pendaftaran magang Anda di Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.
            </p>
        </div>
        <div class="heading-actions">
            @if (!$hasProfile)
                <a href="{{ route('participant.profile.create') }}" class="btn btn-success fw-semibold">
                    <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Lengkapi Profil
                </a>
            @else
                <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary fw-semibold">
                    <i class="bi bi-send me-1" aria-hidden="true"></i> Daftar Magang
                </a>
            @endif
        </div>
    </div>

    {{-- Overview Metrics Cards --}}
    <section class="row g-3" aria-label="Ringkasan Status Peserta">
        {{-- Card 1: Kelengkapan Profil --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card {{ $hasProfile ? 'metric-success' : 'metric-warning' }}">
                <div class="metric-top">
                    <span class="metric-label">Status Profil</span>
                    <span class="metric-icon"><i class="bi bi-person-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value fs-4">
                    {{ $hasProfile ? 'Terisi Lengkap' : 'Belum Terisi' }}
                </div>
                <div class="metric-meta">
                    @if ($hasProfile)
                        <span class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Siap Mendaftar</span>
                    @else
                        <span class="text-warning fw-bold"><i class="bi bi-exclamation-triangle-fill me-1"></i> Wajib Dilengkapi</span>
                    @endif
                </div>
            </article>
        </div>

        {{-- Card 2: Total Pendaftaran --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card metric-primary">
                <div class="metric-top">
                    <span class="metric-label">Total Pendaftaran</span>
                    <span class="metric-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value">{{ $totalRegistrations }}</div>
                <div class="metric-meta">
                    <span>Permohonan magang diajukan</span>
                </div>
            </article>
        </div>

        {{-- Card 3: Status Pendaftaran Terbaru --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card {{ $sv === 'accepted' ? 'metric-success' : ($sv === 'rejected' ? 'metric-danger' : ($sv === 'under_review' ? 'metric-warning' : 'metric-primary')) }}">
                <div class="metric-top">
                    <span class="metric-label">Status Terbaru</span>
                    <span class="metric-icon"><i class="bi bi-info-circle" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value fs-4">
                    @if ($reg !== null)
                        <span class="badge {{ $badgeClass }} fs-6">
                            {{ $reg->status->label() }}
                        </span>
                    @else
                        <span class="badge bg-secondary fs-6">Belum Ada</span>
                    @endif
                </div>
                <div class="metric-meta">
                    <span>{{ $reg !== null ? 'Nomor: '.$reg->nomor_pendaftaran : 'Silakan ajukan pendaftaran' }}</span>
                </div>
            </article>
        </div>

        {{-- Card 4: Surat Balasan Status --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="metric-card {{ $documentInfo['surat_balasan_exists'] ? 'metric-success' : 'metric-warning' }}">
                <div class="metric-top">
                    <span class="metric-label">Surat Balasan</span>
                    <span class="metric-icon"><i class="bi bi-file-earmark-pdf" aria-hidden="true"></i></span>
                </div>
                <div class="metric-value fs-4">
                    {{ $documentInfo['surat_balasan_exists'] ? 'Tersedia' : 'Belum Ada' }}
                </div>
                <div class="metric-meta">
                    @if ($documentInfo['surat_balasan_exists'])
                        <a href="{{ $documentInfo['surat_balasan_download_route'] }}" class="text-success fw-bold text-decoration-none">
                            <i class="bi bi-download me-1"></i> Unduh Surat PDF
                        </a>
                    @else
                        <span>Diterbitkan jika diterima</span>
                    @endif
                </div>
            </article>
        </div>
    </section>

    <div class="row g-4 mt-1">
        {{-- Left Column: Personal Information & Admin Notes --}}
        <div class="col-12 col-lg-4">
            {{-- Personal Information Panel --}}
            <div class="panel">
                <div class="panel-header border-bottom pb-3 mb-3">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-person-vcard" aria-hidden="true"></i>
                            <span>Informasi Pribadi</span>
                        </h2>
                        <p class="text-muted mb-0">Data akun & biodata peserta.</p>
                    </div>
                    @if ($hasProfile)
                        <a href="{{ route('participant.profile.edit') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                @if (!$hasProfile)
                    <div class="alert alert-warning mb-0" role="alert">
                        <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Profil Belum Lengkap</h6>
                        <p class="small mb-3">Lengkapi profil Anda agar dapat mengajukan pendaftaran magang.</p>
                        <a href="{{ route('participant.profile.create') }}" class="btn btn-warning btn-sm w-100 fw-bold">
                            <i class="bi bi-person-plus-fill me-1"></i> Isi Profil Sekarang
                        </a>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        @if(!empty($profile->foto_url))
                            <img src="{{ $profile->foto_url }}" alt="{{ $profile->nama_lengkap ?? $user->name }}"
                                 class="avatar-xl rounded-circle border border-2 border-primary object-fit-cover shadow-sm"
                                 style="width: 64px; height: 64px; object-fit: cover;"
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                            <div class="avatar-xl bg-primary bg-opacity-10 text-primary d-none align-items-center justify-content-center fw-bold fs-2 rounded-circle border border-2 border-primary shadow-sm" style="width: 64px; height: 64px;">
                                {{ mb_substr($profile->nama_lengkap ?? $user->name, 0, 1) }}
                            </div>
                        @else
                            <div class="avatar-xl bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-2 rounded-circle border border-2 border-primary shadow-sm" style="width: 64px; height: 64px;">
                                {{ mb_substr($profile->nama_lengkap ?? $user->name, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <div class="mb-1">
                                <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-2.5 py-1 small fw-bold">
                                    {{ $profile->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa' }}
                                </span>
                            </div>
                            <h3 class="h6 fw-bold mb-1">{{ $profile->nama_lengkap ?? $user->name }}</h3>
                            <p class="text-muted small mb-1"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                            <p class="text-muted small mb-0"><i class="bi bi-telephone me-1"></i>{{ $profile->no_telepon ?? '-' }}</p>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="bi bg-primary-subtle me-1"></i> {{ $profile->institutionLabel() }}</span>
                            <span class="fw-semibold text-end ms-2">{{ $profile->institusi ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="bi bi-mortarboard me-1"></i> {{ $profile->numberLabel() }}</span>
                            <span class="fw-semibold font-monospace">{{ $profile->numberValue() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="bi bi-book me-1"></i> {{ $profile->majorLabel() }}</span>
                            <span class="fw-semibold text-end ms-2">{{ $profile->jurusan ?? '-' }}</span>
                        </li>
                        @if (!empty($profile->nik))
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-muted"><i class="bi bi-card-heading me-1"></i> NIK</span>
                                <span class="fw-semibold font-monospace">{{ $profile->nik }}</span>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>

            {{-- Admin Notes (If Rejected / Available) --}}
            @if ($reg !== null && !empty(trim($reg->catatan_admin ?? '')))
                <div class="panel mt-4 border-danger">
                    <div class="panel-header border-bottom pb-2 mb-2">
                        <h2 class="h6 mb-0 text-danger section-title">
                            <i class="bi bi-chat-left-dots" aria-hidden="true"></i>
                            <span>Catatan Admin</span>
                        </h2>
                    </div>
                    <div class="alert alert-danger mb-0">
                        <p class="mb-0 small fst-italic">"{{ $reg->catatan_admin }}"</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Application Details or Empty State --}}
        <div class="col-12 col-lg-8">
            @if ($reg === null)
                {{-- PROFESSIONAL EMPTY STATE --}}
                <div class="panel text-center py-5">
                    <div class="avatar-xl bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                        <i class="bi bi-journal-plus fs-1"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-2">Belum Ada Pendaftaran Magang</h3>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 480px;">
                        Anda belum mengajukan pendaftaran magang. Silakan lengkapi profil Anda terlebih dahulu, kemudian pilih posisi magang yang tersedia.
                    </p>
                    @if (!$hasProfile)
                        <a href="{{ route('participant.profile.create') }}" class="btn btn-success fw-bold">
                            <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Lengkapi Profil Dulu
                        </a>
                    @else
                        <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-send me-1" aria-hidden="true"></i> Ajukan Pendaftaran Magang
                        </a>
                    @endif
                </div>
            @else
                {{-- APPLICATION DETAILS CARD --}}
                <div class="panel mb-4">
                    <div class="panel-header border-bottom pb-3 mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                <span>Informasi Pendaftaran Magang</span>
                            </h2>
                            <p class="text-muted mb-0">Kode Pendaftaran: <strong class="font-monospace text-primary">{{ $reg->nomor_pendaftaran }}</strong></p>
                        </div>
                        <div>
                            <span class="badge {{ $badgeClass }} px-3 py-2 fs-6 rounded-pill">
                                {{ $reg->status->label() }}
                            </span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small d-block mb-1">Posisi Magang</span>
                                <strong class="fs-6 text-body">{{ $reg->position?->nama_posisi ?? '-' }}</strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small d-block mb-1">Periode Magang</span>
                                <strong class="fs-6 text-body">
                                    {{ $reg->periode_label ?? ($reg->tanggal_mulai?->translatedFormat('d M Y').' — '.$reg->tanggal_selesai?->translatedFormat('d M Y') ?? '-') }}
                                </strong>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small d-block mb-1">Tanggal Pengajuan</span>
                                <span class="fw-semibold text-body">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $reg->tanggal_submit?->translatedFormat('l, d M Y (H:i)') ?? '-' }} WIB
                                </span>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small d-block mb-1">Status Verifikasi</span>
                                <span class="fw-semibold text-body">
                                    <i class="bi bi-shield-check me-1"></i>
                                    {{ $reg->status->label() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- UPLOADED DOCUMENTS PANEL --}}
                <div class="panel mb-4">
                    <div class="panel-header border-bottom pb-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-folder2-open" aria-hidden="true"></i>
                                <span>Dokumen & Berkas Magang</span>
                            </h2>
                            <p class="text-muted mb-0">Berkas administrasi pendaftaran magang.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Document 1: CV --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="p-3 border rounded-3 text-center h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <i class="bi bi-file-earmark-person fs-1 {{ $documentInfo['cv_exists'] ? 'text-primary' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-2 mb-1">CV / Riwayat Hidup</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['cv_exists'] ? 'File Terunggah' : 'Belum Ada File' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['cv_exists'])
                                        <a href="{{ $documentInfo['cv_url'] }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-download me-1"></i> Unduh CV
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2">Tidak Ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Document 2: Surat Pengantar --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="p-3 border rounded-3 text-center h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <i class="bi bi-envelope-paper fs-1 {{ $documentInfo['surat_pengantar_exists'] ? 'text-success' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-2 mb-1">Surat Pengantar</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['surat_pengantar_exists'] ? 'File Terunggah' : 'Belum Ada File' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['surat_pengantar_exists'])
                                        <a href="{{ $documentInfo['surat_pengantar_url'] }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                            <i class="bi bi-download me-1"></i> Unduh Surat
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2">Tidak Ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Document 3: Proposal Magang --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="p-3 border rounded-3 text-center h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <i class="bi bi-file-earmark-pdf fs-1 {{ $documentInfo['proposal_magang_exists'] ? 'text-danger' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-2 mb-1">Proposal Magang</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['proposal_magang_exists'] ? 'File Terunggah' : 'Belum Ada File' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['proposal_magang_exists'])
                                        <a href="{{ $documentInfo['proposal_magang_url'] }}" target="_blank" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="bi bi-download me-1"></i> Unduh Proposal
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2">Tidak Ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Document 4: Surat Balasan (If Available) --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <div class="p-3 border rounded-3 text-center h-100 d-flex flex-column justify-content-between {{ $documentInfo['surat_balasan_exists'] ? 'border-success bg-success bg-opacity-10' : '' }}">
                                <div>
                                    <i class="bi bi-file-earmark-check fs-1 {{ $documentInfo['surat_balasan_exists'] ? 'text-success' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-2 mb-1">Surat Balasan (Admin)</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['surat_balasan_exists'] ? 'Resmi Diterbitkan' : 'Belum Diterbitkan' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['surat_balasan_exists'])
                                        <a href="{{ $documentInfo['surat_balasan_download_route'] }}" class="btn btn-success btn-sm w-100 fw-bold">
                                            <i class="bi bi-download me-1"></i> Unduh Surat Balasan
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-2">Belum Tersedia</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
