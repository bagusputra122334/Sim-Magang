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
    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <h1 class="h3 mb-1">Selamat Datang, {{ $user->name }}!</h1>
            <p class="text-muted mb-0">
                Pantau status pendaftaran magang Anda di Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.
            </p>
        </div>
    </div>

    @if($reg !== null && $reg->is_terminated)
        <div class="alert alert-danger border-2 rounded-4 mb-4 d-flex align-items-start" role="alert">
            <i class="bi bi-slash-circle-fill fs-3 text-danger me-3 flex-shrink-0 mt-1"></i>
            <div class="flex-grow-1">
                <h5 class="alert-heading fw-bold mb-1">Status Magang Sebelumnya Dinonaktifkan</h5>
                <p class="mb-2">Status operasional magang Anda sebelumnya (<code>{{ $reg->nomor_pendaftaran }}</code>) telah dinonaktifkan oleh Admin. Anda diperbolehkan mengajukan pendaftaran magang baru.</p>
                @if($reg->catatan_penonaktifan)
                    <div class="small text-muted bg-white p-2.5 rounded border mb-2">
                        <strong>Catatan Admin:</strong> {{ $reg->catatan_penonaktifan }}
                    </div>
                @endif
                <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl px-4 py-2 font-semibold text-sm shadow-sm hover:shadow-md hover:opacity-90 transition-all duration-200">
                    <i class="bi bi-send-fill me-1"></i> Ajukan Pendaftaran Magang Baru
                </a>

            </div>
        </div>
    @endif

    {{-- Overview Metrics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Card 1: Status Profil -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Status Profil</p>
                <h3 class="text-xl font-extrabold text-slate-800">{{ $hasProfile ? 'Terisi Lengkap' : 'Belum Terisi' }}</h3>
                @if ($hasProfile)
                    <p class="text-xs text-emerald-600 font-medium mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        Siap Mendaftar
                    </p>
                @else
                    <p class="text-xs text-amber-600 font-medium mt-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        Wajib Dilengkapi
                    </p>
                @endif
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg {{ $hasProfile ? 'bg-emerald-500' : 'bg-amber-500' }} text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Total Pendaftaran -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Pendaftaran</p>
                <h3 class="text-xl font-extrabold text-slate-800">{{ $totalRegistrations }}</h3>
                <p class="text-xs text-slate-500 mt-1">Permohonan diajukan</p>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <!-- Card 3: Status Terbaru -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Status Terbaru</p>
                @if ($reg !== null)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $reg->is_terminated ? 'bg-rose-100 text-rose-800' : ($sv === 'accepted' ? 'bg-emerald-100 text-emerald-800' : ($sv === 'rejected' ? 'bg-rose-100 text-rose-800' : ($sv === 'under_review' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800'))) }}">
                        {{ $reg->is_terminated ? 'Dinonaktifkan' : $reg->status->label() }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                        Belum Ada
                    </span>
                @endif
                <p class="text-xs text-slate-500 mt-2">{{ $reg !== null ? 'Nomor: '.$reg->nomor_pendaftaran : 'Silakan ajukan pendaftaran' }}</p>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-sky-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Surat Balasan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Surat Balasan</p>
                <h3 class="text-xl font-extrabold text-slate-800">{{ $documentInfo['surat_balasan_exists'] ? 'Tersedia' : 'Belum Ada' }}</h3>
                @if ($documentInfo['surat_balasan_exists'])
                    <a href="{{ $documentInfo['surat_balasan_download_route'] }}" class="text-xs text-emerald-600 font-bold hover:underline flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Surat PDF
                    </a>
                @else
                    <p class="text-xs text-slate-500 mt-1">Diterbitkan jika diterima</p>
                @endif
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </div>

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
                        <a href="{{ route('participant.profile.edit') }}" class="btn btn-outline-secondary bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg px-3 py-1.5 font-medium text-xs shadow-sm hover:shadow-md transition-all duration-200">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                @if (!$hasProfile)
                    <div class="alert alert-warning mb-0" role="alert">
                        <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Profil Belum Lengkap</h6>
                        <p class="small mb-3">Lengkapi profil Anda agar dapat mengajukan pendaftaran magang.</p>
                        <a href="{{ route('participant.profile.create') }}" class="btn btn-warning bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white rounded-xl py-2 px-3 font-semibold text-sm shadow-sm hover:shadow-md hover:opacity-90 transition-all duration-200 w-100">
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
                        <a href="{{ route('participant.profile.create') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl px-4 py-2.5 font-semibold text-sm shadow-sm hover:shadow-md hover:opacity-90 transition-all duration-200">
                            <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Lengkapi Profil Dulu
                        </a>
                    @else
                        <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl px-4 py-2.5 font-semibold text-sm shadow-sm hover:shadow-md hover:opacity-90 transition-all duration-200">
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
