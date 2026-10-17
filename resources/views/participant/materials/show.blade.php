@extends('layouts.participant')
@section('title', $material->title)
@section('content')
@php
    $sourceType = $material->source_type ?? 'material';
    $isTaskSubmission = !empty($submission->source_task_submission);
    $hasSubmissionLink = !empty($submission->submission_link);
    $hasSubmissionFile = !empty($submission->file_path);

    $submissionType = $material->submission_type;
    $isFileAllowed = $material->isFileSubmission();
    $isLinkAllowed = $material->isLinkSubmission();
    $onlyLinkAllowed = (!$isFileAllowed && $isLinkAllowed);
    $onlyFileAllowed = ($isFileAllowed && !$isLinkAllowed);

    $acceptAttr = $material->accepted_extensions;
    $acceptedMimes = $material->accepted_mimes;
    $submissionTypeLabel = $material->submission_type_label;

    $allowedFormatsHuman = $submissionTypeLabel;
    $fileFormatsHuman = $submissionTypeLabel;

    $isVideoWatched = $submission ? (bool) ($submission->is_video_watched ?? false) : false;
@endphp
<div class="max-w-7xl mx-auto px-2 sm:px-4 pt-1 pb-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h1 class="h4 fw-bold mb-0 text-gray-900" style="letter-spacing: -0.02em;">{{ $material->title }}</h1>
        <a href="{{ route('participant.materials.index') }}" class="btn btn-secondary bg-slate-800 hover:bg-slate-700 active:bg-slate-900 text-white rounded-2 font-bold text-xs shadow-sm py-1.5 px-3 d-inline-flex align-items-center" style="background-color: #1f2937 !important; color: #ffffff !important; border: 1px solid #111827;">
            <svg style="width: 0.875rem; height: 0.875rem; margin-right: 0.375rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Materi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border rounded-2 shadow-sm mb-3 py-2 px-3 d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success me-2 flex-shrink-0"></i>
            <div class="text-xs">
                <strong class="alert-heading fw-bold d-block mb-0.5">Berhasil!</strong>
                <p class="mb-0 text-muted">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border rounded-2 shadow-sm mb-3 py-2 px-3 d-flex align-items-center" role="alert">
            <i class="bi bi-x-circle-fill fs-5 text-danger me-2 flex-shrink-0"></i>
            <div class="text-xs">
                <strong class="alert-heading fw-bold d-block mb-0.5">Error</strong>
                <p class="mb-0 text-muted">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="row g-2.5">
        {{-- ========================================= --}}
        {{-- KIRI: KONTEN MATERI + INSTRUKSI (col-lg-8) --}}
        {{-- ========================================= --}}
        <div class="col-lg-8">

            {{-- =============== KARTU 1: KONTEN MATERI =============== --}}
            <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
                <div class="card-header bg-white border-bottom py-2 px-3 sm:px-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h6 class="m-0 fw-bold text-primary">
                            <i class="bi bi-journal-text me-1.5"></i>Konten Materi
                        </h6>
                        @if($material->is_task)
                            <span style="background-color: #fef3c7 !important; color: #92400e !important; padding: 0.2rem 0.5rem !important; border-radius: 9999px !important; font-size: 0.72rem !important; font-weight: 700 !important; border: 1px solid #fde68a !important;" class="d-inline-flex align-items-center">
                                <i class="bi bi-clipboard-check me-1"></i> Tugas
                            </span>
                        @endif
                        @if($sourceType === 'task')
                            <span style="background-color: #ede9fe !important; color: #5b21b6 !important; padding: 0.2rem 0.5rem !important; border-radius: 9999px !important; font-size: 0.72rem !important; font-weight: 700 !important; border: 1px solid #ddd6fe !important;" class="d-inline-flex align-items-center">
                                <i class="bi bi-list-task me-1"></i> Penugasan
                            </span>
                        @endif
                        @if($material->is_task && $submission)
                            <span style="background-color: #d1fae5 !important; color: #065f46 !important; padding: 0.2rem 0.5rem !important; border-radius: 9999px !important; font-size: 0.72rem !important; font-weight: 700 !important; border: 1px solid #6ee7b7 !important;" class="d-inline-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-1"></i> Sudah Dikumpulkan
                            </span>
                        @elseif($material->is_task && !$submission)
                            @php
                                $_deadlineCarbon = !empty($material->deadline) ? \Illuminate\Support\Carbon::parse($material->deadline) : null;
                                $_isOverdue = $_deadlineCarbon && $_deadlineCarbon->isPast();
                            @endphp
                            @if($_isOverdue)
                                <span style="background-color: #fee2e2 !important; color: #991b1b !important; padding: 0.2rem 0.5rem !important; border-radius: 9999px !important; font-size: 0.72rem !important; font-weight: 700 !important; border: 1px solid #fecaca !important;" class="d-inline-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Terlambat
                                </span>
                            @else
                                <span style="background-color: #dbeafe !important; color: #1e40af !important; padding: 0.2rem 0.5rem !important; border-radius: 9999px !important; font-size: 0.72rem !important; font-weight: 700 !important; border: 1px solid #bfdbfe !important;" class="d-inline-flex align-items-center">
                                    <i class="bi bi-hourglass-split me-1"></i> Belum Dikumpulkan
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="card-body p-3 sm:p-4">
                    <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-1.5" style="letter-spacing: 0.05em;">Deskripsi</label>
                    <div class="p-3 bg-gray-50 rounded-2 border border-gray-200 text-xs" style="background-color: #f8f9fa;">
                        <div class="text-gray-800 lh-base">
                            {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-600 dark:text-blue-400 underline font-semibold hover:text-blue-800 transition-colors" rel="noopener noreferrer">$1</a>', e($material->description))) !!}
                        </div>
                    </div>

                    @if(!empty($material->file_materi))
                        <div class="mt-3">
                            <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-1.5" style="letter-spacing: 0.05em;">
                                <i class="bi bi-file-earmark-text text-primary me-1"></i>Dokumen Materi
                            </label>
                            <div class="p-2.5 border rounded-2 bg-light d-flex align-items-center justify-content-between gap-2.5">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <i class="bi bi-file-earmark-text fs-3 text-primary flex-shrink-0"></i>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-xs text-truncate">
                                            {{ basename($material->file_materi) }}
                                        </div>
                                        <small class="text-muted text-xs">
                                            Lampiran dokumen dari Pembimbing
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_materi) }}" target="_blank" class="btn btn-primary btn-sm rounded-2 flex-shrink-0 px-3 py-1.5 fw-bold text-xs shadow-sm">
                                    <i class="bi bi-download me-1"></i>Unduh Dokumen
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(!empty($material->youtube_url))
                        <div class="mt-3" id="materialVideoSection">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                <label class="block text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-0" style="letter-spacing: 0.05em;">
                                    <i class="bi bi-youtube text-danger me-1"></i>Video Pembelajaran
                                </label>
                                <span id="video-status-badge"
                                      class="d-inline-flex align-items-center px-2.5 py-1 text-xs fw-bold rounded-pill border"
                                      style="
                                        @if($isVideoWatched)
                                          background-color: #d1fae5 !important; color: #065f46 !important; border-color: #6ee7b7 !important;
                                        @else
                                          background-color: #fee2e2 !important; color: #991b1b !important; border-color: #fecaca !important;
                                        @endif
                                      ">
                                    <i class="bi @if($isVideoWatched) bi-check-circle-fill @else bi-hourglass-split @endif me-1"></i>
                                    <span id="videoStatusText">@if($isVideoWatched) Tuntas Ditonton @else Belum Selesai (Tonton tanpa skip) @endif</span>
                                </span>
                            </div>
                            <div id="video-container-card"
                                 class="ratio ratio-16x9 rounded-2 overflow-hidden shadow-sm"
                                 style="
                                   border-width: 2px !important;
                                   border-style: solid !important;
                                   border-color: @if($isVideoWatched) #10b981 !important @else #ef4444 !important @endif;
                                   transition: border-color 0.4s ease, box-shadow 0.4s ease;
                                 ">
                                @if($videoId)
                                    @php
                                        $ytId = $videoId;
                                    @endphp
                                    <iframe
                                        id="embedded-yt-player"
                                        src="https://www.youtube.com/embed/{{ $ytId }}?enablejsapi=1&origin={{ urlencode(request()->getSchemeAndHttpHost()) }}"
                                        title="YouTube video player"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-decoration-none text-muted bg-light">
                                        <i class="bi bi-play-circle-fill fs-2 text-primary mb-2"></i>
                                        <span class="fw-bold text-xs">Buka Video di YouTube</span>
                                        <span class="text-xs">{{ $material->youtube_url }}</span>
                                    </a>
                                @endif
                            </div>
                            @if(!$videoId)
                                <div class="mt-2 text-center">
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-2 px-3 py-1.5 text-xs fw-bold">
                                        <i class="bi bi-youtube me-1"></i> Buka Link YouTube
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- =============== KARTU 2: INSTRUKSI PENUGASAN (jika is_task) =============== --}}
            @if($material->is_task)
                @php
                    $deadlineCarbon = !empty($material->deadline) ? \Illuminate\Support\Carbon::parse($material->deadline) : null;
                    $isOverdue = $deadlineCarbon && $deadlineCarbon->isPast() && !$submission;
                @endphp
                <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-2 px-3 sm:px-4 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h6 class="m-0 fw-bold text-warning">
                            <i class="bi bi-clipboard-check me-1.5"></i>Instruksi Penugasan
                        </h6>
                        @if($deadlineCarbon !== null)
                            <span class="badge rounded-pill px-2.5 py-1 text-xs fw-bold {{ $isOverdue ? 'bg-danger text-white border border-danger' : 'bg-primary-subtle text-primary border border-primary rounded-pill' }}" style="{{ $isOverdue ? '' : 'background-color: #dbeafe;' }}">
                                <i class="bi bi-clock-fill me-1"></i>
                                Deadline: {{ $deadlineCarbon->locale('id_ID')->isoFormat('dddd, D MMM YYYY HH:mm') }}
                                @if($isOverdue)
                                    <span class="ms-1">(Terlambat)</span>
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-3 sm:p-4">
                        {{-- BOX KUNING INSTRUKSI -- Background Kuning Solid --}}
                        <div class="p-3 rounded-2 mb-2.5" style="background-color: #fbbf24; border-radius: 0.5rem;">
                            <div class="text-gray-900 text-xs lh-base fw-medium" style="color: #1f2937;">
                                @if(!empty(trim($material->task_instruction ?? '')))
                                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-800 underline font-semibold hover:text-blue-900 transition-colors" rel="noopener noreferrer">$1</a>', e($material->task_instruction))) !!}
                                @else
                                    {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-800 underline font-semibold hover:text-blue-900 transition-colors" rel="noopener noreferrer">$1</a>', e($material->description))) !!}
                                @endif
                            </div>
                        </div>

                        {{-- FORMAT YANG DIIZINKAN --}}
                        @if($material->hasSubmissionType() || $material->is_task)
                            <div class="d-flex align-items-center gap-2 p-2 rounded-2 border border-gray-200 bg-gray-50 text-xs" style="background-color: #f8f9fa;">
                                <i class="bi bi-paperclip text-gray-500 fs-6 me-1"></i>
                                <span class="fw-semibold text-gray-600 me-1">Format yang diizinkan:</span>
                                <span class="fw-bold text-gray-800">
                                    {{ $allowedFormatsHuman }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ========================================= --}}
        {{-- KANAN: PANEL PENGUMPULAN TUGAS (col-lg-4) --}}
        {{-- ========================================= --}}
        <div class="col-lg-4">
            @if($material->is_task)
                <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden sticky-top" style="top: 1rem;">
                    <div class="card-header bg-white border-bottom py-2 px-3 sm:px-4">
                        <h6 class="m-0 fw-bold text-success">
                            <i class="bi bi-send-fill me-1.5"></i>Pengumpulan Tugas
                        </h6>
                    </div>
                    <div class="card-body p-3 sm:p-4">
                        @if($submission)
                            {{-- SUDAH DIKUMPULKAN --}}
                            <div class="alert alert-success border rounded-2 mb-3 py-2 px-3 d-flex align-items-start" role="alert">
                                <i class="bi bi-check2-circle fs-5 text-success me-2 flex-shrink-0 mt-0.5"></i>
                                <div class="flex-grow-1 min-w-0 text-xs">
                                    <strong class="alert-heading fw-bold d-block mb-1">Sudah Dikumpulkan!</strong>
                                    <p class="mb-1">
                                        <strong>Status:</strong>
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success rounded-pill px-2 py-0.5 text-xs">
                                            {{ ucfirst(is_string($submission->status) ? $submission->status : ($submission->status?->value ?? 'Submitted')) }}
                                        </span>
                                    </p>
                                    @php
                                        $subUpdated = isset($submission->updated_at) ? (\Illuminate\Support\Carbon::parse($submission->updated_at)) : now();
                                    @endphp
                                    <p class="mb-0 text-truncate text-muted" title="Dikumpulkan: {{ $subUpdated->locale('id_ID')->isoFormat('D MMM YYYY HH:mm') }}">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Dikumpulkan: {{ $subUpdated->locale('id_ID')->isoFormat('D MMM YYYY HH:mm') }}
                                    </p>
                                </div>
                            </div>

                            @if($hasSubmissionFile)
                                <div class="mb-3">
                                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-1.5" style="letter-spacing: 0.05em;">File Pengumpulan</label>
                                    <div class="p-2.5 border rounded-2 bg-light d-flex align-items-center justify-content-between gap-2.5">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0" style="min-width: 0;">
                                            <i class="bi bi-file-earmark-arrow-up fs-4 text-primary flex-shrink-0"></i>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-bold text-xs text-truncate" title="{{ basename($submission->file_path) }}">
                                                    {{ basename($submission->file_path) }}
                                                </div>
                                                <small class="text-muted d-block text-truncate text-xs" title="{{ \Illuminate\Support\Str::of($submission->file_path)->after('submissions/') }}">
                                                    {{ \Illuminate\Support\Str::of($submission->file_path)->after('submissions/')->limit(32) }}
                                                </small>
                                            </div>
                                        </div>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-2 flex-shrink-0 px-2.5 py-1 text-xs fw-bold">
                                            <i class="bi bi-download me-1"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($hasSubmissionLink)
                                <div class="mb-3">
                                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-1.5" style="letter-spacing: 0.05em;">Link Pengumpulan</label>
                                    <div class="p-2.5 border rounded-2 bg-light d-flex align-items-center justify-content-between gap-2.5">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0" style="min-width: 0;">
                                            <i class="bi bi-link-45deg fs-4 text-indigo flex-shrink-0"></i>
                                            <div class="flex-grow-1 min-w-0">
                                                <a href="{{ $submission->submission_link }}" target="_blank"
                                                   class="fw-bold text-xs text-truncate d-block text-decoration-none text-indigo"
                                                   title="{{ $submission->submission_link }}">
                                                    {{ \Illuminate\Support\Str::limit($submission->submission_link, 48) }}
                                                </a>
                                            </div>
                                        </div>
                                        <a href="{{ $submission->submission_link }}" target="_blank" class="btn btn-outline-indigo btn-sm rounded-2 flex-shrink-0 px-2.5 py-1 text-xs fw-bold">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(!empty(trim($submission->submission_text ?? '')) || !empty(trim($submission->grade_note ?? '')))
                                <div class="mb-3">
                                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-1.5" style="letter-spacing: 0.05em;">Catatan Pengumpulan</label>
                                    <div class="p-2.5 border rounded-2 bg-light text-xs lh-base">
                                        {!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-600 dark:text-blue-400 underline font-semibold hover:text-blue-800 transition-colors" rel="noopener noreferrer">$1</a>', e($submission->submission_text ?? $submission->grade_note ?? ''))) !!}
                                    </div>
                                </div>
                            @endif

                            {{-- =================================================================
                                 GANTI FILE / UNGGAH ULANG (RESUBMISSION) TOGGLE
                                 ================================================================= --}}
                            <div class="mb-3" x-data="{ openResubmit: {{ $errors->any() ? 'true' : 'false' }} }">
                                <button class="btn btn-warning btn-sm fw-bold w-100 rounded-2 py-1.5 px-3 d-inline-flex align-items-center justify-content-center gap-1.5 shadow-sm text-dark text-xs"
                                        type="button"
                                        @click="openResubmit = !openResubmit; if(openResubmit) { setTimeout(() => $el.scrollIntoView({behavior: 'smooth', block: 'start'}), 100); }">
                                    <i class="bi bi-arrow-repeat fs-6"></i>
                                    <span>Ganti File / Unggah Ulang</span>
                                    <i class="bi bi-chevron-double-down small" :class="{'d-none': openResubmit}"></i>
                                    <i class="bi bi-chevron-double-up small d-none" :class="{'d-block': openResubmit, 'd-none': !openResubmit}"></i>
                                </button>

                                <div x-show="openResubmit" x-transition.duration.300ms class="mt-2.5" style="display: none;">
                                    <div class="p-3 border border-warning-subtle bg-warning-subtle rounded-2">
                                        <p class="text-xs fw-semibold text-warning-emphasis mb-2 d-flex align-items-center gap-1.5">
                                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                                            Unggah ulang akan mengganti file / link pengumpulan Anda sebelumnya.
                                        </p>
                                        <form action="{{ route('participant.materials.submit', $material->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            @if($isFileAllowed)
                                                <div class="mb-2">
                                                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                                        <i class="bi bi-file-earmark-arrow-up me-1"></i>File Pengganti
                                                        @if($onlyFileAllowed)<span class="text-danger ms-1">*</span>@endif
                                                    </label>
                                                    <input
                                                        type="file"
                                                        name="file"
                                                        class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('file') is-invalid @enderror"
                                                        accept="{{ $acceptAttr }}">
                                                    <div class="form-text text-muted text-xs mt-1">
                                                        Format: <strong>
                                                            @if($onlyFileAllowed || $onlyLinkAllowed)
                                                                {{ $fileFormatsHuman }}
                                                            @else
                                                                Umum (PDF, DOC, DOCX, ZIP, RAR, JPG, JPEG, PNG)
                                                            @endif
                                                        </strong> (Maks. 10 MB)
                                                    </div>
                                                    @error('file')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                                </div>
                                            @endif

                                            @if($isLinkAllowed)
                                                <div class="mb-2">
                                                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                                        <i class="bi bi-link-45deg me-1"></i>Link Pengganti
                                                        @if($onlyLinkAllowed)<span class="text-danger ms-1">*</span>@endif
                                                    </label>
                                                    <input
                                                        type="url"
                                                        name="submission_link"
                                                        class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('submission_link') is-invalid @enderror"
                                                        placeholder="https://drive.google.com/... atau https://github.com/..."
                                                        value="{{ old('submission_link') }}">
                                                    <div class="form-text text-muted text-xs mt-1">
                                                        @if($onlyLinkAllowed)
                                                            Wajib: Masukkan link pengumpulan baru.
                                                        @else
                                                            Opsional: Masukkan link baru pengganti.
                                                        @endif
                                                    </div>
                                                    @error('submission_link')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                                </div>
                                            @endif

                                            <div class="mb-2.5">
                                                <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                                    <i class="bi bi-chat-square-text me-1"></i>Catatan (Opsional)
                                                </label>
                                                <textarea
                                                    name="submission_text"
                                                    class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('submission_text') is-invalid @enderror"
                                                    rows="2"
                                                    placeholder="Tambahkan catatan pengantar unggah ulang...">{{ old('submission_text', $submission->submission_text ?? '') }}</textarea>
                                                @error('submission_text')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                            </div>

                                            <button type="submit" class="btn btn-warning w-100 fw-bold py-1.5 px-3 text-xs rounded-2 shadow-sm d-inline-flex align-items-center justify-content-center gap-1.5 text-warning-emphasis">
                                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                                <span>Simpan Unggahan Ulang</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @php
                                $reviewContent = trim($submission->grade_note ?? '');
                                $reviewAlt = trim($submission->review_note ?? '');
                                $gradeVal = trim($submission->grade ?? '');
                                $hasReview = ($gradeVal !== '' || $reviewContent !== '' || $reviewAlt !== '');
                            @endphp
                            @if($hasReview)
                                <div class="alert alert-primary border rounded-2 mb-0 py-2.5 px-3 text-xs" role="alert">
                                    <strong class="alert-heading fw-bold d-block mb-2">
                                        <i class="bi bi-clipboard2-pencil-fill me-1.5"></i>Hasil Review Pembimbing
                                    </strong>
                                    @if($gradeVal !== '')
                                        <div class="mb-2">
                                            <span class="fw-semibold">Nilai:</span>
                                            <span class="badge bg-primary text-white border border-primary rounded-pill px-2.5 py-0.5 ms-1.5 fw-bold text-xs">
                                                {{ $gradeVal }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($reviewContent !== '')
                                        <div class="mb-0">
                                            <div class="fw-bold mb-1">Catatan Review:</div>
                                            <div class="bg-white p-2.5 rounded-2 border lh-base">
                                                {!! nl2br(e($reviewContent)) !!}
                                            </div>
                                        </div>
                                    @elseif($reviewAlt !== '')
                                        <div class="mb-0">
                                            <div class="fw-bold mb-1">Catatan Review:</div>
                                            <div class="bg-white p-2.5 rounded-2 border lh-base">
                                                {!! nl2br(e($reviewAlt)) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            {{-- FORM BELUM DIKUMPULKAN --}}
                            <form action="{{ route('participant.materials.submit', $material->id) }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                                @csrf

                                @if($isFileAllowed)
                                    <div>
                                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                            <i class="bi bi-file-earmark-arrow-up me-1 text-gray"></i>Upload File Tugas
                                            @if($onlyFileAllowed)<span class="text-danger ms-1">*</span>@endif
                                        </label>
                                        <input
                                            type="file"
                                            name="file"
                                            class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('file') is-invalid @enderror"
                                            accept="{{ $acceptAttr }}"
                                            @if($onlyFileAllowed) required @endif>
                                        <div class="form-text text-muted text-xs mt-1">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Format: <strong>
                                                @if($onlyFileAllowed || $onlyLinkAllowed)
                                                    {{ $fileFormatsHuman }}
                                                @else
                                                    Umum (PDF, DOC, DOCX, ZIP, RAR, JPG, JPEG, PNG)
                                                @endif
                                            </strong> (Maks. 10 MB)
                                        </div>
                                        @error('file')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                    </div>
                                @endif

                                @if($isLinkAllowed)
                                    <div>
                                        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                            <i class="bi bi-link-45deg me-1 text-indigo"></i>Link Tugas (URL)
                                            @if($onlyLinkAllowed)<span class="text-danger ms-1">*</span>@endif
                                        </label>
                                        <input
                                            type="url"
                                            name="submission_link"
                                            class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('submission_link') is-invalid @enderror"
                                            placeholder="https://drive.google.com/... atau https://github.com/..."
                                            @if($onlyLinkAllowed) required @endif
                                            value="{{ old('submission_link') }}">
                                        <div class="form-text text-muted text-xs mt-1">
                                            <i class="bi bi-info-circle me-1"></i>
                                            @if($onlyLinkAllowed)
                                                Wajib: Masukkan link pengumpulan (Google Drive, GitHub, dll.)
                                            @else
                                                Opsional: Masukkan link Google Drive, GitHub, dll.
                                            @endif
                                        </div>
                                        @error('submission_link')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                    </div>
                                @endif

                                <div>
                                    <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                        <i class="bi bi-chat-square-text me-1 text-gray-600"></i>Catatan Teks (Opsional)
                                    </label>
                                    <textarea
                                        name="submission_text"
                                        class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('submission_text') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Tambahkan catatan penjelasan untuk Pembimbing (opsional)...">{{ old('submission_text') }}</textarea>
                                    @error('submission_text')<div class="invalid-feedback text-xs">{{ $message }}</div>@enderror
                                </div>

                                @if(!$isFileAllowed && !$isLinkAllowed)
                                    <div class="alert alert-warning text-xs mb-2 rounded-2 py-2 px-3">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Tidak ada format pengumpulan yang ditentukan untuk tugas ini. Silakan hubungi Pembimbing.
                                    </div>
                                @endif

                                {{-- TOMBOL KIRIM TUGAS HIJAU GELAP SESUAI GAMBAR --}}
                                <button type="submit" class="btn btn-success w-100 fw-bold py-1.5 px-3.5 mt-2 rounded-2 shadow-sm text-xs text-white" style="background-color: #059669; --bs-btn-bg: #059669; --bs-btn-hover-bg: #047857; --bs-btn-border-color: #059669; --bs-btn-hover-border-color: #047857;">
                                    <i class="bi bi-send-fill me-1.5"></i>Kirim Tugas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm border rounded-2 mb-3">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-lg bg-info bg-opacity-10 text-info mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px;">
                            <i class="bi bi-info-circle-fill fs-4"></i>
                        </div>
                        <h6 class="fw-bold mb-1 text-xs">Materi Informasi</h6>
                        <p class="text-muted text-xs mb-0">
                            Materi ini bersifat informasi saja dan <strong>tidak perlu dikumpulkan</strong>.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if(!empty($material->youtube_url) && !empty($videoId))
<script>
(function () {
    var player = null;
    var maxTimeWatched = 0;
    var isDone = false;
    var initialDone = @json($isVideoWatched);
    var intervalHandle = null;
    var csrfToken = @json(csrf_token());
    var completeEndpoint = @json(route('participant.materials.complete-video', ['material' => $material->id]));
    var ytId = @json($ytId ?? $videoId);

    var wrapper = document.getElementById('video-container-card');
    var badge = document.getElementById('video-status-badge');
    var statusText = document.getElementById('videoStatusText');
    var statusIcon = badge ? badge.querySelector('i.bi') : null;

    function applyDoneUi() {
        isDone = true;
        if (wrapper) {
            wrapper.style.setProperty('border-color', '#10b981', 'important');
            wrapper.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.12), 0 1px 2px rgba(0,0,0,0.08)';
            try { wrapper.className = 'ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm bg-white border-2 border-emerald-500 p-4'; } catch (e) {}
        }
        if (badge) {
            badge.style.setProperty('background-color', '#d1fae5', 'important');
            badge.style.setProperty('color', '#065f46', 'important');
            badge.style.setProperty('border-color', '#6ee7b7', 'important');
            try { badge.className = 'd-inline-flex align-items-center px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold uppercase rounded-full px-3 py-1.5 small fw-bold rounded-pill border'; } catch (e) {}
        }
        if (statusText) {
            statusText.textContent = 'Tuntas Ditonton';
        }
        if (statusIcon) {
            statusIcon.classList.remove('bi-hourglass-split', 'bi-skip-forward-fill');
            statusIcon.classList.add('bi-check-circle-fill');
        }
    }

    function reportBackend() {
        if (isDone) return;
        try {
            fetch(completeEndpoint, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ completed: true })
            })
            .then(function () { applyDoneUi(); })
            .catch(function () { applyDoneUi(); });
        } catch (e) {
            applyDoneUi();
        }
    }

    if (initialDone) {
        applyDoneUi();
    }

    var tag = document.createElement('script');
    tag.src = 'https://www.youtube.com/iframe_api';
    var firstScriptTag = document.getElementsByTagName('script')[0];
    if (firstScriptTag && firstScriptTag.parentNode) {
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    } else {
        document.head.appendChild(tag);
    }

    window.onYouTubeIframeAPIReady = function () {
        player = new YT.Player('embedded-yt-player', {
            height: '100%',
            width: '100%',
            videoId: ytId,
            playerVars: {
                'playsinline': 1,
                'rel': 0,
                'modestbranding': 1,
                'origin': window.location.origin
            },
            events: {
                'onStateChange': onPlayerStateChange,
                'onReady': function () {
                    try {
                        if (!initialDone && !isDone && typeof player.seekTo === 'function') {
                            player.seekTo(0, true);
                        }
                    } catch (e) {}
                }
            }
        });
    };

    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            if (intervalHandle === null) {
                intervalHandle = setInterval(checkProgress, 1000);
            }
        } else if (event.data === YT.PlayerState.ENDED) {
            if (maxTimeWatched > 0 && typeof player.getDuration === 'function') {
                var d = player.getDuration();
                if (d > 0 && maxTimeWatched >= d * 0.98 && !isDone) {
                    reportBackend();
                }
            }
        }
    }

    function checkProgress() {
        if (!player || isDone || typeof player.getCurrentTime !== 'function' || typeof player.getDuration !== 'function') {
            return;
        }

        var currentTime = player.getCurrentTime();
        var duration = player.getDuration();
        if (!duration || duration <= 0) return;

        if (currentTime > maxTimeWatched + 1.5) {
            try {
                player.seekTo(maxTimeWatched, true);
            } catch (e) {}
        } else {
            if (currentTime > maxTimeWatched) {
                maxTimeWatched = currentTime;
            }
        }

        if (maxTimeWatched > 0 && maxTimeWatched >= duration * 0.98) {
            if (intervalHandle !== null) {
                clearInterval(intervalHandle);
                intervalHandle = null;
            }
            reportBackend();
        }
    }
})();
</script>
@endif
@endsection
