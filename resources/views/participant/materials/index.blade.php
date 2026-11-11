@extends('layouts.app')
@section('title', 'Materi & Penugasan')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2" style="margin-bottom: 0.5rem !important;">
        <div>
            <h1 class="h4 mb-0 font-bold text-gray-800 dark:text-gray-100">Materi &amp; Penugasan</h1>
        </div>
        <a href="{{ route('participant.dashboard') }}" class="btn btn-secondary btn-sm py-1.5 px-3 text-xs font-semibold">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger text-xs py-2 px-3 rounded-2 mb-2">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success border-2 border-success rounded-3 shadow-sm mb-2 d-flex align-items-start py-2 px-3 text-xs" role="alert">
            <i class="bi bi-check-circle-fill fs-5 text-success me-2 flex-shrink-0 mt-0.5"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-0.5 text-xs">Berhasil!</h6>
                <p class="mb-0 text-xs">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Segmented Switcher Panel (Semua, Tugas Divisi, Tugas Mandiri Saya) -->
    <div class="p-1 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 inline-flex items-center gap-1.5 mb-2 flex-wrap shadow-inner" style="background-color: #f1f5f9; border: 1px solid #e2e8f0; margin-bottom: 0.625rem !important;">
        <!-- Tombol 1: Semua Penugasan -->
        <a href="{{ route('participant.materials.index', ['type' => 'all']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2 text-decoration-none transition-all cursor-pointer shadow-xs"
           @if(($filterType ?? 'all') === 'all')
               style="background-color: #2563eb !important; color: #ffffff !important; box-shadow: 0 1px 3px rgba(37,99,235,0.3) !important;"
           @else
               style="background-color: transparent; color: #475569;"
               onmouseover="this.style.backgroundColor='#ffffff'; this.style.color='#0f172a';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#475569';"
           @endif>
            <i class="bi bi-collection-fill"></i>
            <span>Semua Penugasan</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                  @if(($filterType ?? 'all') === 'all')
                      style="background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;"
                  @else
                      style="background-color: #e2e8f0 !important; color: #334155 !important;"
                  @endif>
                {{ $countAll ?? 0 }}
            </span>
        </a>

        <!-- Tombol 2: Tugas Divisi (Bersama) -->
        <a href="{{ route('participant.materials.index', ['type' => 'division']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2 text-decoration-none transition-all cursor-pointer shadow-xs"
           @if(($filterType ?? '') === 'division')
               style="background-color: #2563eb !important; color: #ffffff !important; box-shadow: 0 1px 3px rgba(37,99,235,0.3) !important;"
           @else
               style="background-color: transparent; color: #475569;"
               onmouseover="this.style.backgroundColor='#ffffff'; this.style.color='#0f172a';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#475569';"
           @endif>
            <i class="bi bi-people-fill"></i>
            <span>Tugas Divisi (Bersama)</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                  @if(($filterType ?? '') === 'division')
                      style="background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;"
                  @else
                      style="background-color: #e2e8f0 !important; color: #334155 !important;"
                  @endif>
                {{ $countDivision ?? 0 }}
            </span>
        </a>

        <!-- Tombol 3: Tugas Mandiri Saya -->
        <a href="{{ route('participant.materials.index', ['type' => 'personal']) }}"
           class="px-3.5 py-1.5 rounded-lg text-xs font-bold flex items-center gap-2 text-decoration-none transition-all cursor-pointer shadow-xs"
           @if(($filterType ?? '') === 'personal')
               style="background-color: #4f46e5 !important; color: #ffffff !important; box-shadow: 0 1px 3px rgba(79,70,229,0.3) !important;"
           @else
               style="background-color: transparent; color: #475569;"
               onmouseover="this.style.backgroundColor='#ffffff'; this.style.color='#0f172a';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#475569';"
           @endif>
            <i class="bi bi-person-check-fill"></i>
            <span>Tugas Mandiri Saya</span>
            <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold"
                  @if(($filterType ?? '') === 'personal')
                      style="background-color: rgba(255,255,255,0.25) !important; color: #ffffff !important;"
                  @else
                      style="background-color: #e2e8f0 !important; color: #334155 !important;"
                  @endif>
                {{ $countPersonal ?? 0 }}
            </span>
        </a>
    </div>

    <!-- START OF CLEAN LIST CONTAINER (Layer 2 EXACT SPEC) -->
    <div class="d-flex flex-column w-100" style="gap: 0.75rem;">
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

                $hasDeadline = !empty($material->deadline);
                $deadlineCarbon = $hasDeadline ? \Illuminate\Support\Carbon::parse($material->deadline) : null;
                $createdCarbon = !empty($material->created_at) ? \Illuminate\Support\Carbon::parse($material->created_at) : null;
                $isOverdue = $deadlineCarbon && $deadlineCarbon->isPast() && !$isCompleted;
                $isPersonal = ($material->target_type ?? 'all') === 'custom';
            @endphp

            {{-- SINGLE TASK CARD --}}
            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-lg p-3.5 sm:p-4 mb-0 shadow-sm hover:border-gray-300 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <!-- Left Info Section -->
                <div class="flex flex-col gap-1 min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($isPersonal)
                            <span style="background-color: #ede9fe !important; color: #5b21b6 !important; padding: 0.15rem 0.55rem !important; border-radius: 0.25rem !important; font-size: 0.65rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #ddd6fe !important;">
                                <i class="bi bi-person-fill me-0.5"></i> Tugas Mandiri Khusus Anda
                            </span>
                        @else
                            <span style="background-color: #f1f5f9 !important; color: #475569 !important; padding: 0.15rem 0.55rem !important; border-radius: 0.25rem !important; font-size: 0.65rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; text-transform: uppercase !important; border: 1px solid #cbd5e1 !important;">
                                <i class="bi bi-people-fill me-0.5"></i> Tugas Divisi
                            </span>
                        @endif

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

                        @php $mFilesCount = count($material->all_files); @endphp
                        @if($mFilesCount > 0)
                            <span style="background-color: #f1f5f9 !important; color: #334155 !important; padding: 0.125rem 0.5rem !important; border-radius: 0.25rem !important; font-size: 0.625rem !important; font-weight: 700 !important; letter-spacing: 0.05em !important; border: 1px solid #cbd5e1 !important;" class="inline-flex items-center">
                                <i class="bi bi-paperclip me-0.5"></i> {{ $mFilesCount }} Berkas
                            </span>
                        @endif
                    </div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-slate-100 truncate mb-0 mt-1">{{ $material->title }}</h3>
                    <p class="text-xs text-gray-500 flex items-center mb-0.5 mt-0.5">
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
                        <p class="text-xs flex items-center mb-0 {{ $dlColorClass }}">
                            <svg class="w-3.5 h-3.5 mr-1 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Deadline: {{ $deadlineCarbon->translatedFormat('d M Y, H:i') }} WIB{{ $dlExtraText }}
                        </p>
                    @endif
                </div>

                <!-- Right Action Button (Status-Aware) -->
                <div class="shrink-0 flex items-center">
                    @if($isTaskFlag)
                        @if($userSubmission || $isCompleted)
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition text-decoration-none" style="background-color: #4f46e5 !important;">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Sudah Dikumpulkan
                            </a>
                        @elseif($isOverdue)
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition text-decoration-none" style="background-color: #dc2626 !important;">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Terlambat — Kumpulkan
                            </a>
                        @else
                            <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition text-decoration-none" style="background-color: #059669 !important;">
                                Kumpulkan Tugas
                                <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        @endif
                    @else
                        <a href="{{ route('participant.materials.show', $material->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 text-white text-xs font-bold rounded-lg shadow-sm transition text-decoration-none" style="background-color: #0369a1 !important;">
                            Lihat Materi
                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl p-8 text-center shadow-xs">
                @if(($filterType ?? '') === 'personal')
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center text-2xl" style="background-color: #ede9fe; color: #6366f1; border: 1px solid #ddd6fe;">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">Belum Ada Tugas Mandiri Khusus untuk Anda</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3.5 max-w-md mx-auto">
                        Pembimbing belum memberikan tugas mandiri perorangan untuk akun Anda. Tugas mandiri diberikan spesifik berdasarkan bidang spesialisasi atau proyek khusus.
                    </p>
                    <a href="{{ route('participant.materials.index', ['type' => 'division']) }}" class="btn btn-primary btn-sm py-1.5 px-3.5 text-xs font-bold rounded-lg shadow-sm d-inline-flex align-items-center" style="background-color: #2563eb !important;">
                        <i class="bi bi-people-fill me-1.5"></i> Lihat Tugas Divisi (Bersama)
                    </a>
                @elseif(($filterType ?? '') === 'division')
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center text-2xl" style="background-color: #dbeafe; color: #2563eb; border: 1px solid #bfdbfe;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">Belum Ada Tugas Divisi</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-0 max-w-md mx-auto">
                        Belum ada materi atau tugas divisi bersama yang diterbitkan pembimbing saat ini.
                    </p>
                @else
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full flex items-center justify-center text-2xl" style="background-color: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1;">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-1">Belum Ada Materi &amp; Penugasan</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-0 max-w-md mx-auto">
                        Belum ada materi atau penugasan yang diterbitkan pembimbing untuk periode magang Anda saat ini.
                    </p>
                @endif
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
