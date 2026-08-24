@extends('layouts.participant')
@section('title', 'Detail Pendaftaran — ' . ($reg->nomor_pendaftaran ?? ''))

@php
    function statusBadgeClass_($sv): string {
        return match($sv) {
            'submitted'   => 'bg-primary-subtle text-primary-emphasis border-primary-subtle',
            'under_review' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
            'accepted'    => 'bg-success-subtle text-success-emphasis border-success-subtle',
            'rejected'    => 'bg-danger-subtle text-danger-emphasis border-danger-subtle',
            default       => 'bg-secondary-subtle text-secondary-emphasis border-secondary-subtle',
        };
    }
    function statusBadgeIcon_($sv): string {
        return match($sv) {
            'submitted'   => '<i class="bi bi-send-check-fill me-1.5"></i>',
            'under_review' => '<i class="bi bi-hourglass-split me-1.5"></i>',
            'accepted'    => '<i class="bi bi-check-circle-fill me-1.5"></i>',
            'rejected'    => '<i class="bi bi-x-circle-fill me-1.5"></i>',
            default       => '',
        };
    }
    $sv = $reg->status->value;
@endphp

@push('styles')
<style>
    .doc-attachment-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    @media (max-width: 991.98px) {
        .doc-attachment-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 575.98px) {
        .doc-attachment-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    .doc-attachment-card {
        background-color: var(--app-surface, #ffffff);
        border: 1px solid var(--app-border, #dee2e6);
        border-radius: 14px;
        padding: 1.15rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        width: 100%;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }

    .doc-attachment-card:hover {
        border-color: var(--app-primary, #0d6efd);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.08);
    }

    .doc-attachment-card-top {
        min-width: 0;
        width: 100%;
    }

    .doc-attachment-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        min-width: 0;
        width: 100%;
    }

    .doc-attachment-icon {
        width: 42px;
        height: 42px;
        min-width: 42px;
        min-height: 42px;
        border-radius: 10px;
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .doc-attachment-meta {
        min-width: 0;
        flex: 1 1 0;
    }

    .doc-attachment-title {
        font-weight: 700;
        font-size: 0.875rem;
        line-height: 1.25;
        margin: 0 0 0.15rem;
        color: var(--app-text, #212529);
        word-break: break-word;
        overflow-wrap: anywhere;
    }

    .doc-attachment-subtitle {
        font-size: 0.725rem;
        color: var(--app-text-muted, #6c757d);
        display: block;
        line-height: 1.2;
    }

    .doc-attachment-filepill {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem 0.55rem;
        border-radius: 8px;
        background-color: var(--app-surface-soft, #f8f9fa);
        border: 1px solid var(--app-border, #e9ecef);
        font-family: var(--bs-font-monospace, monospace);
        font-size: 0.725rem;
        color: var(--app-text-secondary, #495057);
        margin-bottom: 0.85rem;
        min-width: 0;
        width: 100%;
        box-sizing: border-box;
        overflow: hidden;
    }

    .doc-attachment-filepill span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        min-width: 0;
        flex: 1 1 0;
    }

    .doc-attachment-card-bottom {
        margin-top: auto;
        padding-top: 0.75rem;
        border-top: 1px solid var(--app-border-subtle, var(--app-border, #e9ecef));
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .doc-attachment-btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.45rem;
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
    }

    .doc-attachment-btn-group .btn {
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.35rem 0.65rem;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .gap-2\.5 {
        gap: 0.625rem !important;
    }
    .me-1\.5 {
        margin-right: 0.375rem !important;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-lg-11">


        {{-- Hero Header: Clean, High Contrast, Government Digital Standard --}}
        <div class="card shadow-sm border rounded-4 overflow-hidden mb-4 bg-body">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center g-3">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 font-monospace small">
                                <i class="bi bi-ticket-perforated-fill me-1"></i>{{ $reg->nomor_pendaftaran }}
                            </span>
                            <span class="badge bg-body-tertiary text-body-secondary border rounded-pill px-2.5 py-1 small">
                                <i class="bi bi-clock-history me-1"></i>{{ $reg->tanggal_submit?->translatedFormat('d M Y, H:i') }} WIB
                            </span>
                        </div>
                        <h1 class="h3 fw-bold mb-1 text-body">
                            {{ $reg->position?->nama_posisi ?? 'Detail Pendaftaran Magang' }}
                        </h1>
                        <p class="text-muted mb-0 small">
                            <i class="bi bi-geo-alt me-1"></i>Dinas Komunikasi, Informatika, Statistik dan Persandian Kab. Tuban
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-inline-flex flex-column align-items-md-end gap-1">
                            <span class="badge rounded-pill px-3.5 py-2 fs-6 border {!! statusBadgeClass_($sv) !!}" style="cursor: default; user-select: none;">
                                {!! statusBadgeIcon_($sv) !!}{{ $reg->status->label() }}
                            </span>
                            <div class="small text-muted mt-1">
                                Status Pengajuan Pendaftaran
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($sv === 'rejected' && !empty(trim($reg->catatan_admin ?? '')))
            <div class="alert alert-danger border-2 rounded-4 mb-4 d-flex align-items-start" role="alert">
                <i class="bi bi-file-earmark-x-fill fs-3 text-danger me-3 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h5 class="alert-heading fw-semibold mb-1 text-danger">Catatan Verifikasi Administrator (Alasan Penolakan)</h5>
                    <p class="mb-0 lh-base text-body">{{ $reg->catatan_admin }}</p>
                </div>
            </div>
        @endif

        @if($reg->isAccepted())
            @php
                $suratAda = !empty($reg->surat_balasan_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($reg->surat_balasan_path);
                $suratFileInfo = null;
                if ($suratAda) {
                    $disk = \Illuminate\Support\Facades\Storage::disk('public');
                    $bytes = $disk->size($reg->surat_balasan_path);
                    $sizeKb = (int) round($bytes / 1024);
                    $suratFileInfo = [
                        'basename'      => basename($reg->surat_balasan_path),
                        'size_kb'       => $sizeKb,
                        'human_size'    => number_format($sizeKb, 0, ',', '.').' KB',
                        'last_modified' => date('d M Y H:i', $disk->lastModified($reg->surat_balasan_path)),
                    ];
                }
            @endphp
            <div class="card border-{{ $suratAda ? 'success' : 'warning' }} border-2 shadow-sm rounded-4 overflow-hidden mb-4 bg-body">
                <div class="card-header bg-{{ $suratAda ? 'success' : 'warning' }} bg-opacity-10 border-0 py-3.5 px-4 px-md-5 d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-bold text-{{ $suratAda ? 'success' : 'warning' }}-emphasis">
                            <i class="bi bi-envelope-paper-fill me-2"></i>
                            Surat Balasan Resmi — Dinas Komunikasi, Informatika, Statistik dan Persandian
                        </h5>
                        <p class="mb-0 mt-1 small text-{{ $suratAda ? 'success' : 'warning' }}-emphasis opacity-75">
                            @if($suratAda)
                                <i class="bi bi-check2-circle me-1"></i> Surat Balasan sudah diunggah Admin dan siap diunduh.
                            @else
                                <i class="bi bi-hourglass-split me-1"></i> Menunggu Admin mengunggah Surat Balasan.
                            @endif
                        </p>
                    </div>
                    <span class="badge rounded-pill bg-{{ $suratAda ? 'success' : 'warning' }} px-3 py-2 fs-6">
                        @if($suratAda)
                            <i class="bi bi-cloud-check-fill me-1"></i> SIAP DIUNDUH
                        @else
                            <i class="bi bi-clock-history me-1"></i> MENUNGGU UPLOAD
                        @endif
                    </span>
                </div>
                <div class="card-body px-4 px-md-5 py-4">
                    @if($suratAda && $suratFileInfo)
                        <div class="row g-3 align-items-center">
                            <div class="col-lg-7">
                                <dl class="row mb-0 small">
                                    <dt class="col-sm-4 text-muted">Nomor Pendaftaran</dt>
                                    <dd class="col-sm-8 fw-bold font-monospace text-success mb-1">{{ $reg->nomor_pendaftaran }}</dd>

                                    <dt class="col-sm-4 text-muted">Nama File</dt>
                                    <dd class="col-sm-8 font-monospace mb-1">{{ $suratFileInfo['basename'] }}</dd>

                                    <dt class="col-sm-4 text-muted">Ukuran File</dt>
                                    <dd class="col-sm-8 mb-1">{{ $suratFileInfo['human_size'] }} ({{ $suratFileInfo['size_kb'] }} KB)</dd>

                                    <dt class="col-sm-4 text-muted">Diunggah pada</dt>
                                    <dd class="col-sm-8 mb-0">{{ $suratFileInfo['last_modified'] }} WIB</dd>
                                </dl>
                            </div>
                            <div class="col-lg-5">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('participant.applications.reply-letter.download', $reg->id) }}"
                                       class="btn btn-success btn-lg fw-semibold shadow-sm d-inline-flex align-items-center justify-content-center"
                                       style="min-height: 48px;">
                                        <i class="bi bi-file-earmark-pdf-fill me-2 fs-5"></i>
                                        Download Surat Balasan (PDF)
                                    </a>
                                    <div class="small text-muted text-center">
                                        <i class="bi bi-shield-check me-1"></i>
                                        File resmi dari Diskominfo SP Tuban, gunakan sebagai bukti penerimaan magang.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <div class="flex-grow-1">
                                <p class="mb-0 small lh-lg text-body">
                                    <i class="bi bi-info-circle me-1 text-warning"></i>
                                    Pendaftaran Anda sudah dinyatakan <b>DITERIMA</b>, namun Surat Balasan resmi dari Dinas Komunikasi, Informatika, Statistik dan Persandian
                                    Kabupaten Tuban <b>belum diunggah</b> oleh tim Admin. Silakan periksa kembali secara berkala atau hubungi Admin jika
                                    membutuhkan informasi lebih lanjut.
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="display-5 text-warning opacity-50">
                                    <i class="bi bi-envelope-open"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <div class="row g-4">
            {{-- Main Column (Informasi & Lampiran) --}}
            <div class="col-lg-8">
                {{-- 1. INFORMASI PENDAFTARAN --}}
                <div class="card shadow-sm border rounded-4 overflow-hidden mb-4 bg-body">
                    <div class="card-header bg-light border-0 py-3.5 px-4 px-md-5">
                        <h5 class="mb-0 fw-bold text-body">
                            <i class="bi bi-card-checklist text-primary me-2"></i>Informasi Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Nama Peserta</label>
                                <div class="fs-6 fw-semibold text-body">{{ $reg->user?->name ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Email Peserta</label>
                                <div class="fs-6 fw-semibold text-body">{{ $reg->user?->email ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Posisi Magang</label>
                                <div class="fs-6 fw-bold text-primary">{{ $reg->position?->nama_posisi ?? '—' }}</div>
                                <div class="small text-muted mt-1"><i class="bi bi-hash me-1"></i>Slug: {{ $reg->position?->slug ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Status Posisi</label>
                                <div class="small">
                                    <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-3 py-1.5 fw-semibold"><i class="bi bi-check-circle-fill me-1"></i>Posisi Terbuka</span>
                                </div>
                            </div>

                            <div class="col-12 my-1">
                                <hr class="my-2 border-secondary-subtle">
                            </div>

                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Periode Mulai Magang</label>
                                <div class="fs-6 fw-semibold text-body"><i class="bi bi-calendar-plus text-primary me-2"></i>{{ $reg->periode_mulai?->translatedFormat('l, d F Y') ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Periode Selesai Magang</label>
                                <div class="fs-6 fw-semibold text-body"><i class="bi bi-calendar2-check text-primary me-2"></i>{{ $reg->periode_selesai?->translatedFormat('l, d F Y') ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Durasi Magang</label>
                                <div class="fs-6 fw-semibold text-primary">
                                    <i class="bi bi-hourglass-split me-1"></i>
                                    {{ $reg->periode_mulai && $reg->periode_selesai ? ($reg->periode_mulai->diffInDays($reg->periode_selesai) + 1).' Hari' : '—' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small fw-semibold text-uppercase mb-1 d-block">Tanggal Submit</label>
                                <div class="fs-6 fw-semibold text-body"><i class="bi bi-send-check text-primary me-2"></i>{{ $reg->tanggal_submit?->translatedFormat('d M Y — H:i') }} WIB</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. DESKRIPSI POSISI (JIKA ADA) --}}
                @if(!empty($reg->position?->deskripsi ?? null))
                <div class="card shadow-sm border rounded-4 overflow-hidden mb-4 bg-body">
                    <div class="card-header bg-light border-0 py-3.5 px-4 px-md-5">
                        <h5 class="mb-0 fw-bold text-body">
                            <i class="bi bi-info-square text-primary me-2"></i>Deskripsi Posisi Magang
                        </h5>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="lh-base fs-6 text-body">{!! nl2br(e($reg->position?->deskripsi ?? '')) !!}</div>
                    </div>
                </div>
                @endif

                {{-- 3. LAMPIRAN DOKUMEN PENDAFTARAN --}}
                <div class="card shadow-sm border rounded-4 overflow-hidden mb-4 mb-lg-0 bg-body">
                    <div class="card-header bg-light border-0 py-3.5 px-4 px-md-5 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="mb-0 fw-bold text-body">
                                <i class="bi bi-folder2-open text-primary me-2"></i>Lampiran Dokumen Pendaftaran
                            </h5>
                            <p class="text-muted small mb-0 mt-0.5">
                                Berkas dokumen persyaratan yang diunggah saat pengajuan pendaftaran magang.
                            </p>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @php
                            $cvUrl = $dokumenUrl['cv'] ?? (!empty($reg->cv_path) ? \Illuminate\Support\Facades\Storage::disk('public')->url($reg->cv_path) : null);
                            $suratPengantarUrl = $dokumenUrl['surat_pengantar'] ?? (!empty($reg->surat_pengantar_path) ? \Illuminate\Support\Facades\Storage::disk('public')->url($reg->surat_pengantar_path) : null);
                            $proposalMagangUrl = $dokumenUrl['proposal_magang'] ?? (!empty($reg->proposal_magang_path) ? \Illuminate\Support\Facades\Storage::disk('public')->url($reg->proposal_magang_path) : null);
                        @endphp

                        <div class="doc-attachment-grid">
                            {{-- 1. Curriculum Vitae (CV) --}}
                            <div class="doc-attachment-card">
                                <div class="doc-attachment-card-top">
                                    <div class="doc-attachment-header">
                                        <div class="doc-attachment-icon">
                                            <i class="bi bi-file-earmark-person-fill"></i>
                                        </div>
                                        <div class="doc-attachment-meta">
                                            <h6 class="doc-attachment-title">Curriculum Vitae (CV)</h6>
                                            <span class="doc-attachment-subtitle">Biodata & Riwayat Hidup</span>
                                        </div>
                                    </div>

                                    @if(!empty($reg->cv_path))
                                        <div class="doc-attachment-filepill" title="{{ basename($reg->cv_path) }}">
                                            <i class="bi bi-filetype-pdf text-danger flex-shrink-0"></i>
                                            <span>{{ basename($reg->cv_path) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-secondary small mb-3 py-2 px-2.5 text-center">
                                            <i class="bi bi-dash-circle me-1"></i>CV tidak tersedia.
                                        </div>
                                    @endif
                                </div>

                                <div class="doc-attachment-card-bottom">
                                    @if(!empty($cvUrl))
                                        <div class="doc-attachment-btn-group">
                                            <a href="{{ $cvUrl }}" target="_blank" rel="noopener"
                                               class="btn btn-outline-primary btn-sm fw-semibold"
                                               title="Lihat berkas PDF Curriculum Vitae">
                                                <i class="bi bi-box-arrow-up-right me-1.5"></i>Lihat PDF
                                            </a>
                                            <a href="{{ $cvUrl }}" download
                                               class="btn btn-primary btn-sm fw-semibold shadow-xs"
                                               title="Download berkas Curriculum Vitae">
                                                <i class="bi bi-download me-1.5"></i>Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="btn btn-sm btn-outline-secondary disabled w-100 py-2">
                                            <i class="bi bi-dash-circle me-1"></i>Belum Diunggah
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 2. Surat Pengantar --}}
                            <div class="doc-attachment-card">
                                <div class="doc-attachment-card-top">
                                    <div class="doc-attachment-header">
                                        <div class="doc-attachment-icon">
                                            <i class="bi bi-envelope-paper-fill"></i>
                                        </div>
                                        <div class="doc-attachment-meta">
                                            <h6 class="doc-attachment-title">Surat Pengantar</h6>
                                            <span class="doc-attachment-subtitle">Institusi / Sekolah</span>
                                        </div>
                                    </div>

                                    @if(!empty($reg->surat_pengantar_path))
                                        <div class="doc-attachment-filepill" title="{{ basename($reg->surat_pengantar_path) }}">
                                            <i class="bi bi-filetype-pdf text-danger flex-shrink-0"></i>
                                            <span>{{ basename($reg->surat_pengantar_path) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-secondary small mb-3 py-2 px-2.5 text-center">
                                            <i class="bi bi-dash-circle me-1"></i>Surat pengantar tidak tersedia.
                                        </div>
                                    @endif
                                </div>

                                <div class="doc-attachment-card-bottom">
                                    @if(!empty($suratPengantarUrl))
                                        <div class="doc-attachment-btn-group">
                                            <a href="{{ $suratPengantarUrl }}" target="_blank" rel="noopener"
                                               class="btn btn-outline-primary btn-sm fw-semibold"
                                               title="Lihat berkas PDF Surat Pengantar">
                                                <i class="bi bi-box-arrow-up-right me-1.5"></i>Lihat PDF
                                            </a>
                                            <a href="{{ $suratPengantarUrl }}" download
                                               class="btn btn-primary btn-sm fw-semibold shadow-xs"
                                               title="Download berkas Surat Pengantar">
                                                <i class="bi bi-download me-1.5"></i>Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="btn btn-sm btn-outline-secondary disabled w-100 py-2">
                                            <i class="bi bi-dash-circle me-1"></i>Belum Diunggah
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- 3. Proposal Magang --}}
                            <div class="doc-attachment-card">
                                <div class="doc-attachment-card-top">
                                    <div class="doc-attachment-header">
                                        <div class="doc-attachment-icon">
                                            <i class="bi bi-file-earmark-pdf-fill"></i>
                                        </div>
                                        <div class="doc-attachment-meta">
                                            <h6 class="doc-attachment-title">Proposal Magang</h6>
                                            <span class="doc-attachment-subtitle">Rencana / Kerangka Magang</span>
                                        </div>
                                    </div>

                                    @if(!empty($reg->proposal_magang_path))
                                        <div class="doc-attachment-filepill" title="{{ basename($reg->proposal_magang_path) }}">
                                            <i class="bi bi-filetype-pdf text-danger flex-shrink-0"></i>
                                            <span>{{ basename($reg->proposal_magang_path) }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-secondary small mb-3 py-2 px-2.5 text-center">
                                            <i class="bi bi-dash-circle me-1"></i>Proposal belum diunggah.
                                        </div>
                                    @endif
                                </div>

                                <div class="doc-attachment-card-bottom">
                                    @if(!empty($proposalMagangUrl))
                                        <div class="doc-attachment-btn-group">
                                            <a href="{{ $proposalMagangUrl }}" target="_blank" rel="noopener"
                                               class="btn btn-outline-primary btn-sm fw-semibold"
                                               title="Lihat berkas PDF Proposal Magang">
                                                <i class="bi bi-box-arrow-up-right me-1.5"></i>Lihat PDF
                                            </a>
                                            <a href="{{ $proposalMagangUrl }}" download
                                               class="btn btn-primary btn-sm fw-semibold shadow-xs"
                                               title="Download berkas Proposal Magang">
                                                <i class="bi bi-download me-1.5"></i>Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="btn btn-sm btn-outline-secondary disabled w-100 py-2">
                                            <i class="bi bi-dash-circle me-1"></i>Belum Diunggah
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column (Panel Aksi Pendaftaran) --}}
            <div class="col-lg-4 align-self-start">
                <div class="card shadow-sm border rounded-4 mb-4 sticky-top bg-body" style="top: 1rem;">
                    <div class="card-header bg-light border-0 py-3.5 px-4 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold text-body">
                            <i class="bi bi-sliders text-primary me-2"></i>Aksi Pendaftaran
                        </h5>
                    </div>
                    <div class="card-body p-4 d-grid gap-2.5">
                        @if($reg->isAccepted() && !empty($reg->surat_balasan_path) && \Illuminate\Support\Facades\Storage::disk('public')->exists($reg->surat_balasan_path))
                            <a href="{{ route('participant.applications.reply-letter.download', $reg->id) }}"
                               class="btn btn-success fw-semibold d-inline-flex align-items-center justify-content-center shadow-sm"
                               style="min-height: 44px;">
                                <i class="bi bi-file-earmark-pdf-fill me-2 fs-5"></i>Download Surat Balasan
                            </a>
                        @endif

                        @if($reg->dapatDiubah())
                            <a href="{{ route('participant.registrations.edit', $reg->id) }}"
                               class="btn btn-primary fw-semibold d-inline-flex align-items-center justify-content-center shadow-sm"
                               style="min-height: 44px;">
                                <i class="bi bi-pencil-square me-2"></i>Ubah Pendaftaran
                            </a>
                        @endif

                        @if($reg->dapatDihapus())
                            <form action="{{ route('participant.registrations.destroy', $reg->id) }}" method="POST"
                                  class="d-grid form-delete-registration-show">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        id="btnDeleteShow"
                                        data-nomor="{{ $reg->nomor_pendaftaran }}"
                                        class="btn btn-outline-danger fw-semibold d-inline-flex align-items-center justify-content-center"
                                        style="min-height: 44px;">
                                    <i class="bi bi-trash3-fill me-2"></i>Hapus Pendaftaran
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('participant.registrations.index') }}"
                           class="btn btn-outline-secondary fw-semibold d-inline-flex align-items-center justify-content-center"
                           style="min-height: 44px;">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Riwayat
                        </a>

                        <hr class="my-2 border-secondary-subtle">

                        <div class="p-3 rounded-3 bg-body-tertiary border small text-body-secondary lh-base">
                            <div class="fw-semibold text-body mb-1 d-flex align-items-center">
                                <i class="bi bi-shield-check text-success me-1.5"></i>Perlindungan Data:
                            </div>
                            <div>Dokumen CV, Surat Pengantar, dan Proposal Magang Anda disimpan secara aman pada server internal Diskominfo dan hanya dapat diakses oleh tim Admin yang berwenang.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.form-delete-registration-show').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = document.getElementById('btnDeleteShow');
            const nomor = btn?.getAttribute('data-nomor') || 'ini';
            const ok = confirm('Anda yakin menghapus pendaftaran '+nomor+'?\n\nSeluruh lampiran CV & Surat Pengantar juga akan ikut terhapus dan tidak dapat dikembalikan.');
            if (ok) this.submit();
        });
    });
});
</script>
@endpush

