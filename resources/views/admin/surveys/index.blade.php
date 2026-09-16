@extends('layouts.admin')

@section('title', 'Survei Kepuasan')

@section('content')
@php
    $totalResponden = number_format($statistics['total'] ?? 0);
    $rataRata = $statistics['average'] ?? 0;
    $sangatPuas = number_format($statistics['counts'][5] ?? 0);
    $ulasanPerbaikan = number_format(($statistics['counts'][1] ?? 0) + ($statistics['counts'][2] ?? 0) + ($statistics['counts'][3] ?? 0));
@endphp

<div class="space-y-4">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center pb-2 mb-3 border-bottom">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-star-fill text-warning fs-4"></i> Survei Kepuasan
            </h1>
        </div>
        <div>
            <a href="{{ route('admin.surveys.export') }}" class="btn btn-primary btn-sm px-3 py-2 fw-bold rounded-lg shadow-sm d-inline-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-pdf"></i>
                Ekspor PDF
            </a>
        </div>
    </div>

    {{-- Top Statistics Row (Ultra-Compact, 4 Columns) --}}
    <div class="row g-3 mb-4">
        <!-- Card 1: Total Responden -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Total Responden</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $totalResponden }}</h5>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Rata-Rata Rating -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Rata-Rata Rating</p>
                        <div class="d-flex align-items-baseline gap-1">
                            <h5 class="fw-bold mb-0 text-dark">{{ number_format((float)$rataRata, 1) }}</h5>
                            <span class="text-muted small" style="font-size: 0.75rem;">/ 5.0</span>
                        </div>
                    </div>
                    <div class="text-warning">
                        <i class="bi bi-star-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Sangat Puas -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Sangat Puas (5★)</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $sangatPuas }}</h5>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-emoji-smile-fill fs-5"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Ulasan Perbaikan -->
        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-2 px-3 d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Ulasan Perbaikan</p>
                        <h5 class="fw-bold mb-0 text-dark">{{ $ulasanPerbaikan }}</h5>
                    </div>
                    <div class="text-info">
                        <i class="bi bi-chat-left-text-fill fs-5"></i>
                    </div>
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
