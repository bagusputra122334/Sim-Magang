@extends('layouts.admin')

@section('title', 'Survei Kepuasan')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-5">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-star-fill text-amber-400"></i> Survei Kepuasan
            </h1>
            <p class="text-sm text-slate-700 mt-1">
                Daftar umpan balik dan tingkat kepuasan layanan dari pengguna SIM-MAGANG Diskominfo Tuban.
            </p>
        </div>
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/50">
                <i class="bi bi-shield-check"></i> Admin Access
            </span>
        </div>
    </div>

    {{-- Statistics Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Responden --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Responden</p>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">
                        {{ number_format($statistics['total']) }}
                    </h3>
                </div>
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-blue-500 text-white shadow-sm text-xl font-bold">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        {{-- Rata-rata Rating --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rata-rata Rating</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <h3 class="text-2xl font-bold text-slate-800 dark:text-white">
                            {{ $statistics['average'] }}
                        </h3>
                        <span class="text-xs text-slate-500">/ 5.0</span>
                    </div>
                </div>
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm text-xl font-bold">
                    <i class="bi bi-star-fill"></i>
                </div>
            </div>
        </div>

        {{-- Rating 5 Bintang --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sangat Puas (5 ★)</p>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">
                        {{ number_format($statistics['counts'][5] ?? 0) }}
                    </h3>
                </div>
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm text-xl font-bold">
                    <i class="bi bi-emoji-smile-fill"></i>
                </div>
            </div>
        </div>

        {{-- Rating 1-3 Bintang --}}
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ulasan Perbaikan (1-3 ★)</p>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">
                        {{ number_format(($statistics['counts'][1] ?? 0) + ($statistics['counts'][2] ?? 0) + ($statistics['counts'][3] ?? 0)) }}
                    </h3>
                </div>
                <div class="w-12 h-12 flex items-center justify-center rounded-xl bg-purple-500 text-white shadow-sm text-xl font-bold">
                    <i class="bi bi-chat-left-text"></i>
                </div>
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
                                        {{ $survey->komentar }}
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
