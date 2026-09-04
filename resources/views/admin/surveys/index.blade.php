@extends('layouts.admin')

@section('title', 'Survei Kepuasan')

@section('content')
@php
    $totalResponden = number_format($statistics['total'] ?? 0);
    $rataRata = $statistics['average'] ?? 0;
    $sangatPuas = number_format($statistics['counts'][5] ?? 0);
    $ulasanPerbaikan = number_format(($statistics['counts'][1] ?? 0) + ($statistics['counts'][2] ?? 0) + ($statistics['counts'][3] ?? 0));
@endphp

<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-star-fill text-amber-400"></i> Survei Kepuasan
            </h1>
            <p class="text-sm text-slate-600 mt-1">
                Daftar umpan balik dan tingkat kepuasan layanan dari pengguna SIM-MAGANG Diskominfo Tuban.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.surveys.export') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-md transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor Laporan IKM (PDF)
            </a>
        </div>
    </div>

    {{-- Statistics Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6 mt-4">
        <!-- Card 1: Total Responden -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Responden</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $totalResponden }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-blue-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <!-- Card 2: Rata-Rata Rating -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Rata-Rata Rating</p>
                <div class="flex items-baseline gap-1">
                    <h3 class="text-2xl font-extrabold text-slate-800">{{ number_format((float)$rataRata, 1) }}</h3>
                    <span class="text-sm font-medium text-slate-500">/ 5.0</span>
                </div>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-amber-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
        </div>

        <!-- Card 3: Sangat Puas -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Sangat Puas (5★)</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $sangatPuas }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-emerald-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <!-- Card 4: Ulasan Perbaikan -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Ulasan Perbaikan</p>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $ulasanPerbaikan }}</h3>
            </div>
            <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-purple-500 text-white shadow-sm shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
        </div>
    </div>

    {{-- Main Survey Table Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-table text-indigo-600 dark:text-indigo-400"></i> Data Submit Survei Kepuasan
            </h2>
            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                Menampilkan Halaman {{ $surveys->currentPage() }} dari {{ $surveys->lastPage() ?: 1 }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 text-xs uppercase tracking-wider font-bold text-slate-700 dark:text-slate-200">
                        <th scope="col" class="py-3.5 px-6 w-16 text-center">No</th>
                        <th scope="col" class="py-3.5 px-6 w-48">Nilai</th>
                        <th scope="col" class="py-3.5 px-6">Komentar</th>
                        <th scope="col" class="py-3.5 px-6 w-44">IP Address</th>
                        <th scope="col" class="py-3.5 px-6 w-48">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm text-slate-700 dark:text-slate-300">
                    @forelse ($surveys as $index => $survey)
                        <tr class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            {{-- Number --}}
                            <td class="py-4 px-6 text-center font-bold text-slate-600 dark:text-slate-400">
                                {{ $surveys->firstItem() + $index }}
                            </td>

                            {{-- Nilai (Rating Stars) --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center text-amber-500 text-base space-x-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $survey->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star text-slate-300 dark:text-slate-600"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-300 dark:border-amber-700">
                                        {{ $survey->rating }}/5
                                    </span>
                                </div>
                            </td>

                            {{-- Komentar --}}
                            <td class="py-4 px-6">
                                @if (!empty($survey->komentar))
                                    <p class="text-sm text-slate-700 font-medium leading-relaxed">
                                        {{ \Illuminate\Support\Str::limit($survey->komentar ?? '-', 75) }}
                                    </p>
                                @else
                                    <span class="italic text-slate-500 text-xs font-normal">
                                        Tidak ada komentar
                                    </span>
                                @endif
                            </td>

                            {{-- IP Address --}}
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-mono font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    <i class="bi bi-globe2 text-slate-500"></i>
                                    {{ $survey->ip_address }}
                                </span>
                            </td>

                            {{-- Waktu --}}
                            <td class="py-4 px-6 text-xs text-slate-700 font-medium whitespace-nowrap">
                                <div class="flex items-center gap-1.5">
                                    <i class="bi bi-clock text-slate-500"></i>
                                    <span>{{ $survey->created_at ? $survey->created_at->format('d M Y H:i') : '-' }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 text-2xl">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <p class="font-medium text-slate-600 dark:text-slate-300">Belum ada data survei kepuasan</p>
                                    <p class="text-xs text-slate-400">Data tanggapan pengguna yang masuk akan ditampilkan di tabel ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($surveys->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30">
                {{ $surveys->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
