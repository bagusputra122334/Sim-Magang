@extends('layouts.app')
@section('title', $material->title)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 mb-0 text-gray-800">{{ $material->title }}</h1>
        <a href="{{ route('participant.materials.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Materi
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
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-transparent border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-journal-text me-1"></i> Konten Materi
                    </h6>
                    <span class="badge rounded-pill px-3 py-1.5 small fw-bold {{ $material->is_task ? 'bg-warning-subtle text-warning-emphasis border border-warning-subtle' : 'bg-info-subtle text-info-emphasis border border-info-subtle' }}">
                        <i class="bi {{ $material->is_task ? 'bi-clipboard-check' : 'bi-book' }} me-1"></i>
                        {{ $material->is_task ? 'Materi + Tugas' : 'Materi Saja' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-uppercase text-muted small mb-2">Deskripsi</h6>
                        <div class="p-3 bg-light rounded-3 border">
                            {!! nl2br(e($material->description)) !!}
                        </div>
                    </div>

                    @if($material->youtube_url)
                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase text-muted small mb-2">
                                <i class="bi bi-youtube text-danger me-1"></i> Video Pembelajaran
                            </h6>
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden border shadow-sm">
                                @if($videoId)
                                    <iframe
                                        src="https://www.youtube.com/embed/{{ $videoId }}"
                                        title="YouTube video player"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen>
                                    </iframe>
                                @else
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-decoration-none text-muted bg-light">
                                        <i class="bi bi-play-circle-fill fs-1 text-primary mb-2"></i>
                                        <span class="fw-semibold">Buka Video di YouTube</span>
                                        <span class="small">{{ $material->youtube_url }}</span>
                                    </a>
                                @endif
                            </div>
                            @if(!$videoId)
                                <div class="mt-2 text-center">
                                    <a href="{{ $material->youtube_url }}" target="_blank" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-youtube me-1"></i> Buka Link YouTube
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            @if($material->is_task)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-transparent border-bottom py-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <h6 class="m-0 fw-bold text-warning">
                            <i class="bi bi-clipboard-check me-1"></i> Instruksi Penugasan
                        </h6>
                        @if($material->deadline)
                            @php
                                $isOverdue = $material->deadline->isPast() && !$submission;
                            @endphp
                            <span class="badge rounded-pill px-3 py-1.5 small fw-bold {{ $isOverdue ? 'bg-danger-subtle text-danger-emphasis border border-danger-subtle' : 'bg-primary-subtle text-primary-emphasis border border-primary-subtle' }}">
                                <i class="bi bi-clock-fill me-1"></i>
                                Deadline: {{ $material->deadline->translatedFormat('l, d M Y H:i') }}
                                @if($isOverdue)
                                    <span class="ms-1">(Terlambat)</span>
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        @if(!empty(trim($material->task_instruction ?? '')))
                            <div class="p-3 bg-warning bg-opacity-5 border border-warning-subtle rounded-3 mb-0">
                                {!! nl2br(e($material->task_instruction)) !!}
                            </div>
                        @else
                            <p class="text-muted mb-0 fst-italic small">
                                <i class="bi bi-info-circle me-1"></i> Tidak ada instruksi penugasan tambahan. Silakan kerjakan berdasarkan deskripsi materi di atas.
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            @if($material->is_task)
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 1rem;">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h6 class="m-0 fw-bold text-success">
                            <i class="bi bi-send-fill me-1"></i> Pengumpulan Tugas
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        @if($submission)
                            <div class="alert alert-success border-2 border-success rounded-3 mb-3 d-flex align-items-start" role="alert">
                                <i class="bi bi-check2-circle fs-4 text-success me-2 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <h6 class="alert-heading fw-bold mb-1">Sudah Dikumpulkan!</h6>
                                    <p class="mb-1 small">
                                        <strong>Status:</strong>
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-2 py-0.5">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-0 small">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        Dikumpulkan: {{ $submission->updated_at->translatedFormat('d M Y H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold small text-muted text-uppercase">File Pengumpulan</label>
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between gap-2">
                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                        <i class="bi bi-file-earmark-arrow-up fs-3 text-primary flex-shrink-0"></i>
                                        <div class="min-w-0">
                                            <div class="fw-semibold small text-truncate">
                                                {{ basename($submission->file_path) }}
                                            </div>
                                            <small class="text-muted">{{ \Illuminate\Support\Str::of($submission->file_path)->after('submissions/')->limit(30) }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm flex-shrink-0">
                                        <i class="bi bi-download me-1"></i> Unduh
                                    </a>
                                </div>
                            </div>

                            @if(!empty(trim($submission->submission_text ?? '')))
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small text-muted text-uppercase">Catatan Pengumpulan</label>
                                    <div class="p-3 border rounded-3 bg-light small">
                                        {!! nl2br(e($submission->submission_text)) !!}
                                    </div>
                                </div>
                            @endif

                            @if(!empty(trim($submission->grade ?? '')) || !empty(trim($submission->grade_note ?? '')) || !empty(trim($submission->review_note ?? '')))
                                <div class="alert alert-primary border-2 border-primary rounded-3 mb-0" role="alert">
                                    <h6 class="alert-heading fw-bold mb-2">
                                        <i class="bi bi-clipboard2-check-fill me-1"></i> Hasil Review Pembimbing
                                    </h6>
                                    @if(!empty(trim($submission->grade ?? '')))
                                        <div class="mb-2">
                                            <span class="fw-semibold">Nilai:</span>
                                            <span class="badge bg-primary-subtle text-primary-emphasis border border-primary-subtle rounded-pill px-3 py-1 ms-1">
                                                {{ $submission->grade }}
                                            </span>
                                        </div>
                                    @endif
                                    @if(!empty(trim($submission->grade_note ?? '')))
                                        <div class="mb-0">
                                            <div class="fw-semibold small mb-1">Catatan Review:</div>
                                            <div class="small bg-white p-2 rounded border">
                                                {!! nl2br(e($submission->grade_note)) !!}
                                            </div>
                                        </div>
                                    @endif
                                    @if(!empty(trim($submission->review_note ?? '')) && empty(trim($submission->grade_note ?? '')))
                                        <div class="mb-0">
                                            <div class="fw-semibold small mb-1">Catatan Review:</div>
                                            <div class="small bg-white p-2 rounded border">
                                                {!! nl2br(e($submission->review_note)) !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @else
                            <form action="{{ route('participant.materials.submit', $material->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Upload File Tugas
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="file"
                                        name="file"
                                        class="form-control @error('file') is-invalid @enderror"
                                        accept=".pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png"
                                        required>
                                    <div class="form-text text-muted small mt-1">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Format yang diizinkan: <strong>PDF, DOC, DOCX, ZIP, RAR, JPG, JPEG, PNG</strong> (Maks. 10 MB)
                                    </div>
                                    @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="bi bi-chat-square-text me-1"></i> Catatan Teks (Opsional)
                                    </label>
                                    <textarea
                                        name="submission_text"
                                        class="form-control @error('submission_text') is-invalid @enderror"
                                        rows="3"
                                        placeholder="Tambahkan catatan penjelasan untuk Pembimbing (opsional)...">{{ old('submission_text') }}</textarea>
                                    @error('submission_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <button type="submit" class="btn btn-success w-100 fw-bold py-2.5 shadow-sm hover:shadow-md transition-all duration-200">
                                    <i class="bi bi-send-fill me-1"></i> Kirim Tugas
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="avatar-xl bg-info bg-opacity-10 text-info mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                            <i class="bi bi-info-circle-fill fs-1"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Materi Informasi / Non-Tugas</h6>
                        <p class="text-muted small mb-0">
                            Materi ini bersifat informasi saja dan <strong>tidak memerlukan pengumpulan tugas</strong>. Anda dapat mempelajari konten materi dengan tenang.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
