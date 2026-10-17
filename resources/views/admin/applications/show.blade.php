@extends('layouts.admin')

@php
    $reg = $application;
    $profil = $profilPeserta ?? $reg->user?->profile ?? null;
    $statusColorMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'primary',
        \App\Enums\RegistrationStatus::UnderReview->value => 'warning',
        \App\Enums\RegistrationStatus::Accepted->value    => 'success',
        \App\Enums\RegistrationStatus::Rejected->value    => 'danger',
    ];
    $badgeColor = $statusColorMap[$reg->status->value] ?? 'secondary';

    // Surat Balasan Info
    $sbPath = $reg->surat_balasan_path;
    $sbDisk = !empty($sbPath) && \Illuminate\Support\Facades\Storage::disk('local')->exists($sbPath)
        ? \Illuminate\Support\Facades\Storage::disk('local')
        : \Illuminate\Support\Facades\Storage::disk('public');
    $sbExists = !empty($sbPath) && is_string($sbPath) && $sbDisk->exists($sbPath);
    $sbUrl = $sbExists ? route('documents.downloadByPath', ['path' => $sbPath]) : null;
    $sbBasename = $sbExists ? basename($sbPath) : null;
    $sbSize = $sbExists ? number_format((int) round($sbDisk->size($sbPath) / 1024), 0, ',', '.') . ' KB' : null;
    $sbLastModified = $sbExists ? date('d M Y H:i', $sbDisk->lastModified($sbPath)) : null;
@endphp

@section('title', 'Detail Pendaftaran '.$reg->nomor_pendaftaran)

@push('styles')
<style>
    /* ==========================================================================
       DOKUMEN PERSYARATAN (COMPACT 3-COLUMN LAYOUT) & SURAT BALASAN
       ========================================================================== */
    .doc-item-card {
        border: 1px solid var(--app-border);
        border-radius: 10px;
        background-color: var(--app-surface);
        padding: 0.95rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .doc-item-card:hover {
        border-color: var(--app-primary);
        box-shadow: var(--app-shadow-sm);
        transform: translateY(-2px);
    }

    .doc-card-header {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        margin-bottom: 0.65rem;
        min-width: 0;
        width: 100%;
    }

    .doc-icon-wrapper {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .doc-icon-wrapper.active {
        background-color: var(--app-primary-subtle);
        color: var(--app-primary);
        border: 1px solid rgba(13, 110, 253, 0.2);
    }

    .doc-icon-wrapper.inactive {
        background-color: var(--app-surface-soft);
        color: var(--app-text-muted);
        border: 1px solid var(--app-border);
    }

    .doc-card-title-group {
        flex: 1 1 0;
        min-width: 0;
        min-height: 44px;
    }

    .doc-card-title {
        font-weight: 700;
        font-size: 0.85rem;
        line-height: 1.3;
        margin: 0 0 0.15rem;
        color: var(--app-text);
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .doc-card-desc {
        font-size: 0.725rem;
        line-height: 1.25;
        color: var(--app-text-muted);
        margin: 0;
    }

    .doc-file-box {
        margin-bottom: 0.65rem;
        width: 100%;
        min-width: 0;
    }

    .file-meta-pill {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.3rem 0.55rem;
        border-radius: 6px;
        background-color: var(--app-surface-soft);
        border: 1px solid var(--app-border);
        font-size: 0.725rem;
        color: var(--app-text-secondary);
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        overflow: hidden;
    }

    .file-meta-pill span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-family: var(--bs-font-monospace);
        font-size: 0.72rem;
        flex: 1 1 0;
        min-width: 0;
    }

    /* Action Container: 50/50 compact buttons */
    .doc-card-actions {
        display: flex;
        gap: 6px;
        width: 100%;
        min-width: 0;
        margin-top: auto;
        padding-top: 0.65rem;
        border-top: 1px solid var(--app-border-subtle, var(--app-border));
        box-sizing: border-box;
    }

    .doc-card-actions .btn {
        flex: 1 1 0;
        min-width: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 32px;
        padding: 0.25rem 0.4rem;
        font-size: 0.775rem;
        font-weight: 600;
        border-radius: 6px;
        white-space: nowrap;
        box-sizing: border-box;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
    }

    .btn-doc-preview {
        background-color: var(--app-surface);
        border: 1px solid var(--app-primary);
        color: var(--app-primary) !important;
    }

    .btn-doc-preview:hover,
    .btn-doc-preview:focus {
        background-color: var(--app-primary);
        color: #ffffff !important;
        border-color: var(--app-primary);
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.25);
    }

    .btn-doc-download {
        background-color: var(--app-surface-soft);
        border: 1px solid var(--app-border);
        color: var(--app-text) !important;
    }

    .btn-doc-download:hover,
    .btn-doc-download:focus {
        background-color: var(--app-text-secondary);
        color: var(--app-surface) !important;
        border-color: var(--app-text-secondary);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    }

    .btn-doc-empty {
        width: 100%;
        min-height: 32px;
        background-color: var(--app-surface-soft);
        border: 1px dashed var(--app-border);
        color: var(--app-text-muted);
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        box-sizing: border-box;
        cursor: not-allowed;
    }

    /* Surat Balasan Card Styling */
    .reply-letter-card {
        border: 1.5px solid var(--app-border);
        border-radius: 16px;
        background-color: var(--app-surface);
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .reply-letter-card.state-uploaded {
        border-color: rgba(25, 135, 84, 0.35);
        background: linear-gradient(180deg, rgba(25, 135, 84, 0.04) 0%, var(--app-surface) 100%);
    }

    .reply-letter-card.state-pending {
        border-color: rgba(13, 110, 253, 0.35);
        background: linear-gradient(180deg, rgba(13, 110, 253, 0.04) 0%, var(--app-surface) 100%);
    }

    .reply-letter-card.state-disabled {
        background-color: var(--app-surface-soft);
        border-color: var(--app-border);
    }

    /* Profile Sidebar Card Layout Stability */
    .profile-sidebar-card {
        border: 1px solid var(--app-border);
        border-radius: 16px;
        background-color: var(--app-surface);
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .profile-avatar-img {
        width: 110px;
        height: 110px;
        min-width: 110px;
        min-height: 110px;
        max-width: 110px;
        max-height: 110px;
        aspect-ratio: 1 / 1;
        object-fit: cover;
        border-radius: 50%;
        display: block;
        margin: 0 auto 0.75rem;
    }

    .profile-avatar-placeholder {
        width: 110px;
        height: 110px;
        min-width: 110px;
        min-height: 110px;
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 2rem;
        font-weight: 700;
    }

    .profile-info-list .list-group-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.65rem 0;
        background: transparent;
        border-color: var(--app-border-subtle, var(--app-border));
        min-width: 0;
    }

    .profile-info-label {
        color: var(--app-text-muted);
        font-size: 0.8rem;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .profile-info-value {
        color: var(--app-text);
        font-size: 0.825rem;
        font-weight: 500;
        text-align: end;
        word-break: break-word;
        overflow-wrap: anywhere;
        flex-grow: 1;
        min-width: 0;
    }

    @media (prefers-reduced-motion: reduce) {
        .doc-item-card, .reply-letter-card, .profile-sidebar-card {
            transition: none !important;
            transform: none !important;
        }
    }
</style>
@endpush

@section('content')
    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 fw-bold mb-0 text-slate-800 dark:text-slate-100 d-flex align-items-center gap-2 flex-wrap">
                <i class="bi bi-file-earmark-text text-primary"></i> Detail Pendaftaran
                <span class="badge bg-light text-primary border font-monospace text-xs px-2 py-0.5">
                    {{ $reg->nomor_pendaftaran }}
                </span>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.applications.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-bold shadow-sm transition-all px-3 py-1.5 text-xs">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            @if (! $reg->isAccepted() && ! $reg->isRejected())
                <a href="{{ route('admin.applications.review', $reg->id) }}" class="btn bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-lg font-bold shadow-sm transition-all px-3 py-1.5 text-xs">
                    <i class="bi bi-pencil-square me-1"></i> Berikan Keputusan (Review)
                </a>
            @endif
        </div>
    </div>

    {{-- Business Rule #1 Alert UnderReview Auto --}}
    @if ($berubahJadiUnderReview ?? false)
        <div class="alert alert-info alert-dismissible fade show mb-3 p-2.5 text-xs shadow-sm border" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-6 me-2 flex-shrink-0"></i>
                <div>
                    <b>Status Otomatis Diperbarui:</b> Pendaftaran ini otomatis beralih dari
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle">Submitted</span> ke
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Under Review</span>
                    karena dibuka oleh Administrator untuk pertama kalinya.
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="row g-2.5">
        {{-- KOLOM KIRI: Informasi Utama & Alur Dokumen --}}
        <div class="col-lg-8">
            {{-- 1. INFORMASI PENDAFTARAN --}}
            <div class="card shadow-sm border mb-3 rounded-2">
                <div class="card-header bg-light border-0 py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h2 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                        <i class="bi bi-journal-text text-primary me-1.5"></i>Informasi Pendaftaran
                    </h2>
                    <span class="badge rounded-pill bg-{{ $badgeColor }} px-2 py-0.5 text-xs shadow-xs">
                        {{ $reg->status->label() }}
                    </span>
                </div>
                <div class="card-body p-3 sm:p-4 text-xs">
                    <dl class="row mb-0 g-2">
                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Nomor Pendaftaran</dt>
                        <dd class="col-sm-8 fw-bold font-monospace text-primary text-xs mb-0">{{ $reg->nomor_pendaftaran }}</dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Posisi Magang</dt>
                        <dd class="col-sm-8 fw-semibold mb-0 text-xs">
                            {{ $reg->position?->nama_posisi ?? '—' }}
                        </dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Pembimbing Lapangan</dt>
                        <dd class="col-sm-8 mb-0 text-xs">
                            @if($reg->position?->mentor_name)
                                <div class="fw-semibold text-body">
                                    <i class="bi bi-person-badge text-primary me-1"></i>
                                    {{ $reg->position->mentor_name }}
                                </div>
                                @if($reg->position->mentor_nip)
                                    <div class="text-muted font-monospace text-xs">NIP: {{ $reg->position->mentor_nip }}</div>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Periode Pelaksanaan</dt>
                        <dd class="col-sm-8 mb-0 text-xs">
                            <div class="fw-semibold">
                                {{ optional($reg->periode_mulai)->format('d F Y') ?? '—' }}
                                <span class="text-muted mx-1">s/d</span>
                                {{ optional($reg->periode_selesai)->format('d F Y') ?? '—' }}
                            </div>
                            @if($reg->periode_label)
                                <div class="text-muted text-xs mt-0.5">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $reg->periode_label }}
                                </div>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Waktu Pengajuan (Submit)</dt>
                        <dd class="col-sm-8 text-secondary mb-0 text-xs">
                            {{ optional($reg->tanggal_submit)->translatedFormat('d F Y • H:i') ?? '—' }} WIB
                        </dd>

                        <dt class="col-sm-4 text-xs font-bold !text-slate-800 dark:!text-slate-200">Status Verifikasi</dt>
                        <dd class="col-sm-8 mb-0 text-xs">
                            <span class="badge bg-{{ $badgeColor }} px-2 py-0.5 rounded-pill text-xs">
                                {{ $reg->status->label() }}
                            </span>
                            @if ($reg->updated_at && optional($reg->tanggal_submit)->format('c') !== $reg->updated_at->format('c'))
                                <span class="text-muted ms-2 text-xs">
                                    (Terakhir diperbarui: {{ $reg->updated_at->format('d/m/Y H:i') }})
                                </span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- 2. CATATAN VERIFIKASI ADMIN (JIKA ADA) --}}
            @if ($reg->catatan_admin)
                <div class="card shadow-sm border mb-3 rounded-2 border-{{ $reg->isRejected() ? 'danger' : ($reg->isAccepted() ? 'success' : 'info') }}">
                    <div class="card-header bg-{{ $reg->isRejected() ? 'danger' : ($reg->isAccepted() ? 'success' : 'info') }} bg-opacity-10 py-2 px-3 border-0">
                        <h3 class="text-xs font-bold uppercase tracking-wider mb-0 text-{{ $reg->isRejected() ? 'danger' : ($reg->isAccepted() ? 'success' : 'info') }}">
                            <i class="bi bi-chat-left-quote-fill me-1.5"></i>Catatan Verifikasi Administrator
                        </h3>
                    </div>
                    <div class="card-body p-3 sm:p-4 text-xs">
                        <p class="mb-0 text-body lh-base" style="white-space: pre-wrap;">{{ $reg->catatan_admin }}</p>
                    </div>
                </div>
            @endif

            {{-- 3. SURAT BALASAN RESMI (DOKUMEN ADMINISTRASI DINAS) --}}
            <div class="card shadow-sm border mb-3 rounded-2 overflow-hidden">
                <div class="card-header bg-light border-0 py-2 px-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                            <i class="bi bi-file-earmark-check text-success me-1.5"></i>Surat Balasan
                        </h2>
                    </div>
                    @if ($sbExists)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-2 py-0.5 rounded-pill fw-bold text-xs">
                            <i class="bi bi-check-circle-fill me-1"></i> Surat Telah Terbit
                        </span>
                    @elseif (! $reg->isAccepted())
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-2 py-0.5 rounded-pill fw-bold text-xs">
                            <i class="bi bi-lock-fill me-1"></i> Belum Tersedia
                        </span>
                    @endif
                </div>

                <div class="card-body p-3 sm:p-4">
                    {{-- STATE A: SUDAH DIUNGGAH --}}
                    @if ($sbExists)
                        <div class="reply-letter-card state-uploaded p-3 rounded-2">
                            <div class="d-flex align-items-start gap-2.5 flex-wrap flex-md-nowrap mb-2.5">
                                <div class="p-2 rounded-2 bg-success bg-opacity-10 text-success border border-success-subtle d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-file-earmark-check-fill fs-4"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h4 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase mb-0">Surat Balasan</h4>
                                        <span class="badge bg-success text-white px-2 py-0.5 rounded-pill text-xs">Sudah Diunggah</span>
                                    </div>
                                    <p class="text-muted text-xs mb-2">
                                        Surat balasan resmi telah diterbitkan dan tersedia untuk diunduh oleh peserta melalui portal pendaftaran mereka.
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <div class="file-meta-pill">
                                            <i class="bi bi-filetype-pdf text-danger"></i>
                                            <span class="font-monospace fw-semibold">{{ $sbBasename }}</span>
                                        </div>
                                        <div class="file-meta-pill">
                                            <i class="bi bi-hdd text-secondary"></i>
                                            <span>{{ $sbSize }}</span>
                                        </div>
                                        @if($sbLastModified)
                                            <div class="file-meta-pill">
                                                <i class="bi bi-calendar-check text-success"></i>
                                                <span>Diunggah: {{ $sbLastModified }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-2 pt-2 border-top justify-content-start align-items-center">
                                <a href="{{ $sbUrl }}" target="_blank" rel="noopener"
                                   class="btn btn-outline-primary btn-sm px-2.5 py-1 text-xs font-semibold shadow-xs">
                                    <i class="bi bi-eye me-1"></i> Preview
                                </a>
                                <a href="{{ route('admin.applications.reply-letter.download', $reg->id) }}"
                                   class="btn btn-secondary btn-sm px-2.5 py-1 text-xs shadow-sm font-semibold">
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                                <a href="{{ route('admin.applications.reply-letter', $reg->id) }}"
                                   class="btn btn-outline-secondary btn-sm px-2.5 py-1 text-xs ms-auto font-semibold">
                                    <i class="bi bi-arrow-repeat me-1"></i> Ganti Surat
                                </a>
                            </div>
                        </div>

                    {{-- STATE B: BELUM DIUNGGAH TAPI STATUS ACCEPTED --}}
                    @elseif ($reg->isAccepted())
                        <div class="reply-letter-card state-pending p-3 rounded-2 border border-slate-200 bg-white" x-data="{ fileName: '' }">
                            <form method="POST" action="{{ route('admin.applications.reply-letter.store', $reg->id) }}" enctype="multipart/form-data" class="space-y-2.5">
                                @csrf
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                        <label for="surat_balasan" class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0 flex items-center gap-1.5">
                                            <i class="bi bi-filetype-pdf text-rose-500"></i> Unggah Surat Balasan (PDF) <span class="text-rose-500">*</span>
                                        </label>
                                        <span class="text-xs text-slate-400">Maks. 2 MB</span>
                                    </div>
                                    
                                    <div class="relative border border-dashed rounded-lg p-2.5 text-center transition-all duration-150"
                                         :class="fileName ? 'border-blue-500 bg-blue-50/30' : 'border-slate-300 bg-slate-50/60 hover:border-blue-400 hover:bg-blue-50/10'">
                                        <input
                                            type="file"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('surat_balasan') is-invalid @enderror"
                                            id="surat_balasan"
                                            name="surat_balasan"
                                            accept="application/pdf,.pdf"
                                            @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                                            required>
                                        
                                        <!-- Default Dropzone Prompt (Hides when file is selected) -->
                                        <div x-show="!fileName" class="flex items-center justify-center gap-2 py-0.5">
                                            <i class="bi bi-cloud-arrow-up text-blue-600 text-base"></i>
                                            <span class="font-bold text-slate-700 text-xs">Pilih atau seret file PDF ke sini</span>
                                        </div>
                                        
                                        <!-- Active Selected File State -->
                                        <div x-show="fileName" x-cloak class="flex items-center justify-center gap-2 py-0.5">
                                            <i class="bi bi-file-earmark-pdf text-blue-600 text-base"></i>
                                            <span class="font-bold text-slate-800 text-xs truncate max-w-xs" x-text="fileName"></span>
                                            <button type="button" @click.stop.prevent="fileName = ''; document.getElementById('surat_balasan').value = ''" class="text-slate-400 hover:text-rose-600 transition-colors ms-1 relative z-20" title="Batal pilih file">
                                                <i class="bi bi-x-circle-fill text-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('surat_balasan')
                                        <div class="text-rose-500 text-xs font-medium mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Action Footer: Primary Submit Button -->
                                <div class="flex justify-end pt-1">
                                    <button type="submit"
                                            class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-bold text-xs rounded-lg transition-all shadow-sm flex items-center gap-1.5"
                                            :class="!fileName ? 'opacity-60 cursor-not-allowed' : 'hover:shadow-md'"
                                            :disabled="!fileName">
                                        <i class="bi bi-upload"></i>
                                        <span>Kirim Surat Balasan</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    {{-- STATE C: BELUM TERSEDIA KARENA STATUS BELUM ACCEPTED --}}
                    @else
                        <div class="reply-letter-card state-disabled p-3 rounded-2 text-muted">
                            <div class="d-flex align-items-start gap-2.5">
                                <div class="p-2 rounded-2 bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-shield-lock-fill fs-4"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold mb-1 text-body uppercase">Surat Balasan</h4>
                                    @if ($reg->isRejected())
                                        <p class="text-xs mb-0 text-danger">
                                            <i class="bi bi-x-circle me-1"></i> Pendaftaran ini berstatus <b>Ditolak (Rejected)</b>. Surat balasan penerimaan magang tidak diterbitkan.
                                        </p>
                                    @else
                                        <p class="text-xs mb-0 text-muted">
                                            Belum ada surat balasan yang diunggah untuk pendaftaran ini. Surat balasan resmi diterbitkan setelah pendaftaran dinyatakan <b>Diterima (Accepted)</b> oleh Administrator.
                                            @if (!$reg->isAccepted() && !$reg->isRejected())
                                                Silakan berikan keputusan terlebih dahulu melalui tombol <a href="{{ route('admin.applications.review', $reg->id) }}" class="fw-semibold text-primary text-decoration-none">Review Pendaftaran</a>.
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Profil Peserta --}}
        <div class="col-lg-4 align-self-start">
            <div class="card shadow-sm border rounded-2 mb-3 profile-sidebar-card">
                <div class="card-header bg-light border-0 py-2 px-3">
                    <h2 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                        <i class="bi bi-person-badge text-primary me-1.5"></i>Profil Peserta
                    </h2>
                </div>
                <div class="card-body p-3 sm:p-4 text-center">
                    @if ($profil && $profil->foto_url)
                        <img src="{{ $profil->foto_url }}"
                             alt="Foto {{ $reg->user?->name }}"
                             class="profile-avatar-img border shadow-xs"
                             style="width: 80px; height: 80px; min-width: 80px; min-height: 80px;"
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($reg->user?->name ?? 'User') }}&size=80&background=0d6efd&color=fff&bold=true';">
                    @else
                        <div class="profile-avatar-placeholder bg-primary bg-opacity-10 text-primary border border-primary-subtle shadow-xs" style="width: 80px; height: 80px; min-width: 80px; min-height: 80px; font-size: 1.5rem;">
                            {{ mb_substr($reg->user?->name ?? '?', 0, 1) }}
                        </div>
                    @endif

                    <div class="mb-1.5">
                        <span class="badge {{ $profil?->isSiswa() ? 'bg-success bg-opacity-10 text-success border border-success-subtle' : 'bg-primary bg-opacity-10 text-primary border border-primary-subtle' }} rounded-pill px-2.5 py-0.5 text-xs fw-bold">
                            {{ $profil ? ($profil->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa') : 'Belum Ada Profil' }}
                        </span>
                    </div>

                    <h3 class="text-xs font-bold mb-0 text-slate-800 dark:text-slate-100 text-break" style="word-break: break-word; overflow-wrap: anywhere;">{{ $reg->user?->name ?? '—' }}</h3>
                    <div class="text-muted text-xs mb-3 text-break" style="word-break: break-word; overflow-wrap: anywhere;">{{ $reg->user?->email ?? '' }}</div>

                    <ul class="list-group list-group-flush text-start profile-info-list border-top text-xs">
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Kategori</span>
                            <span class="profile-info-value fw-semibold text-xs">{{ $profil?->participantTypeLabel() ?? '-' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">NIK</span>
                            <span class="profile-info-value font-monospace text-xs">{{ $profil?->nik ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">{{ $profil?->institutionLabel() ?? 'Institusi' }}</span>
                            <span class="profile-info-value fw-bold text-primary text-xs">{{ $profil?->institusi ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">{{ $profil?->numberLabel() ?? 'NIM / NIS' }}</span>
                            <span class="profile-info-value font-monospace fw-semibold text-xs">{{ $profil?->numberValue() ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">{{ $profil?->majorLabel() ?? 'Jurusan' }}</span>
                            <span class="profile-info-value fw-semibold text-xs">{{ $profil?->jurusan ?? '—' }}</span>
                        </li>
                        @if($profil && $profil->isMahasiswa())
                            <li class="list-group-item py-1 px-0">
                                <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Semester</span>
                                <span class="profile-info-value text-xs">
                                    <span class="badge bg-light text-body border rounded-pill px-2 py-0.5 text-xs">Semester {{ $profil->semester ?? '-' }}</span>
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Tahun Angkatan</span>
                            <span class="profile-info-value text-xs">{{ $profil?->tahun_angkatan ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Nomor WhatsApp</span>
                            <span class="profile-info-value text-xs">{{ $profil?->no_telepon ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Jenis Kelamin</span>
                            <span class="profile-info-value text-xs">{{ $profil?->jenis_kelamin?->label() ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Tempat & Tgl Lahir</span>
                            <span class="profile-info-value text-xs">{{ $profil?->tempat_lahir ?? '—' }}@if($profil?->tanggal_lahir), {{ $profil->tanggal_lahir->format('d/m/Y') }}@endif</span>
                        </li>
                        <li class="list-group-item py-1 px-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">Alamat Domisili</span>
                            <span class="profile-info-value text-xs">{{ $profil?->alamat ?? '—' }}</span>
                        </li>
                        <li class="list-group-item py-1 px-0 border-bottom-0">
                            <span class="profile-info-label text-xs font-bold !text-slate-800 dark:!text-slate-200">ID Pengguna</span>
                            <span class="profile-info-value font-monospace text-xs text-muted">#{{ $reg->user?->id ?? '?' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
