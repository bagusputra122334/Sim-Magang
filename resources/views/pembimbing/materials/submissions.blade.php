@extends('layouts.app')
@section('title', 'Daftar Pengumpulan — ' . ($material->title ?? 'Tugas'))
@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-5">
    
    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2 shadow-sm border border-gray-200 dark:border-slate-800 p-3 sm:p-4 mb-3">
        <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-base sm:text-lg font-bold text-gray-900 dark:text-slate-100 mb-0">{{ $material->title ?? $material->judul }}</h1>
                    @if($material->target_type === 'custom')
                        <span style="background-color: #ede9fe !important; color: #5b21b6 !important; border: 1px solid #ddd6fe !important; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700;">
                            <i class="bi bi-person-fill me-0.5"></i> Penugasan Mandiri
                        </span>
                    @else
                        <span style="background-color: #dbeafe !important; color: #1e40af !important; border: 1px solid #bfdbfe !important; padding: 0.15rem 0.5rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700;">
                            <i class="bi bi-people-fill me-0.5"></i> Tugas Divisi
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-0.5 mb-0">
                    Daftar Pengumpulan &amp; Status Pengerjaan Peserta
                    @if($material->target_type === 'custom' && $material->assignedUsers->isNotEmpty())
                        &bull; Ditugaskan ke: <strong class="text-indigo-600">{{ $material->assignedUsers->pluck('name')->join(', ') }}</strong>
                    @endif
                </p>
            </div>
            @php
                $backUrl = ($material->target_type === 'custom' && $material->assignedUsers->count() === 1)
                    ? route('pembimbing.materials.personal', $material->assignedUsers->first()->id)
                    : route('pembimbing.materials.index', ['tab' => $material->target_type === 'custom' ? 'mandiri' : 'divisi']);
            @endphp
            <a href="{{ $backUrl }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-lg hover:bg-gray-50 transition-colors shadow-sm text-decoration-none">
                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
        
        @if($material->is_task && $material->deadline)
        <div class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-red-700 bg-red-100 rounded">
            ⏰ Deadline: {{ \Carbon\Carbon::parse($material->deadline)->translatedFormat('d F Y, H:i') }} WIB
        </div>
        @endif
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2 shadow-sm border border-gray-200 dark:border-slate-800 overflow-hidden mb-3">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 dark:bg-slate-800/60 border-b border-gray-200 dark:border-slate-800">
                        <th class="px-3.5 py-2 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider">No</th>
                        <th class="px-3.5 py-2 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider">Peserta</th>
                        <th class="px-3.5 py-2 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider">Waktu Pengumpulan</th>
                        @if(!empty($material->youtube_url))
                        <th class="px-3.5 py-2 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider">Video (YT)</th>
                        @endif
                        <th class="px-3.5 py-2 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider">File/Tautan</th>
                        <th class="px-3.5 py-2 text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-800 text-xs">
                    @if($material->target_type === 'custom' && $material->assignedUsers->isNotEmpty())
                        {{-- Tampilkan Seluruh Peserta yang Ditugaskan --}}
                        @php
                            $hasDeadline = !empty($material->deadline);
                            $deadlineCarbon = $hasDeadline ? \Carbon\Carbon::parse($material->deadline) : null;
                        @endphp
                        @foreach($material->assignedUsers as $index => $assignedUser)
                            @php
                                $submission = $material->submissions->firstWhere('user_id', $assignedUser->id);
                                $hasSubmitted = $submission !== null;
                                $isOverdue = $hasDeadline && $deadlineCarbon->isPast() && !$hasSubmitted;
                                $watched = (bool) ($submission->is_video_watched ?? false);
                                $videoLinkExists = !empty($material->youtube_url);
                                $watchedAt = !empty($submission->video_watched_at) ? \Illuminate\Support\Carbon::parse($submission->video_watched_at) : null;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-3.5 py-2 text-xs text-gray-700 dark:text-slate-300">{{ $index + 1 }}</td>
                                <td class="px-3.5 py-2">
                                    <p class="text-xs font-bold text-gray-900 dark:text-slate-100 mb-0">{{ $assignedUser->name }}</p>
                                    <span class="text-[11px] text-gray-500">{{ $assignedUser->email }}</span>
                                </td>
                                <td class="px-3.5 py-2 text-xs text-gray-600 dark:text-slate-400">
                                    @if($hasSubmitted && $submission->created_at)
                                        {{ $submission->created_at->translatedFormat('d M Y, H:i') }} WIB
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                @if($videoLinkExists)
                                <td class="px-3.5 py-2">
                                    @if($watched)
                                        <span title="Ditonton tuntas pada: {{ $watchedAt ? $watchedAt->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}" class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded" style="background-color: #d1fae5 !important; color: #065f46 !important; border: 1px solid #6ee7b7;">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Video Tuntas
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                @endif
                                <td class="px-3.5 py-2 text-xs">
                                    @if($hasSubmitted)
                                        @php
                                            $subFiles = $submission->all_files;
                                            $subLink = $submission->submission_link ?: $submission->link;
                                        @endphp
                                        @if(count($subFiles) > 0)
                                            <div class="d-flex flex-column gap-1">
                                                @foreach($subFiles as $f)
                                                    <a href="{{ Storage::url($f['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center text-truncate text-decoration-none" style="max-width: 220px;" title="{{ $f['name'] }}">
                                                        <i class="bi bi-file-earmark-arrow-down me-1 text-primary"></i>
                                                        <span class="text-truncate">{{ $f['name'] }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                        @if($subLink)
                                            <div class="{{ count($subFiles) > 0 ? 'mt-1' : '' }}">
                                                <a href="{{ $subLink }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center text-truncate text-decoration-none" style="max-width: 220px;">
                                                    <i class="bi bi-link-45deg me-1"></i> Buka Tautan
                                                </a>
                                            </div>
                                        @endif
                                        @if(count($subFiles) === 0 && !$subLink)
                                            <span class="text-gray-400 italic">Tidak ada berkas/link</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Belum ada pengumpulan</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-2">
                                    @if($hasSubmitted)
                                        @if($submission->score !== null)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-emerald-800 bg-emerald-100 rounded">
                                                Dinilai: {{ $submission->score }}/100
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-amber-800 bg-amber-100 rounded">
                                                Diserahkan
                                            </span>
                                        @endif
                                    @else
                                        @if($isOverdue)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-rose-800 bg-rose-100 rounded">
                                                Terlambat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold text-gray-600 bg-gray-100 rounded">
                                                Belum Mengumpulkan
                                            </span>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        {{-- Kasus Penugasan Divisi: Loop Submission Masuk --}}
                        @forelse($material->submissions as $index => $submission)
                            @php
                                $watched = (bool) ($submission->is_video_watched ?? false);
                                $videoLinkExists = !empty($material->youtube_url);
                                $watchedAt = !empty($submission->video_watched_at) ? \Illuminate\Support\Carbon::parse($submission->video_watched_at) : null;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-3.5 py-2 text-xs text-gray-700 dark:text-slate-300">{{ $index + 1 }}</td>
                                <td class="px-3.5 py-2">
                                    <p class="text-xs font-bold text-gray-900 dark:text-slate-100 mb-0">{{ $submission->user->name ?? $submission->user->nama ?? 'Anonim' }}</p>
                                </td>
                                <td class="px-3.5 py-2 text-xs text-gray-600 dark:text-slate-400">
                                    {{ $submission->created_at->translatedFormat('d M Y, H:i') }} WIB
                                </td>
                                @if($videoLinkExists)
                                <td class="px-3.5 py-2">
                                    @if($watched)
                                        <span title="Ditonton tuntas pada: {{ $watchedAt ? $watchedAt->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}" class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded" style="background-color: #d1fae5 !important; color: #065f46 !important; border: 1px solid #6ee7b7;">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Video Tuntas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded" style="background-color: #fee2e2 !important; color: #991b1b !important; border: 1px solid #fecaca;">
                                            Video Belum Tuntas
                                        </span>
                                    @endif
                                </td>
                                @endif
                                <td class="px-3.5 py-2 text-xs">
                                    @php
                                        $subFiles = $submission->all_files;
                                        $subLink = $submission->submission_link ?: $submission->link;
                                    @endphp
                                    @if(count($subFiles) > 0)
                                        <div class="d-flex flex-column gap-1">
                                            @foreach($subFiles as $f)
                                                <a href="{{ Storage::url($f['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center text-truncate text-decoration-none" style="max-width: 220px;" title="{{ $f['name'] }}">
                                                    <i class="bi bi-file-earmark-arrow-down me-1 text-primary"></i>
                                                    <span class="text-truncate">{{ $f['name'] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($subLink)
                                        <div class="{{ count($subFiles) > 0 ? 'mt-1' : '' }}">
                                            <a href="{{ $subLink }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 font-semibold inline-flex items-center text-truncate text-decoration-none" style="max-width: 220px;">
                                                <i class="bi bi-link-45deg me-1"></i> Buka Tautan
                                            </a>
                                        </div>
                                    @endif
                                    @if(count($subFiles) === 0 && !$subLink)
                                        <span class="text-gray-400 italic">Tidak ada file/tautan</span>
                                    @endif
                                </td>
                                <td class="px-3.5 py-2">
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold text-emerald-800 bg-emerald-100 rounded">
                                        Diserahkan
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ !empty($material->youtube_url) ? 6 : 5 }}" class="px-3.5 py-6 text-center text-gray-500 text-xs">
                                    Belum ada peserta yang mengumpulkan tugas ini.
                                </td>
                            </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
