@extends('layouts.app')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-5">
    
    <!-- Header Card -->
    <div class="bg-white rounded-2 shadow-sm border border-gray-200 p-3 sm:p-4 mb-3">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="text-base sm:text-lg font-bold text-gray-900">{{ $material->title ?? $material->judul }}</h1>
                <p class="text-xs text-gray-500 mt-0.5">Daftar Pengumpulan Tugas</p>
            </div>
            <a href="{{ route('pembimbing.materials.index') }}" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
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
    <div class="bg-white rounded-2 shadow-sm border border-gray-200 overflow-hidden mb-3">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
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
                <tbody class="divide-y divide-gray-200 text-xs">
                    @forelse($material->submissions as $index => $submission)
                        @php
                            $watched = (bool) ($submission->is_video_watched ?? false);
                            $videoLinkExists = !empty($material->youtube_url);
                            $watchedAt = !empty($submission->video_watched_at) ? \Illuminate\Support\Carbon::parse($submission->video_watched_at) : null;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-3.5 py-2 text-xs text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-3.5 py-2">
                                <p class="text-xs font-bold text-gray-900">{{ $submission->user->name ?? $submission->user->nama ?? 'Anonim' }}</p>
                            </td>
                            <td class="px-3.5 py-2 text-xs text-gray-600">
                                {{ $submission->created_at->translatedFormat('d M Y, H:i') }} WIB
                            </td>
                            @if($videoLinkExists)
                            <td class="px-3.5 py-2">
                                @if($watched)
                                    <span title="Ditonton tuntas pada: {{ $watchedAt ? $watchedAt->translatedFormat('d M Y, H:i') . ' WIB' : '-' }}" class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded" style="background-color: #d1fae5 !important; color: #065f46 !important; border: 1px solid #6ee7b7;">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Video Tuntas
                                    </span>
                                    @if($watchedAt)
                                        <div class="text-[10px] text-gray-500 mt-0.5">
                                            {{ $watchedAt->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    @endif
                                @else
                                    <span title="Peserta belum menonton video sampai selesai." class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded" style="background-color: #fee2e2 !important; color: #991b1b !important; border: 1px solid #fecaca;">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        Video Belum Tuntas
                                    </span>
                                @endif
                            </td>
                            @endif
                            <td class="px-3.5 py-2 text-xs">
                                @if($submission->file_path)
                                    <a href="{{ Storage::url($submission->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Unduh File
                                    </a>
                                @elseif($submission->link)
                                    <a href="{{ $submission->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                        Buka Tautan
                                    </a>
                                @else
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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
