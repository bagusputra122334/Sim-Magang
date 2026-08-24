@extends('layouts.admin')

@php
    $reg = $application;
    $statusColorMap = [
        \App\Enums\RegistrationStatus::Submitted->value   => 'primary',
        \App\Enums\RegistrationStatus::UnderReview->value => 'warning',
        \App\Enums\RegistrationStatus::Accepted->value    => 'success',
        \App\Enums\RegistrationStatus::Rejected->value    => 'danger',
    ];
    $badgeColor = $statusColorMap[$reg->status->value] ?? 'secondary';
@endphp

@section('title', 'Surat Balasan — '.$reg->nomor_pendaftaran)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-pdf me-1 text-danger"></i>
                Kelola Surat Balasan
                <span class="font-monospace small text-muted ms-1">#{{ $reg->nomor_pendaftaran }}</span>
            </h1>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border shadow-sm mb-3">
            <i class="bi bi-check2-circle me-1"></i> {!! session('success') !!}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning border shadow-sm mb-3">
            <i class="bi bi-exclamation-triangle me-1"></i> {!! session('warning') !!}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger border shadow-sm mb-3">
            <i class="bi bi-x-octagon me-1"></i> {!! session('error') !!}
        </div>
    @endif

    <div class="row g-4">
        {{-- KIRI — Ringkasan Pendaftaran --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border mb-4">
                <div class="card-header bg-light d-flex align-items-center justify-content-between">
                    <h2 class="h5 mb-0"><i class="bi bi-info-circle me-1"></i> Ringkasan Pendaftaran</h2>
                    <span class="badge rounded-pill bg-{{ $badgeColor }}">
                        {{ $reg->status->label() }}
                    </span>
                </div>
                <div class="card-body small">
                    <dl class="row mb-3">
                        <dt class="col-5 text-muted">Nomor</dt>
                        <dd class="col-7 fw-bold font-monospace text-primary mb-1">{{ $reg->nomor_pendaftaran }}</dd>

                        <dt class="col-5 text-muted">Peserta</dt>
                        <dd class="col-7 fw-semibold mb-1">{{ $reg->user?->name ?? '—' }}</dd>

                        <dt class="col-5 text-muted">Email</dt>
                        <dd class="col-7 mb-1 small">{{ $reg->user?->email ?? '—' }}</dd>

                        <dt class="col-5 text-muted">Posisi</dt>
                        <dd class="col-7 mb-1">{{ $reg->position?->nama_posisi ?? '—' }}</dd>

                        <dt class="col-5 text-muted">Periode</dt>
                        <dd class="col-7 mb-1">{{ $reg->periode_label ?? '-' }}</dd>
                    </dl>
                    <hr class="my-2">
                    <div class="d-grid gap-1">
                        <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-link btn-sm ps-0 text-start py-1 mb-0">
                            <i class="bi bi-eye me-1"></i> Lihat Detail Lengkap Pendaftaran
                        </a>
                    </div>
                </div>
            </div>

            {{-- Business Rule Alert --}}
            <div class="card shadow-sm border {{ $canUpload ? 'border-success bg-success bg-opacity-5' : 'border-warning bg-warning bg-opacity-5' }}">
                <div class="card-body small">
                    <p class="mb-2 fw-semibold">
                        <i class="bi bi-shield-lock me-1"></i>
                        Business Rules — Upload Surat Balasan
                    </p>
                    <ul class="mb-0 ps-3">
                        <li class="mb-1">
                            Status WAJIB: <b class="text-success">ACCEPTED (Diterima)</b>.
                        </li>
                        <li class="mb-1">
                            Format File: <b>PDF</b> saja, maksimal <b>2 MB</b>.
                        </li>
                        <li class="mb-1">
                            Jika Replace → file lama otomatis dihapus.
                        </li>
                        <li>
                            Status lain (Submitted / Under Review / Rejected) <b>TIDAK BISA</b> upload.
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- KANAN — Upload Form & Info File --}}
        <div class="col-lg-8">
            @if (! $canUpload)
                <div class="alert alert-warning shadow-sm border mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <b>Upload DITOLAK (Business Rule 2 & 3):</b><br>
                    Status pendaftaran saat ini adalah
                    <span class="badge bg-{{ $badgeColor }}">{{ $reg->status->label() }}</span>.
                    Surat Balasan HANYA dapat diunggah jika status = <b class="text-success">ACCEPTED (Diterima)</b>.
                    <hr class="my-2">
                    <a href="{{ route('admin.applications.review', $reg->id) }}" class="btn btn-warning btn-sm mt-1">
                        <i class="bi bi-pencil-square me-1"></i> Berikan Keputusan Accepted terlebih dahulu
                    </a>
                </div>
            @endif

            {{-- INFO FILE — Jika file sudah ada --}}
            @if ($fileInfo['exists'])
                <div class="card shadow-sm border border-success mb-4">
                    <div class="card-header bg-success bg-opacity-10 border-success d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h2 class="h5 mb-0 text-success">
                            <i class="bi bi-file-earmark-check me-1"></i>
                            Surat Balasan Sudah Tersimpan
                        </h2>
                        <span class="badge bg-success rounded-pill">FILE READY</span>
                    </div>
                    <div class="card-body">
                        <dl class="row mb-3">
                            <dt class="col-sm-4 text-muted small">Nama File (Internal)</dt>
                            <dd class="col-sm-8 fw-mono font-monospace small">{{ $fileInfo['basename'] }}</dd>

                            <dt class="col-sm-4 text-muted small">Ukuran File</dt>
                            <dd class="col-sm-8">{{ $fileInfo['human_size'] }} ({{ $fileInfo['size_kb'] }} KB)</dd>

                            <dt class="col-sm-4 text-muted small">Terakhir Diunggah / Dimodifikasi</dt>
                            <dd class="col-sm-8">{{ $fileInfo['last_modified'] }}</dd>

                            <dt class="col-sm-4 text-muted small">Status Ketersediaan</dt>
                            <dd class="col-sm-8">
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-cloud-check me-1"></i> Tersedia untuk diunduh Peserta
                                </span>
                            </dd>
                        </dl>
                        <div class="d-flex flex-wrap gap-2 pt-2 border-top">
                            <a href="{{ route('admin.applications.reply-letter.download', $reg->id) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-download me-1"></i> Download Surat Balasan
                            </a>
                            @if ($fileInfo['public_url'])
                                <a href="{{ $fileInfo['public_url'] }}" target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="bi bi-box-arrow-up-right me-1"></i> Preview (Tab Baru)
                                </a>
                            @endif
                            <div class="small text-muted ms-auto align-self-end">
                                <i class="bi bi-info-circle me-1"></i> Upload file baru akan otomatis mereplace dan menghapus file lama.
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-secondary bg-light border mb-4">
                    <i class="bi bi-info-circle me-1"></i>
                    Surat Balasan <b>BELUM diunggah</b> untuk nomor pendaftaran ini.
                    Peserta belum dapat mengunduh surat sampai Anda mengunggah file PDF di bawah ini.
                </div>
            @endif

            {{-- FORM UPLOAD --}}
            <div class="card shadow-sm border {{ ! $canUpload ? 'opacity-50' : '' }}">
                <div class="card-header bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h2 class="h5 mb-0">
                        <i class="bi bi-cloud-upload me-1"></i>
                        {{ $fileInfo['exists'] ? 'Replace / Unggah Ulang Surat Balasan' : 'Unggah Surat Balasan (PDF)' }}
                    </h2>
                    @if ($canUpload)
                        <span class="badge rounded-pill bg-primary">UPLOAD {{ $fileInfo['exists'] ? '& REPLACE' : '' }}</span>
                    @else
                        <span class="badge rounded-pill bg-secondary">UPLOAD DITUTUP</span>
                    @endif
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger border shadow-sm mb-4">
                            <h3 class="h6 mb-2">
                                <i class="bi bi-exclamation-diamond me-1"></i> Validasi GAGAL — mohon perbaiki:
                            </h3>
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.applications.reply-letter.store', $reg->id) }}"
                        enctype="multipart/form-data"
                        id="form-upload-reply-letter"
                        {{ ! $canUpload ? 'aria-disabled=true' : '' }}>
                        @csrf

                        <div class="mb-4">
                            <label for="surat_balasan" class="form-label fw-semibold">
                                <i class="bi bi-filetype-pdf text-danger me-1"></i>
                                Pilih File Surat Balasan
                                @if ($canUpload)
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input
                                type="file"
                                class="form-control form-control-lg @error('surat_balasan') is-invalid @enderror"
                                id="surat_balasan"
                                name="surat_balasan"
                                accept="application/pdf,.pdf"
                                {{ ! $canUpload ? 'disabled' : 'required' }}>
                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-1 gap-2">
                                <div id="surat_balasan_help" class="form-text small">
                                    @if ($canUpload)
                                        <i class="bi bi-check-circle me-1 text-success"></i>
                                        Format: <b>PDF</b> saja. Maks: <b>2048 KB</b> (2 MB).
                                    @else
                                        <i class="bi bi-lock me-1 text-warning"></i>
                                        Upload dinonaktifkan (status bukan Accepted).
                                    @endif
                                </div>
                                <div id="file-info-live" class="small text-muted d-none">
                                    File dipilih: <span id="file-name" class="font-monospace"></span>
                                    (<span id="file-size"></span>)
                                </div>
                            </div>
                            @error('surat_balasan')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SUBMIT --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-between border-top pt-3">
                            <div class="small text-muted align-self-center">
                                <i class="bi bi-shield-lock me-1"></i>
                                Server akan validasi: MIME = PDF, ukuran ≤ 2MB, status = Accepted.
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-lg"></i> Batal
                                </a>
                                <button
                                    type="submit"
                                    id="btn-upload"
                                    class="btn {{ $fileInfo['exists'] ? 'btn-warning' : 'btn-primary' }}"
                                    {{ ! $canUpload ? 'disabled' : '' }}>
                                    <i class="bi bi-cloud-upload me-1"></i>
                                    {{ $fileInfo['exists'] ? 'Replace & Simpan Surat Baru' : 'Unggah & Simpan Surat Balasan' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function() {
    const fileInput = document.getElementById('surat_balasan');
    const fileInfoLive = document.getElementById('file-info-live');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const btnUpload = document.getElementById('btn-upload');

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    fileInput && fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            const f = this.files[0];
            fileInfoLive.classList.remove('d-none');
            fileName.textContent = f.name;
            fileSize.textContent = formatSize(f.size);
        } else {
            fileInfoLive.classList.add('d-none');
        }
    });

    const form = document.getElementById('form-upload-reply-letter');
    form && form.addEventListener('submit', function(e) {
        if (btnUpload && !btnUpload.disabled) {
            btnUpload.disabled = true;
            btnUpload.classList.add('opacity-75');
            btnUpload.innerHTML = '<i class="bi bi-arrow-repeat me-1 spinner-border spinner-border-sm"></i> Mengunggah ...';
        }
    });
})();
</script>
@endpush
