@extends('layouts.participant')

@section('title', 'Sertifikat Magang — SIMAGANG Diskominfo Tuban')

@section('content')
<div class="w-full px-0 pt-0 pb-6">
    <!-- Header Halaman -->
    <div class="mb-3">
        <h1 class="h4 fw-bold mb-1 text-gray-900 dark:text-white d-flex align-items-center gap-2" style="letter-spacing: -0.02em;">
            <i class="bi bi-award-fill text-primary"></i>
            <span>Sertifikat Kelulusan Magang</span>
        </h1>
        <p class="text-muted small mb-0">
            Penerbitan dan pengunduhan dokumen resmi sertifikat kelulusan digital DISKOMINFO SP Kabupaten Tuban.
        </p>
    </div>

    <!-- Alert / Notice Box -->
    @if(!$certExists)
        <div class="alert alert-info py-2.5 px-3 mb-3 border border-blue-200 bg-blue-50/70 text-blue-900 rounded-xl d-flex align-items-start gap-2 text-xs">
            <i class="bi bi-info-circle-fill text-blue-600 fs-6 mt-0.5 flex-shrink-0"></i>
            <div>
                <strong class="d-block mb-0.5">Informasi Penerbitan Sertifikat:</strong>
                @if($isTerminated)
                    Akun magang Anda berstatus dinonaktifkan / diberhentikan sehingga sertifikat kelulusan tidak dapat diterbitkan. Silakan hubungi admin / pembimbing jika memerlukan informasi lebih lanjut.
                @elseif($isGraduationReached)
                    Anda telah menyelesaikan rangkaian masa magang. Sertifikat kelulusan digital berformat PDF sedang dalam antrean pengunggahan dan evaluasi akhir oleh Pembimbing divisi Anda.
                @else
                    Sertifikat kelulusan digital akan otomatis diproses setelah Anda mencapai akhir periode magang ({{ $reg?->periode_selesai?->translatedFormat('d F Y') ?? 'akhir periode' }}) dan seluruh evaluasi tugas selesai disetujui.
                @endif
            </div>
        </div>
    @endif

    <!-- Main Certificate Card (Full Width) -->
    <div class="w-full card shadow-sm border border-slate-200 dark:border-slate-800 rounded-xl bg-white dark:bg-slate-900 overflow-hidden mb-3">
        <!-- Card Header -->
        <div class="card-header py-2.5 px-3 sm:px-4 bg-slate-50/70 dark:bg-slate-800/60 border-bottom border-slate-200 dark:border-slate-800 d-flex align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-file-earmark-pdf-fill text-rose-500 fs-5"></i>
                <h6 class="m-0 fw-bold text-slate-900 dark:text-white text-xs uppercase tracking-wider">
                    DOKUMEN SERTIFIKAT KELULUSAN RESMI
                </h6>
            </div>
        </div>

        <!-- Card Body with Full-Width Grid -->
        <div class="card-body p-4 sm:p-5">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 w-full items-start">
                <!-- Sisi Kiri: Informasi Sertifikat & Metadata (lg:col-span-8) -->
                <div class="lg:col-span-8 flex flex-col justify-between h-full">
                    <div>
                        <div class="d-flex align-items-start gap-3.5 mb-4">
                            <div class="rounded-xl {{ $certExists ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 border border-slate-200 dark:border-slate-700' }} d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px; font-size: 1.75rem;">
                                <i class="bi {{ $certExists ? 'bi-patch-check-fill' : 'bi-award' }}"></i>
                            </div>
                            <div class="min-w-0">
                                <h3 class="h5 fw-bold text-slate-900 dark:text-white mb-1.5">
                                    {{ $certExists ? 'Sertifikat Kelulusan Digital Resmi Diskominfo Tuban' : 'Status Kelulusan Magang Digital' }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mb-0 leading-relaxed">
                                    @if($certExists)
                                        Selamat! Anda telah menyelesaikan seluruh program magang di Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban. Dokumen sertifikat digital Anda telah ditandatangani dan siap diunduh secara resmi.
                                    @else
                                        Dokumen sertifikat kelulusan resmi berformat PDF berstandar digital Diskominfo akan muncul di sini setelah diterbitkan oleh pembimbing Anda.
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Metadata Table Grid -->
                        <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/80 dark:bg-slate-800/40 text-xs sm:text-sm">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Nomor Pendaftaran / Peserta</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200 font-monospace">{{ $reg?->nomor_pendaftaran ?? '—' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Tanggal Diterbitkan</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $reg?->certificate_issued_at ? \Illuminate\Support\Carbon::parse($reg->certificate_issued_at)->translatedFormat('d F Y • H:i') . ' WIB' : 'Belum Diterbitkan' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Divisi Magang</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $division?->nama_divisi ?? ($division?->name ?? 'Aplikasi dan Informatika') }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Pembimbing Lapangan</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $mentor?->name ?? 'Pembimbing Divisi Diskominfo' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Periode Magang</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $reg?->periode_mulai?->translatedFormat('d M Y') ?? '-' }} s/d {{ $reg?->periode_selesai?->translatedFormat('d M Y') ?? '-' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-slate-500 dark:text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Posisi Magang</span>
                                    <span class="font-bold text-slate-800 dark:text-slate-200">
                                        {{ $reg?->position?->nama_posisi ?? '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sisi Kanan: Thumbnail Preview Box & Action Button (lg:col-span-4) -->
                <div class="lg:col-span-4 w-full">
                    <div class="flex flex-col gap-3 w-full">
                        @if($certExists && $reg)
                            <!-- Kotak Thumbnail Preview Dokumen PDF -->
                            <div class="relative w-full h-52 sm:h-56 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden shadow-sm group">
                                <iframe src="{{ route('participant.certificates.view', $reg->id) }}#toolbar=0&navpanes=0&scrollbar=0&view=FitH"
                                        class="w-full h-full pointer-events-none select-none border-0"
                                        title="Pratinjau Sertifikat Resmi">
                                </iframe>
                                <!-- Overlay Interaktif untuk Memperbesar/Membuka Tab Baru -->
                                <a href="{{ route('participant.certificates.view', $reg->id) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   title="Buka Pratinjau Penuh"
                                   class="absolute inset-0 bg-slate-900/10 hover:bg-slate-900/25 transition-all flex items-center justify-center opacity-0 group-hover:opacity-100 backdrop-blur-[0.5px]">
                                    <span class="px-3.5 py-1.5 text-xs font-semibold bg-white/95 dark:bg-slate-900/95 text-slate-800 dark:text-slate-100 rounded-lg shadow-md border border-slate-200 dark:border-slate-700 flex items-center gap-1.5 transform translate-y-1 group-hover:translate-y-0 transition-transform">
                                        <i class="bi bi-arrows-fullscreen text-emerald-600"></i>
                                        Pratinjau Penuh
                                    </span>
                                </a>
                                <!-- Badge status kecil di pojok thumbnail -->
                                <div class="absolute bottom-2.5 left-2.5 pointer-events-none">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-white/90 dark:bg-slate-900/90 text-slate-700 dark:text-slate-200 shadow-xs border border-slate-200/80 dark:border-slate-700/80">
                                        <i class="bi bi-file-earmark-pdf-fill text-red-500"></i>
                                        Sertifikat Resmi
                                    </span>
                                </div>
                            </div>

                            <!-- Tombol Unduh Persis di Bawah Thumbnail (Full-Width Emerald) -->
                            <a href="{{ route('participant.certificates.download', $reg->id) }}"
                               class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 px-4 rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all text-xs sm:text-sm text-decoration-none">
                                <i class="bi bi-download text-base"></i>
                                <span>Unduh Sertifikat Magang (PDF)</span>
                            </a>
                        @else
                            <!-- Placeholder Kotak Thumbnail Jika Belum Terbit -->
                            <div class="w-full h-52 sm:h-56 bg-slate-50 dark:bg-slate-900 border border-dashed border-slate-300 dark:border-slate-700 rounded-xl flex flex-col items-center justify-center p-4 text-center">
                                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center mb-2">
                                    <i class="bi bi-file-earmark-lock text-xl"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Pratinjau Belum Tersedia</span>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 max-w-[220px]">
                                    Dokumen sertifikat resmi belum diterbitkan oleh pembimbing.
                                </span>
                            </div>

                            <!-- Tombol Unduh Nonaktif -->
                            <button type="button" disabled
                                    class="w-full bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-semibold py-2.5 px-4 rounded-xl shadow-none flex items-center justify-center gap-2 cursor-not-allowed text-xs sm:text-sm border border-slate-200 dark:border-slate-700">
                                <i class="bi bi-lock-fill text-base"></i>
                                <span>Sertifikat Belum Tersedia</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
