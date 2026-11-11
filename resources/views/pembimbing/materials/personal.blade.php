@extends('layouts.app')
@section('title', 'Riwayat Penugasan Mandiri — ' . $intern->name)
@section('content')
<div class="container-fluid">
    <!-- Tombol Kembali & Breadcrumb -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('pembimbing.materials.index', ['tab' => 'mandiri']) }}" class="btn btn-outline-secondary btn-sm py-1 px-3 text-xs font-semibold d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-1.5"></i> Kembali ke Daftar Penugasan Mandiri
        </a>
        <a href="{{ route('pembimbing.materials.create', ['target' => 'custom', 'user_id' => $intern->id]) }}" class="btn btn-sm py-1.5 px-3.5 text-xs font-bold rounded-2 shadow-sm d-inline-flex align-items-center" style="background-color: #4f46e5 !important; border: 1px solid #4338ca !important; color: #ffffff !important;">
            <i class="bi bi-plus-lg me-1.5"></i> Berikan Tugas Baru ke {{ Str::words($intern->name, 2, '') }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 text-xs rounded-2 mb-3 shadow-xs d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-6"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Kartu Profil Anak Magang (Dossier Header) -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs p-3.5 sm:p-4 mb-3.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                @if(!empty($intern->avatar))
                    <img src="{{ Storage::url($intern->avatar) }}" alt="{{ $intern->name }}" class="w-14 h-14 rounded-full object-cover border-2 border-indigo-200 shrink-0">
                @else
                    <div class="w-14 h-14 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-lg shrink-0 border-2 border-indigo-200">
                        {{ strtoupper(substr($intern->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="h5 mb-0 fw-bold text-slate-900 dark:text-slate-100">{{ $intern->name }}</h1>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 me-1.5 animate-pulse"></span>
                            Peserta Aktif
                        </span>
                        @if($registration?->position)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-300">
                                <i class="bi bi-briefcase me-1"></i> {{ $registration->position->nama_posisi }}
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-3 flex-wrap">
                        <span><i class="bi bi-envelope me-1"></i>{{ $intern->email }}</span>
                        @if(!empty($intern->profile?->institusi))
                            <span><i class="bi bi-mortarboard me-1"></i>{{ $intern->profile->institusi }} ({{ $intern->profile->jurusan ?? '-' }})</span>
                        @endif
                        @if($registration?->periode_mulai && $registration?->periode_selesai)
                            <span>
                                <i class="bi bi-calendar3 me-1"></i>
                                Periode: {{ \Carbon\Carbon::parse($registration->periode_mulai)->translatedFormat('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($registration->periode_selesai)->translatedFormat('d M Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Tugas Mandiri -->
    <div class="row g-2.5 mb-3.5">
        <div class="col-6 col-md-3">
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-3 shadow-xs">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Total Tugas Mandiri</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-slate-900 dark:text-slate-100">{{ $stats['total'] }}</span>
                    <span class="text-xs text-slate-500">tugas</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-3 shadow-xs">
                <div class="text-[11px] font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider mb-1">Menunggu Review</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-amber-600 dark:text-amber-400">{{ $stats['pending_review'] }}</span>
                    <span class="text-xs text-slate-500">tugas</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-3 shadow-xs">
                <div class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">Sudah Dinilai / Selesai</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-emerald-600 dark:text-emerald-400">{{ $stats['completed'] }}</span>
                    <span class="text-xs text-slate-500">tugas</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-3 shadow-xs">
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Belum Dikumpulkan</div>
                <div class="d-flex align-items-baseline gap-2">
                    <span class="fs-4 fw-bold text-slate-700 dark:text-slate-300">{{ $stats['unsubmitted'] }}</span>
                    <span class="text-xs text-slate-500">tugas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat Penugasan Mandiri -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden mb-4">
        <div class="px-3.5 py-3 border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/50 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-journal-bookmark-fill text-indigo-600"></i>
                <h2 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-0">
                    Riwayat Penugasan Mandiri Spesifik
                </h2>
            </div>
            <span class="text-xs text-slate-500">
                Menampilkan <strong>{{ $personalMaterials->count() }}</strong> penugasan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="table table-bordered table-hover align-middle mb-0 text-xs table-sm">
                <thead class="table-light bg-slate-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="text-center py-2 px-3" style="width: 48px;">No</th>
                        <th class="py-2 px-3">Judul Tugas Mandiri</th>
                        <th class="py-2 px-3" style="width: 140px;">Format Pengumpulan</th>
                        <th class="py-2 px-3" style="width: 190px;">Waktu &amp; Batas Waktu</th>
                        <th class="text-center py-2 px-3" style="width: 160px;">Status Pengerjaan</th>
                        <th class="text-center py-2 px-3" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personalMaterials as $material)
                        @php
                            $sub = $material->submissions->first();
                            $hasSubmitted = $sub !== null;
                            $hasDeadline = !empty($material->deadline);
                            $deadlineCarbon = $hasDeadline ? \Carbon\Carbon::parse($material->deadline) : null;
                            $isOverdue = $hasDeadline && $deadlineCarbon->isPast() && !$hasSubmitted;
                        @endphp
                        <tr>
                            <td class="text-center text-muted fw-semibold py-2.5 px-3">{{ $loop->iteration }}</td>
                            <td class="py-2.5 px-3">
                                <div class="d-flex align-items-center gap-1.5 flex-wrap">
                                    <span class="fw-bold text-slate-900 dark:text-slate-100">{{ $material->title }}</span>
                                    <span style="background-color: #ede9fe !important; color: #5b21b6 !important; border: 1px solid #ddd6fe !important; padding: 0.12rem 0.45rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 700;">
                                        <i class="bi bi-person-fill me-0.5"></i> Mandiri
                                    </span>
                                    @php $filesCount = count($material->all_files); @endphp
                                    @if($filesCount > 0)
                                        <span style="background-color: #dbeafe !important; color: #1e40af !important; border: 1px solid #bfdbfe !important; padding: 0.12rem 0.45rem; border-radius: 9999px; font-size: 0.65rem; font-weight: 700;">
                                            <i class="bi bi-paperclip me-0.5"></i> {{ $filesCount }} Berkas
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ Str::limit($material->description, 80) }}
                                </div>
                            </td>
                            <td class="py-2.5 px-3">
                                <span class="badge bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700 text-[11px] font-semibold">
                                    {{ $material->submission_type_label }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3">
                                <div class="text-[11px] text-slate-600 dark:text-slate-300">
                                    <i class="bi bi-calendar-event me-1 text-slate-400"></i>
                                    {{ $material->created_at?->translatedFormat('d M Y, H:i') }}
                                </div>
                                @if($hasDeadline)
                                    <div class="text-[11px] mt-0.5 {{ $isOverdue ? 'text-red-600 font-bold' : 'text-slate-500' }}">
                                        <i class="bi bi-alarm me-1 {{ $isOverdue ? 'text-red-500' : 'text-slate-400' }}"></i>
                                        Batas: {{ $deadlineCarbon->translatedFormat('d M Y, H:i') }}
                                        @if($isOverdue)
                                            <span class="text-red-600">(Lewat)</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-[10px] text-slate-400 mt-0.5">Tanpa batas waktu</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                @if($hasSubmitted)
                                    @if($sub->status === 'approved' || $sub->status === 'completed' || $sub->score !== null)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Dinilai
                                            @if($sub->score !== null)
                                                ({{ $sub->score }}/100)
                                            @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Menunggu Review
                                        </span>
                                    @endif
                                    <div class="text-[10px] text-slate-500 mt-0.5">
                                        Dikumpulkan: {{ $sub->created_at?->translatedFormat('d M, H:i') }}
                                    </div>
                                @else
                                    @if($isOverdue)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <i class="bi bi-exclamation-octagon-fill me-1"></i>
                                            Terlambat
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            <i class="bi bi-clock me-1"></i>
                                            Belum Mengumpulkan
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <div class="flex items-center justify-center gap-1.5 flex-nowrap">
                                    <!-- Review / Detail Pengumpulan -->
                                    <a href="{{ route('pembimbing.materials.submissions', $material->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors text-decoration-none whitespace-nowrap">
                                        <i class="bi bi-eye"></i>
                                        <span>Review</span>
                                    </a>

                                    <!-- Edit Tugas -->
                                    <a href="{{ route('pembimbing.materials.edit', $material->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors text-decoration-none whitespace-nowrap">
                                        <i class="bi bi-pencil-square"></i>
                                        <span>Edit</span>
                                    </a>

                                    <!-- Hapus Tugas Mandiri -->
                                    <form action="{{ route('pembimbing.materials.destroy', $material->id) }}" method="POST" class="inline m-0 p-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="bg-rose-50 text-rose-600 hover:bg-rose-100 border border-rose-200 flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors cursor-pointer whitespace-nowrap"
                                                data-simang-confirm="<div class='row g-1'><div class='col-4 text-slate-500 font-semibold'>Judul Tugas</div><div class='col-8 fw-bold text-slate-900'>{{ addslashes($material->title) }}</div><div class='col-4 text-slate-500 font-semibold'>Ditugaskan Ke</div><div class='col-8 fw-bold text-indigo-700'>{{ addslashes($intern->name) }}</div></div>"
                                                data-simang-title="Hapus Penugasan Mandiri"
                                                data-simang-icon="bi-trash-fill"
                                                data-simang-theme="rose"
                                                data-simang-alert="Penugasan mandiri ini beserta seluruh berkas pengerjaan peserta akan dihapus secara permanen."
                                                data-simang-btn-text="Ya, Hapus Penugasan"
                                                data-simang-btn-icon="bi-trash-fill">
                                            <i class="bi bi-trash"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <i class="bi bi-folder-plus fs-2 text-indigo-300 dark:text-indigo-700 mb-2 d-block"></i>
                                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 mb-1">Belum Ada Penugasan Mandiri</h3>
                                    <p class="text-xs text-slate-500 mb-3 max-w-md mx-auto">
                                        Peserta ini belum memiliki penugasan mandiri khusus. Berikan tugas perdana yang disesuaikan dengan bidang keahliannya.
                                    </p>
                                    <a href="{{ route('pembimbing.materials.create', ['target' => 'custom', 'user_id' => $intern->id]) }}" class="btn btn-primary btn-sm py-1.5 px-3 text-xs font-bold rounded-2">
                                        <i class="bi bi-plus-lg me-1"></i> Berikan Tugas Pertama ke {{ Str::words($intern->name, 2, '') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

