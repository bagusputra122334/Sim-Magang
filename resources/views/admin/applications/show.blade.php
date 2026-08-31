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
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 fw-bold mb-0 text-body d-flex align-items-center gap-2 flex-wrap">
                <i class="bi bi-file-earmark-text text-primary"></i> Detail Pendaftaran
                <span class="badge bg-light text-primary border font-monospace fs-6 px-2.5 py-1">
                    {{ $reg->nomor_pendaftaran }}
                </span>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.applications.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            @if (! $reg->isAccepted() && ! $reg->isRejected())
                <a href="{{ route('admin.applications.review', $reg->id) }}" class="btn bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-sm">
                    <i class="bi bi-pencil-square me-1"></i> Berikan Keputusan (Review)
                </a>
            @endif
        </div>
    </div>

    {{-- Business Rule #1 Alert UnderReview Auto --}}
    @if ($berubahJadiUnderReview ?? false)
        <div class="alert alert-info alert-dismissible fade show mb-4 shadow-sm border" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-5 me-2 flex-shrink-0"></i>
                <div class="small">
                    <b>Status Otomatis Diperbarui:</b> Pendaftaran ini otomatis beralih dari
                    <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle">Submitted</span> ke
                    <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle">Under Review</span>
                    karena dibuka oleh Administrator untuk pertama kalinya.
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Flash Notifications --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border shadow-sm mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {!! session('success') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {!! session('warning') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border shadow-sm mb-4">
            <i class="bi bi-x-octagon-fill me-2"></i> {!! session('error') !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- KOLOM KIRI: Informasi Utama & Alur Dokumen --}}
        <div class="col-lg-8">
            {{-- 1. INFORMASI PENDAFTARAN --}}
            <div class="card shadow-sm border mb-4 rounded-4">
                <div class="card-header bg-light border-0 py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h2 class="h5 mb-0 fw-bold text-body">
                        <i class="bi bi-journal-text text-primary me-2"></i>Informasi Pendaftaran
                    </h2>
                    <span class="badge rounded-pill bg-{{ $badgeColor }} px-3 py-1.5 fs-6 shadow-xs">
                        {{ $reg->status->label() }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <dl class="row mb-0 g-3">
                        <dt class="col-sm-4 text-muted fw-normal small">Nomor Pendaftaran</dt>
                        <dd class="col-sm-8 fw-bold font-monospace text-primary fs-6 mb-0">{{ $reg->nomor_pendaftaran }}</dd>

                        <dt class="col-sm-4 text-muted fw-normal small">Posisi Magang</dt>
                        <dd class="col-sm-8 fw-semibold mb-0">
                            {{ $reg->position?->nama_posisi ?? '—' }}
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal small">Pembimbing Lapangan</dt>
                        <dd class="col-sm-8 mb-0">
                            @if($reg->position?->mentor_name)
                                <div class="fw-semibold text-body">
                                    <i class="bi bi-person-badge text-primary me-1"></i>
                                    {{ $reg->position->mentor_name }}
                                </div>
                                @if($reg->position->mentor_nip)
                                    <div class="text-muted small font-monospace">NIP: {{ $reg->position->mentor_nip }}</div>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal small">Periode Pelaksanaan</dt>
                        <dd class="col-sm-8 mb-0">
                            <div class="fw-semibold">
                                {{ optional($reg->periode_mulai)->format('d F Y') ?? '—' }}
                                <span class="text-muted mx-1">s/d</span>
                                {{ optional($reg->periode_selesai)->format('d F Y') ?? '—' }}
                            </div>
                            @if($reg->periode_label)
                                <div class="text-muted small mt-0.5">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $reg->periode_label }}
                                </div>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal small">Waktu Pengajuan (Submit)</dt>
                        <dd class="col-sm-8 text-secondary mb-0">
                            {{ optional($reg->tanggal_submit)->translatedFormat('d F Y • H:i') ?? '—' }} WIB
                        </dd>

                        <dt class="col-sm-4 text-muted fw-normal small">Status Verifikasi</dt>
                        <dd class="col-sm-8 mb-0">
                            <span class="badge bg-{{ $badgeColor }} px-2.5 py-1 rounded-pill">
                                {{ $reg->status->label() }}
                            </span>
                            @if ($reg->updated_at && optional($reg->tanggal_submit)->format('c') !== $reg->updated_at->format('c'))
                                <span class="small text-muted ms-2">
                                    (Terakhir diperbarui: {{ $reg->updated_at->format('d/m/Y H:i') }})
                                </span>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>

            {{-- 2. CATATAN VERIFIKASI ADMIN (JIKA ADA) --}}
            @if ($reg->catatan_admin)
                <div class="card shadow-sm border mb-4 rounded-4 border-{{ $reg->isRejected() ? 'danger' : ($reg->isAccepted() ? 'success' : 'info') }}">
                    <div class="card-header bg-{{ $reg->isRejected() ? 'danger' : ($reg->isAccepted() ? 'success' : 'info') }} bg-opacity-10 py-3 px-4 border-0">
                        <h3 class="h6 mb-0 fw-bold text-{{ $reg->isRejected() ? 'danger' : ($reg->isAccepted() ? 'success' : 'info') }}">
                            <i class="bi bi-chat-left-quote-fill me-2"></i>Catatan Verifikasi Administrator
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="mb-0 text-body lh-base" style="white-space: pre-wrap;">{{ $reg->catatan_admin }}</p>
                    </div>
                </div>
            @endif

            {{-- 3. DOKUMEN PERSYARATAN PESERTA (COMPACT 3-COLUMN IN ONE ROW ON DESKTOP) --}}
            <div class="card shadow-sm border mb-4 rounded-4">
                <div class="card-header bg-light border-0 py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="h5 mb-0 fw-bold text-body">
                            <i class="bi bi-folder2-open text-primary me-2"></i>Dokumen Persyaratan
                        </h2>
                        <p class="text-muted small mb-0 mt-1">
                            Berkas persyaratan yang diunggah oleh peserta saat mengajukan pendaftaran magang.
                        </p>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        {{-- Dokumen 1: Curriculum Vitae --}}
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="doc-item-card">
                                <div class="doc-card-header">
                                    <div class="doc-icon-wrapper {{ $cvUrl ? 'active' : 'inactive' }}">
                                        <i class="bi bi-person-lines-fill"></i>
                                    </div>
                                    <div class="doc-card-title-group">
                                        <h4 class="doc-card-title">Curriculum Vitae</h4>
                                        <p class="doc-card-desc">Biodata & Riwayat Hidup</p>
                                    </div>
                                </div>

                                <div class="doc-file-box">
                                    @if ($cvUrl)
                                        <div class="file-meta-pill" title="{{ basename($reg->cv_path) }}">
                                            <i class="bi bi-filetype-pdf text-danger flex-shrink-0"></i>
                                            <span>{{ basename($reg->cv_path) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning small mb-0 py-1.5 px-2.5">
                                            <i class="bi bi-exclamation-triangle me-1"></i> CV belum di-upload oleh peserta.
                                        </div>
                                    @endif
                                </div>

                                <div class="doc-card-actions">
                                    @if ($cvUrl)
                                        <a href="{{ $cvUrl }}" target="_blank" rel="noopener"
                                           class="btn btn-doc-preview" title="Preview Curriculum Vitae">
                                            <i class="bi bi-box-arrow-up-right"></i> Preview
                                        </a>
                                        <a href="{{ $cvUrl }}" download
                                           class="btn btn-doc-download" title="Download Curriculum Vitae">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @else
                                        <div class="btn-doc-empty">
                                            <i class="bi bi-dash-circle"></i> Berkas Kosong
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Dokumen 2: Surat Pengantar / Rekomendasi --}}
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="doc-item-card">
                                <div class="doc-card-header">
                                    <div class="doc-icon-wrapper {{ $suratPengantarUrl ? 'active' : 'inactive' }}">
                                        <i class="bi bi-envelope-paper"></i>
                                    </div>
                                    <div class="doc-card-title-group">
                                        <h4 class="doc-card-title">Surat Pengantar / Rekomendasi</h4>
                                        <p class="doc-card-desc">Rekomendasi Kampus / Sekolah</p>
                                    </div>
                                </div>

                                <div class="doc-file-box">
                                    @if ($suratPengantarUrl)
                                        <div class="file-meta-pill" title="{{ basename($reg->surat_pengantar_path) }}">
                                            <i class="bi bi-filetype-pdf text-danger flex-shrink-0"></i>
                                            <span>{{ basename($reg->surat_pengantar_path) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning small mb-0 py-1.5 px-2.5">
                                            <i class="bi bi-exclamation-triangle me-1"></i> Surat pengantar belum di-upload peserta.
                                        </div>
                                    @endif
                                </div>

                                <div class="doc-card-actions">
                                    @if ($suratPengantarUrl)
                                        <a href="{{ $suratPengantarUrl }}" target="_blank" rel="noopener"
                                           class="btn btn-doc-preview" title="Preview Surat Pengantar">
                                            <i class="bi bi-box-arrow-up-right"></i> Preview
                                        </a>
                                        <a href="{{ $suratPengantarUrl }}" download
                                           class="btn btn-doc-download" title="Download Surat Pengantar">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @else
                                        <div class="btn-doc-empty">
                                            <i class="bi bi-dash-circle"></i> Berkas Kosong
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Dokumen 3: Proposal Magang --}}
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="doc-item-card">
                                <div class="doc-card-header">
                                    <div class="doc-icon-wrapper {{ !empty($proposalMagangUrl) ? 'active' : 'inactive' }}">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </div>
                                    <div class="doc-card-title-group">
                                        <h4 class="doc-card-title">Proposal Magang</h4>
                                        <p class="doc-card-desc">Rencana & Program Kerja</p>
                                    </div>
                                </div>

                                <div class="doc-file-box">
                                    @if (!empty($proposalMagangUrl))
                                        <div class="file-meta-pill" title="{{ basename($reg->proposal_magang_path) }}">
                                            <i class="bi bi-filetype-pdf text-danger flex-shrink-0"></i>
                                            <span>{{ basename($reg->proposal_magang_path) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning small mb-0 py-1.5 px-2.5">
                                            <i class="bi bi-exclamation-triangle me-1"></i> Proposal magang belum di-upload peserta.
                                        </div>
                                    @endif
                                </div>

                                <div class="doc-card-actions">
                                    @if (!empty($proposalMagangUrl))
                                        <a href="{{ $proposalMagangUrl }}" target="_blank" rel="noopener"
                                           class="btn btn-doc-preview" title="Preview Proposal Magang">
                                            <i class="bi bi-box-arrow-up-right"></i> Preview
                                        </a>
                                        <a href="{{ $proposalMagangUrl }}" download
                                           class="btn btn-doc-download" title="Download Proposal Magang">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    @else
                                        <div class="btn-doc-empty">
                                            <i class="bi bi-dash-circle"></i> Berkas Kosong
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. SURAT BALASAN RESMI (DOKUMEN ADMINISTRASI DINAS) --}}
            <div class="card shadow-sm border mb-4 rounded-4 overflow-hidden">
                <div class="card-header bg-light border-0 py-3 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h2 class="h5 mb-0 fw-bold text-body">
                            <i class="bi bi-file-earmark-check text-success me-2"></i>Surat Balasan
                        </h2>
                        <p class="text-muted small mb-0 mt-1">
                            Unggah surat balasan resmi untuk peserta setelah proses verifikasi selesai.
                        </p>
                    </div>
                    @if ($sbExists)
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-semibold small">
                            <i class="bi bi-check-circle-fill me-1"></i> Surat Telah Terbit
                        </span>
                    @elseif ($reg->isAccepted())
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle px-3 py-1.5 rounded-pill fw-semibold small">
                            <i class="bi bi-clock-history me-1"></i> Belum Diunggah
                        </span>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle px-3 py-1.5 rounded-pill fw-semibold small">
                            <i class="bi bi-lock-fill me-1"></i> Belum Tersedia
                        </span>
                    @endif
                </div>

                <div class="card-body p-4">
                    {{-- STATE A: SUDAH DIUNGGAH --}}
                    @if ($sbExists)
                        <div class="reply-letter-card state-uploaded p-4">
                            <div class="d-flex align-items-start gap-3 flex-wrap flex-md-nowrap mb-3">
                                <div class="p-3 rounded-3 bg-success bg-opacity-10 text-success border border-success-subtle d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-file-earmark-check-fill fs-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h4 class="h5 fw-bold mb-0 text-body">Surat Balasan</h4>
                                        <span class="badge bg-success text-white px-2 py-0.5 rounded-pill small">Sudah Diunggah</span>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Surat balasan resmi telah diterbitkan dan tersedia untuk diunduh oleh peserta melalui portal pendaftaran mereka.
                                    </p>

                                    <div class="d-flex flex-wrap gap-2 mb-3">
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

                            <div class="d-flex flex-wrap gap-2 pt-3 border-top justify-content-start align-items-center">
                                <a href="{{ $sbUrl }}" target="_blank" rel="noopener"
                                   class="btn btn-outline-primary px-3 shadow-xs">
                                    <i class="bi bi-eye me-1.5"></i> Preview
                                </a>
                                <a href="{{ route('admin.applications.reply-letter.download', $reg->id) }}"
                                   class="btn btn-secondary px-3 shadow-sm fw-semibold">
                                    <i class="bi bi-download me-1.5"></i> Download
                                </a>
                                <a href="{{ route('admin.applications.reply-letter', $reg->id) }}"
                                   class="btn btn-outline-secondary px-3 ms-auto">
                                    <i class="bi bi-arrow-repeat me-1.5"></i> Ganti Surat
                                </a>
                            </div>
                        </div>

                    {{-- STATE B: BELUM DIUNGGAH TAPI STATUS ACCEPTED --}}
                    @elseif ($reg->isAccepted())
                        <div class="reply-letter-card state-pending p-4 text-center text-md-start">
                            <div class="row align-items-center g-3">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start gap-3 flex-wrap flex-md-nowrap">
                                        <div class="p-3 rounded-3 bg-primary bg-opacity-10 text-primary border border-primary-subtle d-inline-flex align-items-center justify-content-center flex-shrink-0 mx-auto mx-md-0">
                                            <i class="bi bi-cloud-arrow-up-fill fs-2"></i>
                                        </div>
                                        <div>
                                            <h4 class="h5 fw-bold mb-1 text-body">Surat Balasan</h4>
                                            <p class="text-muted small mb-2">
                                                Belum ada surat balasan yang diunggah untuk pendaftaran ini.
                                            </p>
                                            <div class="text-muted small">
                                                <i class="bi bi-info-circle me-1 text-primary"></i> Format PDF, maksimal sesuai ketentuan sistem.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center text-md-end">
                                    <a href="{{ route('admin.applications.reply-letter', $reg->id) }}"
                                       class="btn bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5 w-100 w-md-auto">
                                        <i class="bi bi-upload me-2"></i> Unggah Surat Balasan
                                    </a>
                                </div>
                            </div>
                        </div>

                    {{-- STATE C: BELUM TERSEDIA KARENA STATUS BELUM ACCEPTED --}}
                    @else
                        <div class="reply-letter-card state-disabled p-4 text-muted">
                            <div class="d-flex align-items-start gap-3">
                                <div class="p-3 rounded-3 bg-secondary bg-opacity-10 text-secondary border border-secondary-subtle d-inline-flex align-items-center justify-content-center flex-shrink-0">
                                    <i class="bi bi-shield-lock-fill fs-2"></i>
                                </div>
                                <div>
                                    <h4 class="h6 fw-bold mb-1 text-body">Surat Balasan</h4>
                                    @if ($reg->isRejected())
                                        <p class="small mb-0 text-danger">
                                            <i class="bi bi-x-circle me-1"></i> Pendaftaran ini berstatus <b>Ditolak (Rejected)</b>. Surat balasan penerimaan magang tidak diterbitkan.
                                        </p>
                                    @else
                                        <p class="small mb-0 text-muted">
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
            <div class="card shadow-sm border rounded-4 mb-4 profile-sidebar-card">
                <div class="card-header bg-light border-0 py-3 px-4">
                    <h2 class="h5 mb-0 fw-bold text-body">
                        <i class="bi bi-person-badge text-primary me-2"></i>Profil Peserta
                    </h2>
                </div>
                <div class="card-body p-4 text-center">
                    @if ($profil && $profil->foto_url)
                        <img src="{{ $profil->foto_url }}"
                             alt="Foto {{ $reg->user?->name }}"
                             class="profile-avatar-img border shadow-xs"
                             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($reg->user?->name ?? 'User') }}&size=110&background=0d6efd&color=fff&bold=true';">
                    @else
                        <div class="profile-avatar-placeholder bg-primary bg-opacity-10 text-primary border border-primary-subtle shadow-xs">
                            {{ mb_substr($reg->user?->name ?? '?', 0, 1) }}
                        </div>
                    @endif

                    <div class="mb-2">
                        <span class="badge {{ $profil?->isSiswa() ? 'bg-success bg-opacity-10 text-success border border-success-subtle' : 'bg-primary bg-opacity-10 text-primary border border-primary-subtle' }} rounded-pill px-3 py-1.5 fw-bold">
                            {{ $profil ? ($profil->isSiswa() ? '🏫 Siswa / SMK' : '🎓 Mahasiswa') : 'Belum Ada Profil' }}
                        </span>
                    </div>

                    <h3 class="h5 fw-bold mb-0 text-body text-break" style="word-break: break-word; overflow-wrap: anywhere;">{{ $reg->user?->name ?? '—' }}</h3>
                    <div class="text-muted small mb-4 text-break" style="word-break: break-word; overflow-wrap: anywhere;">{{ $reg->user?->email ?? '' }}</div>

                    <ul class="list-group list-group-flush text-start profile-info-list border-top">
                        <li class="list-group-item">
                            <span class="profile-info-label">Kategori</span>
                            <span class="profile-info-value fw-semibold">{{ $profil?->participantTypeLabel() ?? '-' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">NIK</span>
                            <span class="profile-info-value font-monospace">{{ $profil?->nik ?? '—' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">{{ $profil?->institutionLabel() ?? 'Institusi' }}</span>
                            <span class="profile-info-value fw-bold text-primary">{{ $profil?->institusi ?? '—' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">{{ $profil?->numberLabel() ?? 'NIM / NIS' }}</span>
                            <span class="profile-info-value font-monospace fw-semibold">{{ $profil?->numberValue() ?? '—' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">{{ $profil?->majorLabel() ?? 'Jurusan' }}</span>
                            <span class="profile-info-value fw-semibold">{{ $profil?->jurusan ?? '—' }}</span>
                        </li>
                        @if($profil && $profil->isMahasiswa())
                            <li class="list-group-item">
                                <span class="profile-info-label">Semester</span>
                                <span class="profile-info-value">
                                    <span class="badge bg-light text-body border rounded-pill px-2.5 py-1">Semester {{ $profil->semester ?? '-' }}</span>
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item">
                            <span class="profile-info-label">Tahun Angkatan</span>
                            <span class="profile-info-value">{{ $profil?->tahun_angkatan ?? '—' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">Nomor WhatsApp</span>
                            <span class="profile-info-value">{{ $profil?->no_telepon ?? '—' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">Jenis Kelamin</span>
                            <span class="profile-info-value">{{ $profil?->jenis_kelamin?->label() ?? '—' }}</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">Tempat & Tgl Lahir</span>
                            <span class="profile-info-value">{{ $profil?->tempat_lahir ?? '—' }}@if($profil?->tanggal_lahir), {{ $profil->tanggal_lahir->format('d/m/Y') }}@endif</span>
                        </li>
                        <li class="list-group-item">
                            <span class="profile-info-label">Alamat Domisili</span>
                            <span class="profile-info-value">{{ $profil?->alamat ?? '—' }}</span>
                        </li>
                        <li class="list-group-item border-bottom-0">
                            <span class="profile-info-label">ID Pengguna</span>
                            <span class="profile-info-value font-monospace small text-muted">#{{ $reg->user?->id ?? '?' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
