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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-8">
    <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-2">
        <h1 class="h2 fw-bold mb-0 text-gray-900" style="letter-spacing: -0.02em;">{{ $material->title }}</h1>
        <a href="{{ route('participant.materials.index') }}" style="background-color: #1f2937 !important; color: #ffffff !important; border-radius: 0.5rem; padding: 0.5rem 1rem; display: inline-flex; align-items: center; text-decoration: none; font-weight: 600; font-size: 0.875rem; border: 1px solid #111827; margin-bottom: 1.5rem;" class="shadow-sm hover:opacity-90">
            <svg style="width: 1rem; height: 1rem; margin-right: 0.5rem; color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Materi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-2 border-success rounded-3 shadow-sm mb-4 d-flex align-items-start" role="alert">
            <i class="bi bi-check-circle-fill fs-4 text-success me-2 flex-shrink-0 mt-0.5"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Berhasil!</h6>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-2 border-danger rounded-3 shadow-sm mb-4 d-flex align-items-start" role="alert">
            <i class="bi bi-x-circle-fill fs-4 text-danger me-2 flex-shrink-0 mt-0.5"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Error</h6>
                <p class="mb-0">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="row g-4">
        {{-- ========================================= --}}
        {{-- KIRI: KONTEN MATERI + INSTRUKSI (col-lg-8
        {{-- ========================================= --}}
        <div class="col-lg-8">

            {{-- =============== KARTU 1: KONTEN MATERI =============== --}}
            <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-bottom py-4 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h6 class="m-0 fw-bold text-primary fs-5">
                            <i class="bi bi-journal-text me-2"></i>Konten Materi
                        </h6>
                        @if($material->is_task)
                            <span style="background-color: #fef3c7 !important; color: #92400e !important; padding: 0.375rem 0.75rem !important; border-radius: 9999px !important; font-size: 0.8125rem !important; font-weight: 700 !important; border: 1px solid #fde68a !important;" class="d-inline-flex align-items-center">
                                <i class="bi bi-clipboard-check me-1"></i> Tugas
                            </span>
                        @endif
                        @if($sourceType === 'task')
                            <span style="background-color: #ede9fe !important; color: #5b21b6 !important; padding: 0.375rem 0.75rem !important; border-radius: 9999px !important; font-size: 0.8125rem !important; font-weight: 700 !important; border: 1px solid #ddd6fe !important;" class="d-inline-flex align-items-center">
                                <i class="bi bi-list-task me-1"></i> Penugasan
                            </span>
                        @endif
                        @if($material->is_task && $submission)
                            <span style="background-color: #d1fae5 !important; color: #065f46 !important; padding: 0.375rem 0.75rem !important; border-radius: 9999px !important; font-size: 0.8125rem !important; font-weight: 700 !important; border: 1px solid #6ee7b7 !important;" class="d-inline-flex align-items-center">
                                <i class="bi bi-check-circle-fill me-1"></i> Sudah Dikumpulkan
                            </span>
                        @elseif($material->is_task && !$submission)
                            @php
                                $_deadlineCarbon = !empty($material->deadline) ? \Illuminate\Support\Carbon::parse($material->deadline) : null;
                                $_isOverdue = $_deadlineCarbon && $_deadlineCarbon->isPast();
                            @endphp
                            @if($_isOverdue)
                                <span style="background-color: #fee2e2 !important; color: #991b1b !important; padding: 0.375rem 0.75rem !important; border-radius: 9999px !important; font-size: 0.8125rem !important; font-weight: 700 !important; border: 1px solid #fecaca !important;" class="d-inline-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Terlambat
                                </span>
                            @else
                                <span style="background-color: #dbeafe !important; color: #1e40af !important; padding: 0.375rem 0.75rem !important; border-radius: 9999px !important; font-size: 0.8125rem !important; font-weight: 700 !important; border: 1px solid #bfdbfe !important;" class="d-inline-flex align-items-center">
                                    <i class="bi bi-hourglass-split me-1"></i> Belum Dikumpulkan
                                </span>
                            @endif
                        @endif
                    </div>
                    <div style="min-width: 180px;"></div>
                </div>
                <div class="card-body p-5">
                    <h6 class="fw-bold text-uppercase text-muted small mb-3" style="letter-spacing: 0.08em;">Deskripsi</h6>
                    <div class="p-4 bg-gray-50 rounded-4 border border-gray-200" style="background-color: #f8f9fa;">
                        <div class="text-gray-800 lh-lg">
                            {!! nl2br(e($material->description)) !!}
                        </div>
                    </div>

                    @if(!empty($material->file_materi))
                        <div class="mt-5">
                            <h6 class="fw-bold text-uppercase text-muted small mb-3" style="letter-spacing: 0.08em;">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>Dokumen Materi
                            </h6>
                            <div class="p-4 border rounded-4 bg-light d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-2 min-w-0">
                                    <i class="bi bi-file-earmark-text fs-1 text-primary flex-shrink-0"></i>
                                    <div class="min-w-0">
                                        <div class="fw-semibold text-truncate">
                                            {{ basename($material->file_materi) }}
                                        </div>
                                        <small class="text-muted">
                                            Lampiran dokumen dari Pembimbing
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($material->file_materi) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill flex-shrink-0 px-4 py-2 fw-semibold shadow-sm">
                                    <i class="bi bi-download me-1"></i>Unduh Dokumen
                                </a>
                            </div>
                        </div>
                    @endif

                    @if(!empty($material->youtube_url))
                        <div class="mt-5" id="materialVideoSection">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                                <h6 class="m-0 fw-bold text-uppercase text-muted small" style="letter-spacing: 0.08em;">
                                    <i class="bi bi-youtube text-danger me-2"></i>Video Pembelajaran
                                </h6>
                                <span id="video-status-badge"
                                      class="d-inline-flex align-items-center px-3 py-1.5 small fw-bold rounded-pill border"
                                      style="
                                        @if($isVideoWatched)
                                          background-color: #d1fae5 !important; color: #065f46 !important; border-color: #6ee7b7 !important;
                                        @else
                                          background-color: #fee2e2 !important; color: #991b1b !important; border-color: #fecaca !important;
                                        @endif
                                      ">
                                    <i class="bi @if($isVideoWatched) bi-check-circle-fill @else bi-hourglass-split @endif me-1.5"></i>
                                    <span id="videoStatusText">@if($isVideoWatched) Tuntas Ditonton @else Belum Selesai (Tonton tanpa skip) @endif</span>
                                </span>
                            </div>
                            <div id="video-container-card"
                                 class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm"
                                 style="
                                   border-width: 3px !important;
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
                                        <i class="bi bi-play-circle-fill display-4 text-primary mb-3"></i>
                                        <span class="fw-semibold">Buka Video di YouTube</span>
                                        <span class="small">{{ $material->youtube_url }}</span>
                                    </a>
                                @endif
                            </div>
                            @if(!$videoId)
                                <div class="mt-3 text-center">
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="btn btn-outline-danger btn-sm rounded-pill px-4">
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
                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-4 px-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <h6 class="m-0 fw-bold text-warning fs-5">
                            <i class="bi bi-clipboard-check me-2"></i>Instruksi Penugasan
                        </h6>
                        @if($deadlineCarbon !== null)
                            <span class="badge rounded-pill px-4 py-2 small fw-bold {{ $isOverdue ? 'bg-danger text-white border border-danger' : 'bg-primary-subtle text-primary border border-primary rounded-pill' }}" style="{{ $isOverdue ? '' : 'background-color: #dbeafe;' }}">
                                <i class="bi bi-clock-fill me-1"></i>
                                Deadline: {{ $deadlineCarbon->locale('id_ID')->isoFormat('dddd, D MMM YYYY HH:mm') }}
                                @if($isOverdue)
                                    <span class="ms-1">(Terlambat)</span>
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-5">
                        {{-- BOX KUNING INSTRUKSI -- Background Kuning Solid --}}
                        <div class="p-5 rounded-4 mb-4" style="background-color: #fbbf24; border-radius: 1rem;">
                            <div class="text-gray-900 fs-6 lh-lg fw-medium" style="color: #1f2937;">
                                @if(!empty(trim($material->task_instruction ?? '')))
                                    {!! nl2br(e($material->task_instruction)) !!}
                                @else
                                    {!! nl2br(e($material->description)) !!}
                                @endif
                            </div>
                        </div>

                        {{-- FORMAT YANG DIIZINKAN --}}
                        @if($material->hasSubmissionType() || $material->is_task)
                            <div class="d-flex align-items-center gap-2 p-3 rounded-4 border border-gray-200 bg-gray-50" style="background-color: #f8f9fa;">
                                <i class="bi bi-paperclip text-gray-500 fs-5 me-1"></i>
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
                <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden sticky-top" style="top: 1rem;">
                    <div class="card-header bg-white border-bottom py-4 px-4">
                        <h6 class="m-0 fw-bold text-success fs-5">
                            <i class="bi bi-send-fill me-2"></i>Pengumpulan Tugas
                        </h6>
                    </div>
                    <div class="card-body p-5">
                        @if($submission)
                            {{-- SUDAH DIKUMPULKAN --}}
                            <div class="alert alert-success border-2 border-success rounded-3 mb-4 d-flex align-items-start" role="alert">
                                <i class="bi bi-check2-circle fs-4 text-success me-2 flex-shrink-0 mt-0.5"></i>
                                <div class="flex-grow-1 min-w-0">
                                    <h6 class="alert-heading fw-bold mb-1">Sudah Dikumpulkan!</h6>
                                    <p class="mb-1 small">
                                        <strong>Status:</strong>
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success rounded-pill px-2 py-0.5">
                                            {{ ucfirst(is_string($submission->status) ? $submission->status : ($submission->status?->value ?? 'Submitted')) }}
                                        </span>
                                    </p>
                                    @php
                                        $subUpdated = isset($submission->updated_at) ? (\Illuminate\Support\Carbon::parse($submission->updated_at)) : now();
                                    @endphp
                                    <p class="mb-0 small text-truncate" title="Dikumpulkan: {{ $subUpdated->locale('id_ID')->isoFormat('D MMM YYYY HH:mm') }}">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Dikumpulkan: {{ $subUpdated->locale('id_ID')->isoFormat('D MMM YYYY HH:mm') }}
                                    </p>
                                </div>
                            </div>

                            @if($hasSubmissionFile)
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 0.05em;">File Pengumpulan</label>
                                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0" style="min-width: 0;">
                                            <i class="bi bi-file-earmark-arrow-up fs-3 text-primary flex-shrink-0"></i>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-semibold small text-truncate" title="{{ basename($submission->file_path) }}">
                                                    {{ basename($submission->file_path) }}
                                                </div>
                                                <small class="text-muted d-block text-truncate" title="{{ \Illuminate\Support\Str::of($submission->file_path)->after('submissions/') }}">
                                                    {{ \Illuminate\Support\Str::of($submission->file_path)->after('submissions/')->limit(32) }}
                                                </small>
                                            </div>
                                        </div>
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($submission->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill flex-shrink-0">
                                            <i class="bi bi-download me-1"></i> Unduh
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($hasSubmissionLink)
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 0.05em;">Link Pengumpulan</label>
                                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center gap-2 flex-grow-1 min-w-0" style="min-width: 0;">
                                            <i class="bi bi-link-45deg fs-3 text-indigo flex-shrink-0"></i>
                                            <div class="flex-grow-1 min-w-0">
                                                <a href="{{ $submission->submission_link }}" target="_blank"
                                                   class="fw-semibold small text-truncate d-block text-decoration-none text-indigo"
                                                   title="{{ $submission->submission_link }}">
                                                    {{ \Illuminate\Support\Str::limit($submission->submission_link, 48) }}
                                                </a>
                                            </div>
                                        </div>
                                        <a href="{{ $submission->submission_link }}" target="_blank" class="btn btn-outline-indigo btn-sm rounded-pill flex-shrink-0">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> Buka
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(!empty(trim($submission->submission_text ?? '')) || !empty(trim($submission->grade_note ?? '')))
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase mb-2" style="letter-spacing: 0.05em;">Catatan Pengumpulan</label>
                                    <div class="p-3 border rounded-4 bg-light small lh-base">
                                        {!! nl2br(e($submission->submission_text ?? $submission->grade_note ?? '')) !!}
                                    </div>
                                </div>
                            @endif

                            {{-- =================================================================
                                 GANTI FILE / UNGGAH ULANG (RESUBMISSION) TOGGLE
                                 Button trigger -> Collapse reveals edit form
                                 ================================================================= --}}
                            <div class="mb-4">
                                <button class="btn btn-outline-warning btn-sm fw-bold w-100 rounded-3 py-2 d-inline-flex align-items-center justify-content-center gap-2 shadow-sm"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#resubmitFormCollapse"
                                        aria-expanded="false"
                                        aria-controls="resubmitFormCollapse"
                                        onclick="const t = this; setTimeout(()=>{t.scrollIntoView({behavior:'smooth', block:'nearest'});}, 320);">
                                    <i class="bi bi-arrow-repeat"></i>
                                    <span>Ganti File / Unggah Ulang</span>
                                    <i class="bi bi-chevron-double-down small"></i>
                                </button>

                                <div class="collapse mt-4" id="resubmitFormCollapse">
                                    <div class="p-4 border border-warning-subtle bg-warning-subtle rounded-4">
                                        <p class="small fw-semibold text-warning-emphasis mb-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                                            Unggah ulang akan mengganti file / link pengumpulan Anda sebelumnya.
                                        </p>
                                        <form action="{{ route('participant.materials.submit', $material->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            @if($isFileAllowed)
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold mb-2 small">
                                                        <i class="bi bi-file-earmark-arrow-up me-1"></i>File Pengganti
                                                        @if($onlyFileAllowed)<span class="text-danger ms-1">*</span>@endif
                                                    </label>
                                                    <input
                                                        type="file"
                                                        name="file"
                                                        class="form-control @error('file') is-invalid @enderror rounded-3"
                                                        accept="{{ $acceptAttr }}">
                                                    <div class="form-text text-muted small mt-1">
                                                        Format: <strong>
                                                            @if($onlyFileAllowed || $onlyLinkAllowed)
                                                                {{ $fileFormatsHuman }}
                                                            @else
                                                                Umum (PDF, DOC, DOCX, ZIP, RAR, JPG, JPEG, PNG)
                                                            @endif
                                                        </strong> (Maks. 10 MB)
                                                        @if(!$onlyFileAllowed && !$onlyLinkAllowed)
                                                            <br><small class="fst-italic">Bisa mengunggah file ATAU link (pilih salah satu).</small>
                                                        @endif
                                                    </div>
                                                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                            @endif

                                            @if($isLinkAllowed)
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold mb-2 small">
                                                        <i class="bi bi-link-45deg me-1"></i>Link Pengganti
                                                        @if($onlyLinkAllowed)<span class="text-danger ms-1">*</span>@endif
                                                    </label>
                                                    <input
                                                        type="url"
                                                        name="submission_link"
                                                        class="form-control @error('submission_link') is-invalid @enderror rounded-3"
                                                        placeholder="https://drive.google.com/... atau https://github.com/..."
                                                        value="{{ old('submission_link') }}">
                                                    <div class="form-text text-muted small mt-1">
                                                        @if($onlyLinkAllowed)
                                                            Wajib: Masukkan link pengumpulan baru.
                                                        @else
                                                            Opsional: Masukkan link baru pengganti.
                                                        @endif
                                                    </div>
                                                    @error('submission_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label fw-bold mb-2 small">
                                                    <i class="bi bi-chat-square-text me-1"></i>Catatan (Opsional)
                                                </label>
                                                <textarea
                                                    name="submission_text"
                                                    class="form-control @error('submission_text') is-invalid @enderror rounded-3"
                                                    rows="3"
                                                    placeholder="Tambahkan catatan pengantar unggah ulang...">{{ old('submission_text', $submission->submission_text ?? '') }}</textarea>
                                                @error('submission_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>

                                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 rounded-3 shadow-sm d-inline-flex align-items-center justify-content-center gap-2 text-warning-emphasis">
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
                                <div class="alert alert-primary border-2 border-primary rounded-4 mb-0" role="alert">
                                    <h6 class="alert-heading fw-bold mb-3">
                                        <i class="bi bi-clipboard2-pencil-fill me-2"></i>Hasil Review Pembimbing
                                    </h6>
                                    @if($gradeVal !== '')
                                        <div class="mb-3">
                                            <span class="fw-semibold">Nilai:</span>
                                            <span class="badge bg-primary text-white border border-primary rounded-pill px-3 py-1 ms-2">
                                                {{ $gradeVal }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($reviewContent !== '')
                                        <div class="mb-0">
                                            <div class="fw-semibold small mb-2">Catatan Review:</div>
                                            <div class="small bg-white p-3 rounded-4 border">
                                                {!! nl2br(e($reviewContent)) !!}
                                            </div>
                                        </div>
                                    @elseif($reviewAlt !== '')
                                        <div class="mb-0">
                                            <div class="fw-semibold small mb-2">Catatan Review:</div>
                                            <div class="small bg-white p-3 rounded-4 border">
                                                {!! nl2br(e($reviewAlt)) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            {{-- FORM BELUM DIKUMPULKAN --}}
                            <form action="{{ route('participant.materials.submit', $material->id) }}" method="POST" enctype="multipart/form-data" class="space-y-1">
                                @csrf

                                @if($isFileAllowed)
                                    <div class="mb-4">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="bi bi-file-earmark-arrow-up me-1 text-gray me-1"></i>Upload File Tugas
                                        @if($onlyFileAllowed)<span class="text-danger ms-1">*</span>@endif
                                    </label>
                                    <input
                                        type="file"
                                        name="file"
                                        class="form-control form-control-lg @error('file') is-invalid @enderror rounded-3 py-2"
                                        accept="{{ $acceptAttr }}"
                                        @if($onlyFileAllowed) required @endif>
                                    <div class="form-text text-muted small mt-2">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Format: <strong>
                                            @if($onlyFileAllowed || $onlyLinkAllowed)
                                                {{ $fileFormatsHuman }}
                                            @else
                                                Umum (PDF, DOC, DOCX, ZIP, RAR, JPG, JPEG, PNG)
                                            @endif
                                        </strong> (Maks. 10 MB)
                                        @if(!$onlyFileAllowed && !$onlyLinkAllowed)
                                            <br><small class="fst-italic">Catatan: Anda juga dapat mengirim link sebagai pengganti file (pilih salah satu).</small>
                                        @endif
                                    </div>
                                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif

                                @if($isLinkAllowed)
                                    <div class="mb-4">
                                        <label class="form-label fw-bold mb-2">
                                            <i class="bi bi-link-45deg me-1 text-indigo"></i>Link Tugas (URL)
                                            @if($onlyLinkAllowed)<span class="text-danger ms-1">*</span>@endif
                                        </label>
                                        <input
                                            type="url"
                                            name="submission_link"
                                            class="form-control form-control-lg @error('submission_link') is-invalid @enderror rounded-3 py-2"
                                            placeholder="https://drive.google.com/... atau https://github.com/..."
                                            @if($onlyLinkAllowed) required @endif
                                            value="{{ old('submission_link') }}">
                                        <div class="form-text text-muted small mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            @if($onlyLinkAllowed)
                                                Wajib: Masukkan link pengumpulan (Google Drive, GitHub, dll.)
                                            @else
                                                Opsional: Masukkan link Google Drive, GitHub, dll.
                                            @endif
                                        </div>
                                        @error('submission_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <label class="form-label fw-bold mb-2">
                                        <i class="bi bi-chat-square-text me-1 text-gray-600"></i>Catatan Teks (Opsional)
                                    </label>
                                    <textarea
                                        name="submission_text"
                                        class="form-control form-control-lg @error('submission_text') is-invalid @enderror rounded-3"
                                        rows="4"
                                        placeholder="Tambahkan catatan penjelasan untuk Pembimbing (opsional)...">{{ old('submission_text') }}</textarea>
                                    @error('submission_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                @if(!$isFileAllowed && !$isLinkAllowed)
                                    <div class="alert alert-warning small mb-4 rounded-4">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Tidak ada format pengumpulan yang ditentukan untuk tugas ini. Silakan hubungi Pembimbing.
                                    </div>
                                @endif

                                {{-- TOMBOL KIRIM TUGAS HIJAU GELAP SESUAI GAMBAR --}}
                                <button type="submit" class="btn btn-success btn-lg w-100 fw-bold py-3 mt-2 rounded-4 shadow-sm hover-shadow-md transition-all duration-200 text-white" style="background-color: #059669; --bs-btn-bg: #059669; --bs-btn-hover-bg: #047857; --bs-btn-border-color: #059669; --bs-btn-hover-border-color: #047857;">
                                    <i class="bi bi-send-fill me-2"></i>Kirim Tugas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-body p-5 text-center">
                        <div class="avatar-xl bg-info bg-opacity-10 text-info mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle">
                            <i class="bi bi-info-circle-fill fs-1"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Materi Informasi</h6>
                        <p class="text-muted small mb-0">
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
