@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Materi Pembelajaran</h1>
        <a href="{{ route('participant.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3">
        @forelse($materials as $material)
            @php
                $submissionExists = \App\Models\ModuleSubmission::where('material_id', $material->id)
                    ->where('user_id', auth()->id())
                    ->exists();
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <h5 class="card-title mb-0 fw-bold">{{ $material->title }}</h5>
                            @if($material->is_task)
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-2.5 py-1 small fw-bold flex-shrink-0">
                                    <i class="bi bi-clipboard-check me-1"></i> Tugas
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle rounded-pill px-2.5 py-1 small fw-bold flex-shrink-0">
                                    <i class="bi bi-book me-1"></i> Materi
                                </span>
                            @endif
                        </div>

                        <p class="card-text text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ \Illuminate\Support\Str::limit(strip_tags($material->description), 120) }}
                        </p>

                        @if($material->is_task && $material->deadline)
                            <div class="mb-3">
                                <small class="text-danger d-flex align-items-center">
                                    <i class="bi bi-clock-fill me-1"></i>
                                    Deadline: {{ $material->deadline->translatedFormat('d M Y H:i') }}
                                </small>
                            </div>
                        @endif

                        <div class="d-flex gap-2 mt-auto">
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="btn btn-primary w-100">
                                @if($material->is_task)
                                    @if($submissionExists)
                                        <i class="bi bi-eye me-1"></i> Lihat Status
                                    @else
                                        <i class="bi bi-pencil-square me-1"></i> Kerjakan Tugas
                                    @endif
                                @else
                                    <i class="bi bi-book me-1"></i> Buka Materi
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <div class="avatar-xl bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                            <i class="bi bi-journal-text fs-1"></i>
                        </div>
                        <h4 class="fw-bold mb-2">Belum Ada Materi</h4>
                        <p class="text-muted mb-0 mx-auto" style="max-width: 480px;">
                            Saat ini belum ada materi pembelajaran yang diterbitkan oleh Pembimbing di divisi Anda. Silakan cek kembali nanti.
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if($materials->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $materials->links() }}
        </div>
    @endif
</div>
@endsection
