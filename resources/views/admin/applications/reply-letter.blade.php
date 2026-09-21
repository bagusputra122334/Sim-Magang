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
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-2 mb-0">
                <i class="bi bi-file-earmark-pdf text-rose-500"></i>
                <span>Kelola Surat Balasan</span>
                <span class="font-monospace text-sm text-slate-500 font-normal">#{{ $reg->nomor_pendaftaran }}</span>
            </h1>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.applications.show', $reg->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 font-semibold text-sm rounded-xl transition-all shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Detail
            </a>
            <a href="{{ route('admin.applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold text-sm rounded-xl transition-all shadow-sm hover:shadow-md">
                <i class="bi bi-check-circle-fill"></i> Selesai & Kembali ke Verifikasi
            </a>
        </div>
    </div>



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
            <div class="card shadow-sm border rounded-xl overflow-hidden {{ ! $canUpload ? 'opacity-50' : '' }}">
                <div class="card-header bg-light py-2.5 px-3.5 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h2 class="h6 mb-0 font-bold text-slate-800 flex items-center gap-1.5">
                        <i class="bi bi-cloud-upload text-blue-600"></i>
                        {{ $fileInfo['exists'] ? 'Replace / Unggah Ulang Surat Balasan' : 'Unggah Surat Balasan (PDF)' }}
                    </h2>
                    @if ($canUpload)
                        <span class="badge rounded-pill bg-blue-600 text-white text-xs px-2.5 py-1 font-semibold">UPLOAD {{ $fileInfo['exists'] ? '& REPLACE' : '' }}</span>
                    @else
                        <span class="badge rounded-pill bg-slate-500 text-white text-xs px-2.5 py-1 font-semibold">UPLOAD DITUTUP</span>
                    @endif
                </div>
                <div class="card-body p-3.5">
                    @if ($errors->any())
                        <div class="alert alert-danger border shadow-sm mb-3 py-2 px-3">
                            <h3 class="h6 mb-1 text-xs font-bold">
                                <i class="bi bi-exclamation-diamond me-1"></i> Validasi GAGAL — mohon perbaiki:
                            </h3>
                            <ul class="mb-0 text-xs ps-3">
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

                        <div x-data="{ fileName: null }" class="mb-3">
                            <label for="surat_balasan" class="form-label fw-semibold text-xs text-slate-700 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                                <span>
                                    <i class="bi bi-filetype-pdf text-danger me-1"></i>
                                    Pilih File Surat Balasan
                                    @if ($canUpload)
                                        <span class="text-danger">*</span>
                                    @endif
                                </span>
                                <span class="text-slate-400 font-normal text-xs lowercase">Maks. 2 MB</span>
                            </label>
                            
                            <div class="relative border border-dashed rounded-lg p-3 text-center transition-all duration-150"
                                 :class="fileName ? 'border-blue-500 bg-blue-50/30' : 'border-slate-300 bg-slate-50/60 hover:border-blue-400 hover:bg-blue-50/10'">
                                <input
                                    type="file"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 @error('surat_balasan') is-invalid @enderror"
                                    id="surat_balasan"
                                    name="surat_balasan"
                                    accept="application/pdf,.pdf"
                                    @change="fileName = $event.target.files[0] ? $event.target.files[0].name : null"
                                    {{ ! $canUpload ? 'disabled' : 'required' }}>
                                
                                <div x-show="!fileName"
                                     x-cloak
                                     :class="{ 'hidden': fileName }"
                                     class="flex items-center justify-center gap-2 py-1">
                                    <i class="bi bi-cloud-arrow-up text-blue-600 text-lg"></i>
                                    <span class="font-semibold text-slate-700 text-xs">
                                        @if ($canUpload)
                                            Pilih atau seret file PDF ke sini
                                        @else
                                            Upload dinonaktifkan (status bukan Accepted)
                                        @endif
                                    </span>
                                </div>
                                
                                <div x-show="fileName"
                                     x-cloak
                                     :class="{ 'hidden': !fileName }"
                                     class="flex items-center justify-center gap-2 py-0.5">
                                    <i class="bi bi-file-earmark-pdf text-blue-600 text-lg"></i>
                                    <span class="font-semibold text-slate-800 text-xs truncate max-w-xs" x-text="fileName"></span>
                                    <button type="button" @click.stop.prevent="fileName = null; document.getElementById('surat_balasan').value = ''" class="text-slate-400 hover:text-rose-600 transition-colors ms-1 relative z-20" title="Batal pilih file">
                                        <i class="bi bi-x-circle-fill text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            @error('surat_balasan')
                                <div class="text-danger text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SUBMIT --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center border-top pt-3 mt-1">
                            <div class="small text-muted align-self-center text-xs">
                                <i class="bi bi-shield-lock me-1"></i>
                                Format: PDF ≤ 2MB, Status = Accepted.
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-white border border-gray-300 rounded-lg font-semibold shadow-sm hover:bg-gray-50 text-gray-700 text-xs px-3.5 py-1.5">
                                    <i class="bi bi-x-lg me-1"></i> Batal
                                </a>
                                <button
                                    type="submit"
                                    id="btn-upload"
                                    class="btn bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-sm transition-all duration-150 px-4 py-1.5 text-xs"
                                    {{ ! $canUpload ? 'disabled' : '' }}>
                                    <i class="bi bi-cloud-upload me-1.5"></i>
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
