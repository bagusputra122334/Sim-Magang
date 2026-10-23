@extends('layouts.admin')

@php
    $reg = $application;

    // Helper resolusi dokumen persyaratan (prioritas route viewer inline, fallback storage asset)
    $resolveDoc = function(?string $path, string $type) use ($reg) {
        if (empty($path)) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }
        try {
            return route('documents.view', ['registration' => $reg->id, 'type' => $type]);
        } catch (\Throwable $e) {
            return asset('storage/' . $path);
        }
    };

    $cvPath = $reg->cv_path ?? $reg->cv ?? null;
    $spPath = $reg->surat_pengantar_path ?? $reg->surat_pengantar ?? $reg->letter ?? null;
    $pmPath = $reg->proposal_magang_path ?? $reg->proposal_magang ?? $reg->proposal ?? null;

    $finalCvUrl = $cvUrl ?? $resolveDoc($cvPath, 'cv');
    $finalSpUrl = $suratPengantarUrl ?? $resolveDoc($spPath, 'surat_pengantar');
    $finalPmUrl = $proposalMagangUrl ?? $resolveDoc($pmPath, 'proposal_magang');
@endphp

@section('title', 'Verifikasi Review — '.$reg->nomor_pendaftaran)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h1 class="h4 mb-0 font-bold !text-slate-800 dark:!text-slate-200">
                <i class="bi bi-check2-square me-1 text-primary"></i>
                Form Verifikasi Pendaftaran
            </h1>
        </div>
        <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-outline-secondary btn-sm py-1 px-2.5 text-xs font-semibold">
            <i class="bi bi-arrow-left"></i> Batal & Kembali ke Detail
        </a>
    </div>

    <div class="row g-2.5">
        {{-- Ringkasan Sederhana Sebelum Submit --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border mb-3">
                <div class="card-header bg-light py-2 px-3 border-b">
                    <h2 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0"><i class="bi bi-info-circle me-1"></i> Ringkasan Pendaftaran</h2>
                </div>
                <div class="card-body p-3 sm:p-4 text-xs">
                    <p class="mb-1.5">
                        <span class="text-muted">Nomor:</span>
                        <span class="fw-bold font-monospace text-primary">{{ $reg->nomor_pendaftaran }}</span>
                    </p>
                    <p class="mb-1.5">
                        <span class="text-muted">Nama Peserta:</span>
                        <span class="fw-semibold">{{ $reg->user?->name ?? '—' }}</span>
                    </p>
                    <p class="mb-1.5">
                        <span class="text-muted">Posisi:</span>
                        <span>{{ $reg->position?->nama_posisi ?? '—' }}</span>
                    </p>
                    <p class="mb-1.5">
                        <span class="text-muted">Periode:</span>
                        <span>{{ $reg->periode_label ?? '-' }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-muted">Status Saat Ini:</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle py-1 px-2 text-xs">{{ $reg->status->label() }}</span>
                    </p>

                    <hr class="my-2 border-slate-200">

                    {{-- Berkas Lampiran Peserta (Direct Action Buttons) --}}
                    <div class="d-flex flex-column gap-1.5 mt-2">
                        <span class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider d-flex align-items-center gap-1.5">
                            <i class="bi bi-paperclip text-primary"></i>
                            Berkas Lampiran Peserta:
                        </span>
                        <div class="d-flex flex-column gap-1.5">
                            @if($finalCvUrl)
                                <a href="{{ $finalCvUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-between px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 dark:bg-blue-950/40 dark:text-blue-300 dark:border-blue-800 dark:hover:bg-blue-900/50 transition-all text-decoration-none shadow-sm" title="Buka CV di tab baru">
                                    <span class="d-inline-flex align-items-center gap-1.5 truncate">
                                        <i class="bi bi-file-earmark-pdf-fill text-blue-600 dark:text-blue-400"></i>
                                        <span>Lihat CV / Resume</span>
                                    </span>
                                    <i class="bi bi-box-arrow-up-right text-[11px] opacity-70 flex-shrink-0"></i>
                                </a>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-200 rounded-lg dark:bg-slate-800/60 dark:text-slate-500 dark:border-slate-700">
                                    <i class="bi bi-file-earmark-x"></i>
                                    <span>CV belum diunggah</span>
                                </div>
                            @endif

                            @if($finalSpUrl)
                                <a href="{{ $finalSpUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-between px-3 py-1.5 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 dark:bg-indigo-950/40 dark:text-indigo-300 dark:border-indigo-800 dark:hover:bg-indigo-900/50 transition-all text-decoration-none shadow-sm" title="Buka Surat Pengantar di tab baru">
                                    <span class="d-inline-flex align-items-center gap-1.5 truncate">
                                        <i class="bi bi-file-earmark-text-fill text-indigo-600 dark:text-indigo-400"></i>
                                        <span>Lihat Surat Pengantar</span>
                                    </span>
                                    <i class="bi bi-box-arrow-up-right text-[11px] opacity-70 flex-shrink-0"></i>
                                </a>
                            @else
                                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs text-slate-400 bg-slate-50 border border-slate-200 rounded-lg dark:bg-slate-800/60 dark:text-slate-500 dark:border-slate-700">
                                    <i class="bi bi-file-earmark-x"></i>
                                    <span>Surat pengantar belum diunggah</span>
                                </div>
                            @endif

                            @if($finalPmUrl)
                                <a href="{{ $finalPmUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-between px-3 py-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800 dark:hover:bg-emerald-900/50 transition-all text-decoration-none shadow-sm" title="Buka Proposal Magang di tab baru">
                                    <span class="d-inline-flex align-items-center gap-1.5 truncate">
                                        <i class="bi bi-file-earmark-richtext-fill text-emerald-600 dark:text-emerald-400"></i>
                                        <span>Lihat Proposal Magang</span>
                                    </span>
                                    <i class="bi bi-box-arrow-up-right text-[11px] opacity-70 flex-shrink-0"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border border-warning bg-warning bg-opacity-5 mb-3">
                <div class="card-body p-3 sm:p-4 text-xs text-warning-emphasis">
                    <p class="mb-1.5 fw-bold !text-slate-800 dark:!text-slate-200"><i class="bi bi-lightbulb me-1"></i> Bantuan Validasi:</p>
                    <ul class="mb-0 ps-3 space-y-1">
                        <li>Pilihan hanya <b>ACCEPTED (Diterima)</b> atau <b>REJECTED (Ditolak)</b>.</li>
                        <li>Status <b>REJECTED mewajibkan Catatan Admin (min 10 karakter, maks 1000)</b>.</li>
                        <li>Status <b>ACCEPTED</b> Catatan bersifat opsional (max 1000 karakter).</li>
                        <li>Setelah submit => STATUS FINAL, tidak bisa diubah lagi.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form Review --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border mb-3">
                <div class="card-header bg-light py-2 px-3 border-b">
                    <h2 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-0">
                        <i class="bi bi-pencil-square me-1"></i>
                        Keputusan Verifikasi Pendaftaran
                    </h2>
                </div>
                <div class="card-body p-3 sm:p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger border shadow-sm mb-3 p-2.5 text-xs">
                            <h3 class="text-xs font-bold mb-1"><i class="bi bi-exclamation-diamond me-1"></i> Validasi GAGAL — mohon perbaiki:</h3>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-3 p-2.5 text-xs shadow-sm">
                            <i class="bi bi-x-octagon me-1"></i> {!! session('error') !!}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.applications.update-review', $reg->id) }}"
                        id="form-review-verify"
                        onsubmit="return verifyConfirmation();">
                        @csrf
                        @method('PUT')

                        {{-- Status Radio --}}
                        <fieldset class="mb-3">
                            <legend class="col-form-label text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5 d-flex align-items-center gap-1.5">
                                <i class="bi bi-ui-checks text-primary"></i>
                                <span>Status Verifikasi Final</span>
                                <span class="text-danger">*</span>
                            </legend>
                            <div class="row g-2.5">
                                <div class="col-md-6">
                                    <label class="form-check-label d-block border rounded-2 p-3 h-100 cursor-pointer transition-all shadow-sm
                                           {{ old('status') === 'accepted' ? 'border-success border-2 bg-success-subtle bg-opacity-25' : 'border-secondary-subtle bg-white' }}"
                                           id="wrap-accepted"
                                           onclick="document.getElementById('status_accepted').checked = true; selectWrap('accepted');">
                                        <div class="d-flex align-items-start gap-2.5">
                                            <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="status"
                                                   id="status_accepted" value="accepted"
                                                   {{ old('status') === 'accepted' ? 'checked' : '' }}
                                                   onclick="selectWrap('accepted');">
                                            <div>
                                                <div class="fw-bold text-success text-sm mb-1 d-flex align-items-center gap-1">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    <span>ACCEPTED (Diterima)</span>
                                                </div>
                                                <div class="text-xs text-muted lh-sm">
                                                    Peserta dinyatakan lolos verifikasi dan diterima mengikuti kegiatan magang di Diskominfo.
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label d-block border rounded-2 p-3 h-100 cursor-pointer transition-all shadow-sm
                                           {{ old('status') === 'rejected' ? 'border-danger border-2 bg-danger-subtle bg-opacity-25' : 'border-secondary-subtle bg-white' }}"
                                           id="wrap-rejected"
                                           onclick="document.getElementById('status_rejected').checked = true; selectWrap('rejected');">
                                        <div class="d-flex align-items-start gap-2.5">
                                            <input class="form-check-input flex-shrink-0 mt-1" type="radio" name="status"
                                                   id="status_rejected" value="rejected"
                                                   {{ old('status') === 'rejected' ? 'checked' : '' }}
                                                   onclick="selectWrap('rejected');">
                                            <div>
                                                <div class="fw-bold text-danger text-sm mb-1 d-flex align-items-center gap-1">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                    <span>REJECTED (Ditolak)</span>
                                                </div>
                                                <div class="text-xs text-muted lh-sm">
                                                    Pendaftaran ditolak — wajib menyertakan alasan penolakan pada catatan admin.
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Catatan Admin --}}
                        <div class="mb-3">
                            <label for="catatan_admin" class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1.5">
                                <i class="bi bi-chat-square-dots me-1 text-primary"></i>
                                Catatan Admin
                                <span class="text-danger" id="label-catatan-wajib">
                                    @if (old('status') === 'rejected' || ! old('status')) * @endif
                                </span>
                            </label>
                            <textarea
                                class="form-control rounded-2 py-1.5 px-2.5 text-xs @error('catatan_admin') is-invalid @enderror"
                                id="catatan_admin"
                                name="catatan_admin"
                                rows="4"
                                maxlength="1000"
                                placeholder="@if (old('status') === 'rejected') Tuliskan alasan penolakan secara jelas (minimal 10, maksimal 1000 karakter) @else Opsional: catatan untuk peserta dan arsip admin ... @endif"
                            >{{ old('catatan_admin', $reg->catatan_admin) }}</textarea>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div id="catatan-helper" class="form-text text-xs">
                                    @if (old('status') === 'rejected')
                                        <span class="text-danger fw-medium">Wajib (min 10).</span>
                                    @endif
                                    <span>Maksimal <b>1000</b> karakter.</span>
                                </div>
                                <div class="text-xs">
                                    <span id="counter-catatan">{{ mb_strlen(old('catatan_admin', $reg->catatan_admin ?? '')) }}</span>
                                    <span class="text-muted">/1000</span>
                                </div>
                            </div>
                            @error('catatan_admin')
                                <div class="invalid-feedback text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Bottom --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4 pt-3 border-t border-slate-200">
                            <div class="text-slate-500 flex items-center gap-1.5 text-xs">
                                <i class="bi bi-shield-lock text-blue-600"></i>
                                Decision guard: Keputusan final tidak dapat diubah setelah disubmit.
                            </div>
                            <div class="flex items-center justify-end gap-2.5">
                                <!-- Tombol Batal -->
                                <a href="{{ route('admin.applications.show', $reg->id) }}" class="inline-flex items-center justify-center h-8 px-3.5 bg-white border border-slate-300 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Batal
                                </a>
                                
                                <!-- Tombol Simpan -->
                                <button type="submit" id="btn-submit-verif" class="inline-flex items-center justify-center h-8 px-4 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Simpan Keputusan
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
    const ta = document.getElementById('catatan_admin');
    const counter = document.getElementById('counter-catatan');
    const helper  = document.getElementById('catatan-helper');
    const labelWajib = document.getElementById('label-catatan-wajib');
    const accepted = document.getElementById('status_accepted');
    const rejected = document.getElementById('status_rejected');

    window.selectWrap = function(which) {
        const wa = document.getElementById('wrap-accepted');
        const wr = document.getElementById('wrap-rejected');
        wa.classList.remove('border-success', 'bg-success-subtle', 'bg-opacity-25', 'border-2', 'shadow-sm');
        wr.classList.remove('border-danger',  'bg-danger-subtle',  'bg-opacity-25', 'border-2', 'shadow-sm');
        wa.classList.add('border-secondary-subtle', 'bg-white');
        wr.classList.add('border-secondary-subtle', 'bg-white');

        if (which === 'accepted') {
            wa.classList.remove('border-secondary-subtle', 'bg-white');
            wa.classList.add('border-success', 'bg-success-subtle', 'bg-opacity-25', 'border-2', 'shadow-sm');
        } else if (which === 'rejected') {
            wr.classList.remove('border-secondary-subtle', 'bg-white');
            wr.classList.add('border-danger', 'bg-danger-subtle', 'bg-opacity-25', 'border-2', 'shadow-sm');
        }
        syncTextareaRequired();
    };

    function syncTextareaRequired() {
        const isRej = rejected && rejected.checked;
        labelWajib.innerHTML = isRej ? '<b class="text-danger">* WAJIB</b>' : '';
        ta.placeholder = isRej
            ? 'Tuliskan alasan penolakan secara jelas (minimal 10 karakter, maksimal 1000).'
            : 'Opsional — Anda bisa menambahkan catatan untuk peserta dan arsip admin (maks 1000 karakter).';
        ta.required = isRej;
        helper.innerHTML = isRej
            ? '<span class="text-danger fw-medium">Wajib diisi (minimal 10 karakter).</span> <span>Maksimal <b>1000</b> karakter.</span>'
            : '<span>Opsional. Maksimal <b>1000</b> karakter.</span>';
    }

    if (accepted && !accepted.checked && rejected && !rejected.checked && @json(old('status')) === null) {
        // No selection by default
    }
    if (accepted && accepted.checked) selectWrap('accepted');
    if (rejected && rejected.checked) selectWrap('rejected');
    syncTextareaRequired();

    accepted && accepted.addEventListener('change', function() { if (this.checked) selectWrap('accepted'); });
    rejected && rejected.addEventListener('change', function() { if (this.checked) selectWrap('rejected'); });

    ta && ta.addEventListener('input', function() {
        counter.textContent = this.value.length;
    });
})();

function verifyConfirmation(e) {
    if (e && e.preventDefault) e.preventDefault();

    const acc = document.getElementById('status_accepted');
    const rej = document.getElementById('status_rejected');
    const cat = document.getElementById('catatan_admin').value.trim();
    const form = document.querySelector('form[action*="review"]');

    let title, theme, icon, summaryHtml, alertText, btnText, btnIcon;

    if (acc && acc.checked) {
        title = 'Konfirmasi Penerimaan Magang';
        theme = 'emerald';
        icon = 'bi-check-circle-fill';
        summaryHtml = '<div class="row g-1"><div class="col-4 text-slate-500 font-semibold">Keputusan</div><div class="col-8 fw-bold text-emerald-600">ACCEPTED (Diterima)</div><div class="col-4 text-slate-500 font-semibold">Nama Peserta</div><div class="col-8 fw-bold text-slate-900">{{ addslashes($reg->user?->name ?? '—') }}</div><div class="col-4 text-slate-500 font-semibold">Catatan</div><div class="col-8 text-slate-700">' + (cat.length > 0 ? cat.replace(/</g, '&lt;') : '<span class="text-muted italic">Tidak ada catatan</span>') + '</div></div>';
        alertText = 'Peserta akan dinyatakan resmi diterima magang di Diskominfo SP Tuban. Email notifikasi penerimaan dan instruksi lanjutan akan dikirimkan otomatis.';
        btnText = 'Ya, Terima Peserta';
        btnIcon = 'bi-check-circle-fill';
    } else if (rej && rej.checked) {
        if (cat.length < 10) {
            alert('Catatan Admin minimal 10 karakter saat status Rejected.');
            document.getElementById('catatan_admin').focus();
            return false;
        }
        title = 'Konfirmasi Penolakan Pendaftaran';
        theme = 'rose';
        icon = 'bi-x-circle-fill';
        summaryHtml = '<div class="row g-1"><div class="col-4 text-slate-500 font-semibold">Keputusan</div><div class="col-8 fw-bold text-rose-600">REJECTED (Ditolak)</div><div class="col-4 text-slate-500 font-semibold">Nama Peserta</div><div class="col-8 fw-bold text-slate-900">{{ addslashes($reg->user?->name ?? '—') }}</div><div class="col-4 text-slate-500 font-semibold">Alasan</div><div class="col-8 text-slate-700">' + cat.replace(/</g, '&lt;') + '</div></div>';
        alertText = 'Pendaftaran peserta akan ditolak secara permanen. Peserta akan menerima email pemberitahuan beserta alasan penolakan yang telah Anda cantumkan.';
        btnText = 'Ya, Tolak Pendaftaran';
        btnIcon = 'bi-x-circle-fill';
    } else {
        alert('Silakan pilih salah satu keputusan terlebih dahulu: Accepted atau Rejected.');
        return false;
    }

    window.SimangModal.confirm({
        title: title,
        icon: icon,
        theme: theme,
        summaryHtml: summaryHtml,
        alertText: alertText,
        confirmText: btnText,
        confirmIcon: btnIcon,
        form: form
    });

    return false;
}

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btn-submit-verif');
    if (btn) {
        btn.addEventListener('click', verifyConfirmation);
    }
});
</script>
@endpush
