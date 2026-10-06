@extends('layouts.app')
@section('title', 'Materi Pembelajaran')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 mb-0 text-gray-800">Materi & Penugasan</h1>
        <a href="{{ route('participant.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success border-2 border-success rounded-3 shadow-sm mb-4 d-flex align-items-start" role="alert">
            <i class="bi bi-check-circle-fill fs-4 text-success me-2 flex-shrink-0 mt-0.5"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1">Berhasil!</h6>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- START OF CLEAN LIST CONTAINER (Layer 2 EXACT SPEC) -->
    <div class="d-flex flex-column w-100" style="gap: 1rem;">
        @forelse($materials as $material)
            @php
                $rawId = $material->raw_id ?? $material->id;
                $srcType = $material->source_type ?? 'material';
                $isTaskFlag = (bool) ($material->is_task ?? false);

                if ($material->submissions instanceof \Illuminate\Support\Collection && $material->submissions->isNotEmpty()) {
                    $userSubmission = $material->submissions->first();
                } else {
                    $userSubmission = null;
                }

                if ($userSubmission !== null) {
                    $isCompleted = true;
                } elseif ($isTaskFlag || $srcType === 'task' || !empty($material->deadline)) {
                    $isCompleted = \App\Models\ModuleSubmission::where('material_id', $rawId)
                        ->where('user_id', auth()->id())
                        ->exists();
                } else {
                    $isCompleted = false;
                }

                if (!empty($material->deadline) || $isTaskFlag || $srcType === 'task') {
                    $jenisLabel = 'TUGAS';
                    $jenisBg = '#fef3c7';
                    $jenisColor = '#92400e';
                } else {
                    $jenisLabel = 'MATERI';
                    $jenisBg = '#e0f2fe';
                    $jenisColor = '#075985';
                }

                $hasDeadline = !empty($material->deadline);
                $deadlineCarbon = $hasDeadline ? \Illuminate\Support\Carbon::parse($material->deadline) : null;
                $createdCarbon = !empty($material->created_at) ? \Illuminate\Support\Carbon::parse($material->created_at) : null;
                $isOverdue = $deadlineCarbon && $deadlineCarbon->isPast() && !$isCompleted;
            @endphp

            {{-- ======================================================================================
                 SINGLE TASK CARD
                 flex flex-col md:flex-row md:items-center justify-between gap-3 — NO OVERLAP
                 ====================================================================================== --}}
            <div class="bg-white border border-gray-200 rounded-lg p-3.5 sm:p-4 mb-3 shadow-sm hover:border-gray-300 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <!-- Left Info Section -->
                <div class="flex flex-col gap-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($material->is_task)
                            <span style="background-color: #fef3c7 !important; color: #92400e !important; padding: 0.125rem 0.5rem !important; border-radius: 0.25rem !important; font-size: 0.625rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #fde68a !important;">
                                <i class="bi bi-clipboard-check me-0.5"></i> Tugas
                            </span>
                        @endif
                        @if($userSubmission || $isCompleted)
                            <span style="background-color: #d1fae5 !important; color: #065f46 !important; padding: 0.125rem 0.5rem !important; border-radius: 0.25rem !important; font-size: 0.625rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #6ee7b7 !important;">
                                <i class="bi bi-check-circle-fill me-0.5"></i> Sudah Dikumpulkan
                            </span>
                        @elseif($isTaskFlag && $isOverdue)
                            <span style="background-color: #fee2e2 !important; color: #991b1b !important; padding: 0.125rem 0.5rem !important; border-radius: 0.25rem !important; font-size: 0.625rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #fecaca !important;">
                                <i class="bi bi-exclamation-triangle-fill me-0.5"></i> Terlambat
                            </span>
                        @elseif($isTaskFlag && !$isCompleted)
                            <span style="background-color: #dbeafe !important; color: #1e40af !important; padding: 0.125rem 0.5rem !important; border-radius: 0.25rem !important; font-size: 0.625rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #bfdbfe !important;">
                                <i class="bi bi-hourglass-split me-0.5"></i> Belum Dikumpulkan
                            </span>
                        @endif
                        @if(!$material->is_task && !($userSubmission || $isCompleted))
                            <span style="background-color: #e0f2fe !important; color: #075985 !important; padding: 0.125rem 0.5rem !important; border-radius: 0.25rem !important; font-size: 0.625rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #bae6fd !important;">
                                <i class="bi bi-journal-text me-0.5"></i> Materi
                            </span>
                        @endif
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 truncate">{{ $material->title }}</h3>
                    <p class="text-xs text-gray-500 flex items-center mb-1">
                        <svg class="w-3.5 h-3.5 mr-1 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Diberikan: {{ $material->created_at->format('d M Y, H:i') }} WIB
                    </p>
                    @if($hasDeadline && $isTaskFlag)
                        @php
                            $dlColorClass = 'text-gray-500';
                            $dlExtraText = '';
                            if (!$isCompleted && $deadlineCarbon->isPast()) {
                                $dlColorClass = 'text-red-500 font-bold';
                                $dlExtraText = ' (Terlewat)';
                            } elseif (!$isCompleted && $deadlineCarbon->diffInHours(now()) < 24) {
                                $dlColorClass = 'text-orange-500 font-bold';
                            }
                        @endphp
                        <p class="text-xs flex items-center {{ $dlColorClass }}">
                            <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Deadline: {{ $deadlineCarbon->translatedFormat('d M Y, H:i') }} WIB{{ $dlExtraText }}
                        </p>
                    @endif
                </div>

                <!-- Right Action Button (Status-Aware) -->
                <div class="shrink-0 flex items-center">
                    @if($isTaskFlag)
                        @if($userSubmission || $isCompleted)
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition" style="background-color: #4f46e5 !important;">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Sudah Dikumpulkan
                            </a>
                        @elseif($isOverdue)
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition" style="background-color: #dc2626 !important;">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Terlambat — Kumpulkan
                            </a>
                        @else
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition" style="background-color: #059669 !important;">
                                Kumpulkan Tugas
                                <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition" style="background-color: #0369a1 !important;">
                            Lihat Materi
                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="w-100 bg-white border border-gray-200 rounded-4 p-8 text-center text-gray-500">
                Belum ada penugasan baru untuk Anda.
            </div>
        @endforelse
    </div>
    <!-- END OF CLEAN LIST CONTAINER -->

    @if($materials->hasPages())
        <div class="mt-4 d-flex justify-content-center">
            {{ $materials->links() }}
        </div>
    @endif
</div>
@endsection
