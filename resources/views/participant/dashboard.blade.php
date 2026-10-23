@extends('layouts.participant')

@php
    $statusBadgeMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'bg-primary-subtle text-primary border border-primary-subtle',
        \App\Enums\RegistrationStatus::UnderReview->value => 'bg-warning-subtle text-warning border border-warning-subtle',
        \App\Enums\RegistrationStatus::Accepted->value    => 'bg-success-subtle text-success border border-success-subtle',
        \App\Enums\RegistrationStatus::Rejected->value    => 'bg-danger-subtle text-danger border border-danger-subtle',
    ];

    $reg = $acceptedRegistration ?? $latestRegistration ?? ($user->registrations->first() ?? null);
    $sv = $reg?->status?->value;
    $badgeClass = $sv !== null ? ($statusBadgeMap[$sv] ?? 'bg-secondary-subtle text-secondary border') : 'bg-secondary-subtle text-secondary border';

    $latestStatus = $latestStatus ?? ($reg?->is_terminated ? 'Dinonaktifkan' : ($reg?->status?->value ?? ($reg?->status ?? '')));
    if (is_object($latestStatus) && enum_exists($latestStatus::class)) {
        $latestStatus = $latestStatus->value;
    }
    $latestStatus = is_string($latestStatus) ? ucfirst(strtolower($latestStatus)) : '';

    $canApplyNew = $canApplyNew ?? ($reg === null || in_array(strtolower((string)$latestStatus), ['rejected', 'completed', 'inactive', 'dinonaktifkan']));

    $totalAssignments = $totalAssignments ?? 0;
    $pendingCount = $pendingCount ?? 0;
    $doneCount = $doneCount ?? 0;
    $overdueCount = $overdueCount ?? 0;
    $assignments = $assignments ?? [];
@endphp

@section('title', 'Dashboard')

@section('content')
    @if(auth()->check() && (auth()->user()->is_default_password || auth()->user()->hasDefaultPassword() || \Illuminate\Support\Facades\Hash::check('password', auth()->user()->password)))
        <div class="alert alert-danger bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/40 dark:text-red-200 dark:border-red-700 px-3 py-1.5 rounded-lg relative mb-2 d-flex align-items-center justify-content-between gap-2.5 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-shield-exclamation-fill fs-5 text-red-600 flex-shrink-0"></i>
                <span class="fw-semibold text-xs sm:text-sm">Peringatan Keamanan: Kata sandi yang Anda gunakan saat ini sangat berisiko. Harap segera perbarui kata sandi Anda di menu Pengaturan Akun.</span>
            </div>
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-danger text-nowrap flex-shrink-0 font-medium py-1 px-2.5 text-xs">Ganti Password</a>
        </div>
    @endif

    {{-- Header --}}
    <div class="page-heading mb-2">
        <div class="page-heading-copy">
            <h1 class="h4 mb-0.5">Selamat Datang, {{ $user->name }}!</h1>
            <p class="text-muted small mb-0">
                Pantau status pendaftaran magang Anda di Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban.
            </p>
        </div>
    </div>

    @if($reg !== null && $reg->is_terminated)
        <div class="alert alert-danger border-2 rounded-3 mb-2 p-3 d-flex align-items-start" role="alert">
            <i class="bi bi-slash-circle-fill fs-4 text-danger me-2.5 flex-shrink-0 mt-0.5"></i>
            <div class="flex-grow-1">
                <h6 class="alert-heading fw-bold mb-1">Status Magang Sebelumnya Dinonaktifkan</h6>
                <p class="mb-2 small">Status operasional magang Anda sebelumnya (<code>{{ $reg->nomor_pendaftaran }}</code>) telah dinonaktifkan oleh Admin. Anda diperbolehkan mengajukan pendaftaran magang baru.</p>
                @if($reg->catatan_penonaktifan)
                    <div class="small text-muted bg-white p-2 rounded border mb-2">
                        <strong>Catatan Admin:</strong> {{ $reg->catatan_penonaktifan }}
                    </div>
                @endif
                <a href="{{ route('participant.registrations.create') }}" class="btn btn-sm btn-primary rounded-3 px-3 py-1.5 font-semibold text-xs shadow-sm transition-all duration-200">
                    <i class="bi bi-send-fill me-1"></i> Ajukan Pendaftaran Magang Baru
                </a>

            </div>
        </div>
    @endif

    {{-- NEW REGISTRATION PROMPT (If eligible and no active deactivation notice is displayed) --}}
    @if(!($reg !== null && $reg->is_terminated) && (in_array($latestStatus, ['Rejected', 'Completed', 'Inactive', 'Dinonaktifkan', 'rejected', 'completed', 'inactive', 'dinonaktifkan']) || $canApplyNew))
        <div class="alert alert-success border-2 rounded-3 mb-2 p-3 d-flex align-items-center justify-content-between flex-wrap gap-2.5 shadow-sm" role="alert">
            <div class="d-flex align-items-center gap-2.5">
                <i class="bi bi-plus-circle-fill fs-3 text-success flex-shrink-0"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-0.5">Pengajuan Pendaftaran Magang Baru</h6>
                    <p class="mb-0 small text-muted">Status pendaftaran Anda saat ini mengizinkan pengajuan permohonan magang baru.</p>
                </div>
            </div>
            <a href="{{ route('participant.registrations.create') }}" class="btn btn-sm btn-success fw-bold px-3 py-2 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex-shrink-0">
                <i class="bi bi-plus-circle-fill me-1"></i> + Ajukan Pendaftaran Baru
            </a>
        </div>
    @endif

    <!-- Top Statistics Row (Ultra-Compact, 6 Columns - Extended with Assignment Stats) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-1.5 mb-2">
        <!-- Card 1: Status Profil -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-2 sm:p-2.5 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-uppercase fw-bold mb-0.5" style="font-size: 0.65rem; letter-spacing: 0.5px;">Status Profil</p>
                    <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold {{ $hasProfile ? 'text-success' : 'text-danger' }}">{{ $hasProfile ? 'Terisi Lengkap' : 'Belum Terisi' }}</div>
                </div>
                <div class="{{ $hasProfile ? 'text-success' : 'text-danger' }}">
                    <i class="bi {{ $hasProfile ? 'bi-person-check-fill' : 'bi-person-exclamation' }} fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Pendaftaran -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-2 sm:p-2.5 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase fw-bold mb-0.5 text-gray-900 dark:text-white text-dark" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Pendaftaran</p>
                    <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold text-dark">{{ $totalRegistrations ?? ($total_pendaftaran ?? 0) }}</div>
                </div>
                <div class="text-primary">
                    <i class="bi bi-file-earmark-text-fill fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Status Terbaru -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-2 sm:p-2.5 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-uppercase fw-bold mb-0.5" style="font-size: 0.65rem; letter-spacing: 0.5px;">Status Terbaru</p>
                    @if ($reg !== null)
                        <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold {{ $reg->is_terminated ? 'text-danger' : ($sv === 'accepted' ? 'text-success' : ($sv === 'rejected' ? 'text-danger' : ($sv === 'under_review' ? 'text-warning' : 'text-primary'))) }}">
                            {{ $reg->is_terminated ? 'Dinonaktifkan' : $reg->status->label() }}
                        </div>
                    @else
                        <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold text-secondary">Belum Ada</div>
                    @endif
                </div>
                <div class="{{ $reg !== null ? ($reg->is_terminated ? 'text-danger' : ($sv === 'accepted' ? 'text-success' : ($sv === 'rejected' ? 'text-danger' : ($sv === 'under_review' ? 'text-warning' : 'text-primary')))) : 'text-secondary' }}">
                    <i class="bi {{ $reg !== null ? ($reg->is_terminated || $sv === 'rejected' ? 'bi-x-circle-fill' : ($sv === 'accepted' ? 'bi-check-circle-fill' : ($sv === 'under_review' ? 'bi-clock-fill' : 'bi-info-circle-fill'))) : 'bi-info-circle-fill' }} fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Surat Balasan -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-2 sm:p-2.5 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-uppercase fw-bold mb-0.5" style="font-size: 0.65rem; letter-spacing: 0.5px;">Surat Balasan</p>
                    <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold {{ $documentInfo['surat_balasan_exists'] ? 'text-success' : 'text-secondary' }}">
                        {{ $documentInfo['surat_balasan_exists'] ? 'Tersedia' : 'Belum Ada' }}
                    </div>
                </div>
                <div class="{{ $documentInfo['surat_balasan_exists'] ? 'text-success' : 'text-secondary' }}">
                    <i class="bi {{ $documentInfo['surat_balasan_exists'] ? 'bi-file-earmark-check-fill' : 'bi-envelope-fill' }} fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Penugasan -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-2 sm:p-2.5 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-uppercase fw-bold mb-0.5 text-gray-900 dark:text-white text-dark" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Penugasan</p>
                    <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold text-dark">{{ $totalAssignments ?? ($total_penugasan ?? 0) }}</div>
                </div>
                <div class="text-indigo">
                    <i class="bi bi-list-task fs-5"></i>
                </div>
            </div>
        </div>

        <!-- Card 6: Tugas Tertunda -->
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body p-2 sm:p-2.5 d-flex align-items-center justify-content-between">
                <div>
                    <p class="text-muted text-uppercase fw-bold mb-0.5" style="font-size: 0.65rem; letter-spacing: 0.5px;">Tugas Tertunda</p>
                    <div class="text-base sm:text-lg mb-0 text-gray-900 dark:text-white font-bold {{ $overdueCount > 0 ? 'text-danger' : ($pendingCount > 0 ? 'text-warning' : 'text-success') }}">
                        {{ $overdueCount > 0 ? $overdueCount . ' Terlambat' : ($pendingCount > 0 ? $pendingCount . ' Tugas' : 'Selesai') }}
                    </div>
                </div>
                <div class="{{ $overdueCount > 0 ? 'text-danger' : ($pendingCount > 0 ? 'text-warning' : 'text-success') }}">
                    <i class="bi {{ $overdueCount > 0 ? 'bi-exclamation-triangle-fill' : ($pendingCount > 0 ? 'bi-clock-history' : 'bi-check2-circle') }} fs-5"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- ASSIGNMENT DASHBOARD WIDGET (Always visible after stats) --}}
    <div class="panel p-3.5 mb-3">
        <div class="panel-header border-bottom pb-2 mb-2.5 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div>
                <h2 class="h5 mb-1 section-title">
                    <i class="bi bi-clipboard-check" aria-hidden="true"></i>
                    <span>Penugasan Aktif</span>
                </h2>
                <p class="text-muted small mb-0">
                    Materi & Tugas dari Pembimbing Anda.
                    <a href="{{ route('participant.materials.index') }}" class="text-primary fw-bold text-decoration-none ms-1" style="cursor: pointer; transition: all 0.2s ease-in-out;" onmouseover="this.style.textDecoration='underline'; this.style.color='#0a58ca';" onmouseout="this.style.textDecoration='none'; this.style.color='';">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </p>
            </div>
            <div class="d-flex gap-1.5 flex-wrap">
                <span class="badge bg-amber-100 text-amber-950 border border-amber-400 rounded-pill px-2.5 py-1 text-xs font-bold shadow-xs">
                    <i class="bi bi-clock-history text-amber-700 me-1"></i> Tertunda: {{ $pendingCount }}
                </span>
                @if($overdueCount > 0)
                    <span class="badge bg-rose-100 text-rose-950 border border-rose-400 rounded-pill px-2.5 py-1 text-xs font-bold shadow-xs">
                        <i class="bi bi-exclamation-triangle-fill text-rose-700 me-1"></i> Terlambat: {{ $overdueCount }}
                    </span>
                @endif
                <span class="badge bg-emerald-100 text-emerald-950 border border-emerald-400 rounded-pill px-2.5 py-1 text-xs font-bold shadow-xs">
                    <i class="bi bi-check2-circle text-emerald-700 me-1"></i> Selesai: {{ $doneCount }}
                </span>
            </div>
        </div>

        @if(empty($assignments))
            <div class="text-center py-3">
                <div class="avatar-md bg-indigo bg-opacity-10 text-indigo mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle">
                    <i class="bi bi-clipboard2-x fs-4"></i>
                </div>
                <h6 class="fw-bold mb-1 text-muted">Belum Ada Penugasan</h6>
                <p class="text-muted small mb-0 mx-auto" style="max-width: 480px;">
                    Saat ini Pembimbing di divisi Anda belum mempublikasikan materi atau penugasan. Silakan cek kembali secara berkala.
                </p>
            </div>
        @else
            <div class="list-group list-group-flush border rounded-3 overflow-hidden">
                @foreach($assignments as $assignment)
                    @php
                        $isSubmitted = !empty($assignment->submitted);
                        $isOverdue = !empty($assignment->overdue);
                        $srcType = $assignment->source_type ?? 'material';
                        $isTask = (bool) ($assignment->is_task ?? false);
                        $deadlineVal = $assignment->deadline ?? null;
                        $deadlineStr = '';
                        $overdueBadge = '';
                        if ($deadlineVal !== null) {
                            $carbonDeadline = \Illuminate\Support\Carbon::parse($deadlineVal);
                            $deadlineStr = $carbonDeadline->translatedFormat('d M Y H:i');
                            if ($isOverdue && !$isSubmitted) {
                                $overdueBadge = '<span class="badge bg-danger-subtle text-danger-emphasis border border-danger-subtle rounded-pill px-2 py-0.5 ms-1 small">Terlambat</span>';
                            }
                        }
                    @endphp
                    <a href="{{ route('participant.materials.show', $assignment->id) }}" class="list-group-item list-group-item-action d-flex align-items-center gap-2.5 p-2.5 border-0 border-bottom last:border-0 @if(!$isSubmitted && $isOverdue) bg-danger bg-opacity-5 @endif">
                        <div class="flex-shrink-0">
                            @if($srcType === 'task')
                                <div class="avatar-md bg-indigo bg-opacity-10 text-indigo d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-list-task fs-4"></i>
                                </div>
                            @elseif($isTask)
                                <div class="avatar-md bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-clipboard-check fs-4"></i>
                                </div>
                            @else
                                <div class="avatar-md bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center rounded-3">
                                    <i class="bi bi-book fs-4"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                <h6 class="mb-0 fw-bold text-truncate flex-grow-1">{{ $assignment->title }}</h6>
                                @if($srcType === 'task')
                                    <span class="badge bg-blue-100 text-blue-950 border border-blue-400 rounded-pill px-2.5 py-0.5 text-xs font-bold flex-shrink-0">
                                        Tugas
                                    </span>
                                @elseif($isTask)
                                    <span class="badge bg-amber-100 text-amber-950 border border-amber-400 rounded-pill px-2.5 py-0.5 text-xs font-bold flex-shrink-0">
                                        Tugas
                                    </span>
                                @else
                                    <span class="badge bg-blue-100 text-blue-950 border border-blue-400 rounded-pill px-2.5 py-0.5 text-xs font-bold flex-shrink-0">
                                        Materi
                                    </span>
                                @endif
                                @if($isSubmitted)
                                    <span class="badge bg-emerald-100 text-emerald-950 border border-emerald-400 rounded-pill px-2.5 py-0.5 text-xs font-bold flex-shrink-0">
                                        <i class="bi bi-check2-circle text-emerald-700 me-1"></i> Sudah Dikumpulkan
                                    </span>
                                @endif
                                {!! $overdueBadge !!}
                            </div>
                            <p class="mb-0 small text-muted text-truncate" style="-webkit-line-clamp: 1;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($assignment->description), 100) }}
                            </p>
                            @if($deadlineStr !== '' && $isTask)
                                @php
                                    $deadlineColorClass = 'text-gray-500';
                                    $deadlineExtraText = '';
                                    if (!$isSubmitted && isset($carbonDeadline)) {
                                        if ($carbonDeadline->isPast()) {
                                            $deadlineColorClass = 'text-red-600 font-bold';
                                            $deadlineExtraText = ' (Terlewat)';
                                        } elseif ($carbonDeadline->diffInHours(now()) < 24) {
                                            $deadlineColorClass = 'text-orange-500 font-bold';
                                        }
                                    }
                                @endphp
                                <div class="mt-0.5">
                                    <small class="{{ $deadlineColorClass }} d-inline-flex align-items-center">
                                        <i class="bi bi-clock-fill me-1"></i>
                                        Deadline: {{ $deadlineStr }}{{ $deadlineExtraText }}
                                    </small>
                                </div>
                            @endif
                        </div>

                        <div class="flex-shrink-0 d-flex gap-2 align-items-center">
                            @if($isSubmitted)
                                <span class="text-success d-none d-md-inline">
                                    <i class="bi bi-check2-circle fs-4"></i>
                                </span>
                            @elseif($isOverdue && $isTask)
                                <span class="text-danger d-none d-md-inline">
                                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                </span>
                            @elseif($isTask)
                                <span class="text-warning d-none d-md-inline">
                                    <i class="bi bi-pencil-square fs-4"></i>
                                </span>
                            @else
                                <span class="text-info d-none d-md-inline">
                                    <i class="bi bi-arrow-right-circle fs-4"></i>
                                </span>
                            @endif
                            <i class="bi bi-chevron-right text-muted fs-5 d-none d-sm-inline"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Container Utama: Kolom Kiri & Kolom Kanan (Ultra-Dense Grid) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-3.5 mt-0">
        {{-- Left Column: Personal Information & Admin Notes --}}
        <div class="lg:col-span-4 space-y-3">
            {{-- Personal Information Panel --}}
            <div class="panel p-3.5 mb-3">
                <div class="panel-header border-bottom pb-2 mb-2.5">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-person-vcard" aria-hidden="true"></i>
                            <span>Informasi Pribadi</span>
                        </h2>
                        <p class="text-muted small mb-0">Data akun & biodata peserta.</p>
                    </div>
                    @if ($hasProfile)
                        <a href="{{ route('participant.profile.edit') }}" class="btn btn-outline-secondary bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-lg px-2.5 py-1 font-medium text-xs shadow-sm hover:shadow-md transition-all duration-200">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>

                @if (!$hasProfile)
                    <div class="alert alert-warning mb-0 p-3" role="alert">
                        <h6 class="alert-heading fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Profil Belum Lengkap</h6>
                        <p class="small mb-2.5">Lengkapi profil Anda agar dapat mengajukan pendaftaran magang.</p>
                        <a href="{{ route('participant.profile.create') }}" class="btn btn-warning text-dark fw-bold rounded-3 py-1.5 px-3 text-xs shadow-sm transition-all duration-200 w-100">
                            <i class="bi bi-person-plus-fill me-1"></i> Isi Profil Sekarang
                        </a>
                    </div>
                @else
                    <div class="d-flex align-items-center gap-2.5 mb-2.5 pb-2.5 border-bottom">
                        @if(!empty($profile->foto_url))
                            <img src="{{ $profile->foto_url }}" alt="{{ $profile->nama_lengkap ?? $user->name }}"
                                 class="avatar-lg rounded-circle border border-2 border-primary object-fit-cover shadow-sm"
                                 onerror="this.style.display='none'; this.nextElementSibling.classList.remove('d-none');">
                            <div class="avatar-lg bg-primary bg-opacity-10 text-primary d-none align-items-center justify-content-center fw-bold fs-3 rounded-circle border border-2 border-primary shadow-sm">
                                {{ mb_substr($profile->nama_lengkap ?? $user->name, 0, 1) }}
                            </div>
                        @else
                            <div class="avatar-lg bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-3 rounded-circle border border-2 border-primary shadow-sm">
                                {{ mb_substr($profile->nama_lengkap ?? $user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="min-w-0 flex-grow-1">
                            <div class="mb-0.5">
                                <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill px-2 py-0.5 small fw-bold">
                                    {{ $profile->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa' }}
                                </span>
                            </div>
                            <h3 class="h6 fw-bold mb-0.5 text-truncate">{{ $profile->nama_lengkap ?? $user->name }}</h3>
                            <p class="text-muted small mb-0.5 text-truncate"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                            <p class="text-muted small mb-0 text-truncate"><i class="bi bi-telephone me-1"></i>{{ $profile->no_telepon ?? '-' }}</p>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1.5">
                            <span class="text-muted"><i class="bi bg-primary-subtle me-1"></i> {{ $profile->institutionLabel() }}</span>
                            <span class="fw-semibold text-end ms-2">{{ $profile->institusi ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1.5">
                            <span class="text-muted"><i class="bi bi-mortarboard me-1"></i> {{ $profile->numberLabel() }}</span>
                            <span class="fw-semibold font-monospace">{{ $profile->numberValue() }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1.5">
                            <span class="text-muted"><i class="bi bi-book me-1"></i> {{ $profile->majorLabel() }}</span>
                            <span class="fw-semibold text-end ms-2">{{ $profile->jurusan ?? '-' }}</span>
                        </li>
                        @if (!empty($profile->nik))
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-1.5">
                                <span class="text-muted"><i class="bi bi-card-heading me-1"></i> NIK</span>
                                <span class="fw-semibold font-monospace">{{ $profile->nik }}</span>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>

            {{-- Admin Notes (If Rejected / Available) --}}
            @if ($reg !== null && !empty(trim($reg->catatan_admin ?? '')))
                <div class="panel border-info p-3.5 mb-3">
                    <div class="panel-header border-bottom pb-2 mb-2">
                        <h2 class="h6 mb-0 text-info section-title">
                            <i class="bi bi-chat-left-dots" aria-hidden="true"></i>
                            <span>Catatan Admin</span>
                        </h2>
                    </div>
                    <div class="alert alert-info mb-0 p-2.5">
                        <p class="mb-0 small fst-italic">"{{ $reg->catatan_admin }}"</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column: Application Details or Empty State --}}
        <div class="lg:col-span-8 space-y-3">
            @if ($reg === null)
                {{-- PROFESSIONAL EMPTY STATE --}}
                <div class="panel text-center py-4 px-3.5 mb-3">
                    <div class="avatar-lg bg-primary bg-opacity-10 text-primary mx-auto mb-2.5 d-flex align-items-center justify-content-center rounded-circle">
                        <i class="bi bi-journal-plus fs-2"></i>
                    </div>
                    <h3 class="h5 fw-bold mb-1.5">Belum Ada Pendaftaran Magang</h3>
                    <p class="text-muted small mb-3 mx-auto" style="max-width: 480px;">
                        Anda belum mengajukan pendaftaran magang. Silakan lengkapi profil Anda terlebih dahulu, kemudian pilih posisi magang yang tersedia.
                    </p>
                    @if (!$hasProfile)
                        <a href="{{ route('participant.profile.create') }}" class="btn btn-primary rounded-3 px-3.5 py-2 font-semibold text-xs shadow-sm transition-all duration-200">
                            <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Lengkapi Profil Dulu
                        </a>
                    @else
                        <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary rounded-3 px-3.5 py-2 font-semibold text-xs shadow-sm transition-all duration-200">
                            <i class="bi bi-send me-1" aria-hidden="true"></i> Ajukan Pendaftaran Magang
                        </a>
                    @endif
                </div>

            @else
                {{-- APPLICATION DETAILS CARD --}}
                <div class="panel p-3.5 mb-3">
                    <div class="panel-header border-bottom pb-2 mb-2.5 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-file-earmark-text" aria-hidden="true"></i>
                                <span>Informasi Pendaftaran Magang</span>
                            </h2>
                            <p class="text-muted small mb-0">Kode Pendaftaran: <strong class="font-monospace text-primary">{{ $reg->nomor_pendaftaran }}</strong></p>
                        </div>
                        <div>
                            <span class="badge {{ $badgeClass }} px-2.5 py-1.5 small rounded-pill">
                                {{ $reg->status->label() }}
                            </span>
                        </div>
                    </div>

                    {{-- Ultra-Dense Sub-Info Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <div class="p-2.5 bg-light rounded-3">
                                <span class="text-muted small d-block mb-0.5">Posisi Magang</span>
                                <strong class="fs-6 text-body">{{ $reg->position?->nama_posisi ?? '-' }}</strong>
                            </div>
                        </div>

                        <div>
                            <div class="p-2.5 bg-light rounded-3">
                                <span class="text-muted small d-block mb-0.5">Periode Magang</span>
                                <strong class="fs-6 text-body">
                                    {{ $reg->periode_label ?? ($reg->tanggal_mulai?->translatedFormat('d M Y').' — '.$reg->tanggal_selesai?->translatedFormat('d M Y') ?? '-') }}
                                </strong>
                            </div>
                        </div>

                        <div>
                            <div class="p-2.5 bg-light rounded-3">
                                <span class="text-muted small d-block mb-0.5">Tanggal Pengajuan</span>
                                <span class="fw-semibold text-body small">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $reg->tanggal_submit?->translatedFormat('l, d M Y (H:i)') ?? '-' }} WIB
                                </span>
                            </div>
                        </div>

                        <div>
                            <div class="p-2.5 bg-light rounded-3">
                                <span class="text-muted small d-block mb-0.5">Status Verifikasi</span>
                                <span class="fw-semibold text-body small">
                                    <i class="bi bi-shield-check me-1"></i>
                                    {{ $reg->status->label() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- UPLOADED DOCUMENTS PANEL --}}
                <div class="panel p-3.5 mb-3">
                    <div class="panel-header border-bottom pb-2 mb-2.5">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-folder2-open" aria-hidden="true"></i>
                                <span>Dokumen & Berkas Magang</span>
                            </h2>
                            <p class="text-muted small mb-0">Berkas administrasi pendaftaran magang.</p>
                        </div>
                    </div>

                    {{-- 4 Sub-card Dokumen (Ultra-Dense Grid) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5">
                        {{-- Document 1: CV --}}
                        <div>
                            <div class="p-2.5 border rounded-3 text-center h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <i class="bi bi-file-earmark-person fs-2 {{ $documentInfo['cv_exists'] ? 'text-primary' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-1.5 mb-0.5">CV / Riwayat Hidup</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['cv_exists'] ? 'File Terunggah' : 'Belum Ada File' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['cv_exists'])
                                        <a href="{{ $documentInfo['cv_url'] }}" target="_blank" class="btn btn-outline-primary btn-sm w-100 py-1 text-xs">
                                            <i class="bi bi-download me-1"></i> Unduh CV
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-1.5">Tidak Ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Document 2: Surat Pengantar --}}
                        <div>
                            <div class="p-2.5 border rounded-3 text-center h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <i class="bi bi-envelope-paper fs-2 {{ $documentInfo['surat_pengantar_exists'] ? 'text-success' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-1.5 mb-0.5">Surat Pengantar</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['surat_pengantar_exists'] ? 'File Terunggah' : 'Belum Ada File' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['surat_pengantar_exists'])
                                        <a href="{{ $documentInfo['surat_pengantar_url'] }}" target="_blank" class="btn btn-outline-success btn-sm w-100 py-1 text-xs">
                                            <i class="bi bi-download me-1"></i> Unduh Surat
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-1.5">Tidak Ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Document 3: Proposal Magang --}}
                        <div>
                            <div class="p-2.5 border rounded-3 text-center h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <i class="bi bi-file-earmark-pdf fs-2 {{ $documentInfo['proposal_magang_exists'] ? 'text-info' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-1.5 mb-0.5">Proposal Magang</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['proposal_magang_exists'] ? 'File Terunggah' : 'Belum Ada File' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['proposal_magang_exists'])
                                        <a href="{{ $documentInfo['proposal_magang_url'] }}" target="_blank" class="btn btn-outline-info btn-sm w-100 py-1 text-xs">
                                            <i class="bi bi-download me-1"></i> Unduh Proposal
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-1.5">Tidak Ada</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Document 4: Surat Balasan (If Available) --}}
                        <div>
                            <div class="p-2.5 border rounded-3 text-center h-100 d-flex flex-column justify-content-between {{ $documentInfo['surat_balasan_exists'] ? 'border-success bg-success bg-opacity-10' : '' }}">
                                <div>
                                    <i class="bi bi-file-earmark-check fs-2 {{ $documentInfo['surat_balasan_exists'] ? 'text-success' : 'text-muted' }}"></i>
                                    <h4 class="h6 fw-bold mt-1.5 mb-0.5">Surat Balasan (Admin)</h4>
                                    <small class="text-muted d-block mb-2">
                                        {{ $documentInfo['surat_balasan_exists'] ? 'Resmi Diterbitkan' : 'Belum Diterbitkan' }}
                                    </small>
                                </div>
                                <div>
                                    @if ($documentInfo['surat_balasan_exists'])
                                        <a href="{{ $documentInfo['surat_balasan_download_route'] }}" class="btn btn-success btn-sm w-100 fw-bold py-1 text-xs">
                                            <i class="bi bi-download me-1"></i> Unduh Surat Balasan
                                        </a>
                                    @else
                                        <span class="badge bg-secondary w-100 py-1.5">Belum Tersedia</span>
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
