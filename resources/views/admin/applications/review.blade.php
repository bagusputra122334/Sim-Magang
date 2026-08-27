@extends('layouts.admin')

@php
    $reg = $application;
@endphp

@section('title', 'Verifikasi Review — '.$reg->nomor_pendaftaran)

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="bi bi-check2-square me-1 text-primary"></i>
                Form Verifikasi Pendaftaran
            </h1>
        </div>
        <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Batal & Kembali ke Detail
        </a>
    </div>

    <div class="row g-4">
        {{-- Ringkasan Sederhana Sebelum Submit --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border mb-4">
                <div class="card-header bg-light">
                    <h2 class="h5 mb-0"><i class="bi bi-info-circle me-1"></i> Ringkasan Pendaftaran</h2>
                </div>
                <div class="card-body small">
                    <p class="mb-2">
                        <span class="text-muted">Nomor:</span>
                        <span class="fw-bold font-monospace text-primary">{{ $reg->nomor_pendaftaran }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-muted">Nama Peserta:</span>
                        <span class="fw-semibold">{{ $reg->user?->name ?? '—' }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-muted">Posisi:</span>
                        <span>{{ $reg->position?->nama_posisi ?? '—' }}</span>
                    </p>
                    <p class="mb-2">
                        <span class="text-muted">Periode:</span>
                        <span>{{ $reg->periode_label ?? '-' }}</span>
                    </p>
                    <p class="mb-3">
                        <span class="text-muted">Status Saat Ini:</span>
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">{{ $reg->status->label() }}</span>
                    </p>
                    <hr class="my-2">
                    <div class="d-grid gap-1">
                        <a href="{{ route('admin.applications.show', $reg->id) }}#dokumen" class="btn btn-link btn-sm ps-0 text-start mb-0 py-1">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Lihat Dokumen Persyaratan (CV, Surat Pengantar, Proposal)
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border border-warning bg-warning bg-opacity-5">
                <div class="card-body small text-warning-emphasis">
                    <p class="mb-2 fw-semibold"><i class="bi bi-lightbulb me-1"></i> Bantuan Validasi:</p>
                    <ul class="mb-0 ps-3">
                        <li class="mb-1">Pilihan hanya <b>ACCEPTED (Diterima)</b> atau <b>REJECTED (Ditolak)</b>.</li>
                        <li class="mb-1">Status <b>REJECTED mewajibkan Catatan Admin (min 10 karakter, maks 1000)</b>.</li>
                        <li class="mb-1">Status <b>ACCEPTED</b> Catatan bersifat opsional (max 1000 karakter).</li>
                        <li>Setelah submit => STATUS FINAL, tidak bisa diubah lagi.</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Form Review --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border">
                <div class="card-header bg-light">
                    <h2 class="h5 mb-0">
                        <i class="bi bi-pencil-square me-1"></i>
                        Keputusan Verifikasi Pendaftaran
                    </h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger border shadow-sm mb-4">
                            <h3 class="h6 mb-2"><i class="bi bi-exclamation-diamond me-1"></i> Validasi GAGAL — mohon perbaiki:</h3>
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $msg)
                                    <li>{{ $msg }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger mb-4 shadow-sm">
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
                        <fieldset class="mb-4">
                            <legend class="col-form-label fw-semibold mb-3 text-dark d-flex align-items-center gap-2">
                                <i class="bi bi-ui-checks text-primary fs-5"></i>
                                <span>Status Verifikasi Final</span>
                                <span class="text-danger">*</span>
                            </legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-check-label d-block border rounded-4 p-4 h-100 cursor-pointer transition-all shadow-sm
                                           {{ old('status') === 'accepted' ? 'border-success border-2 bg-success-subtle bg-opacity-25' : 'border-secondary-subtle bg-white' }}"
                                           id="wrap-accepted"
                                           onclick="document.getElementById('status_accepted').checked = true; selectWrap('accepted');">
                                        <div class="d-flex align-items-start gap-3">
                                            <input class="form-check-input flex-shrink-0 mt-1.5" type="radio" name="status"
                                                   id="status_accepted" value="accepted"
                                                   {{ old('status') === 'accepted' ? 'checked' : '' }}
                                                   onclick="selectWrap('accepted');">
                                            <div>
                                                <div class="fw-bold text-success fs-5 mb-1 d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                    <span>ACCEPTED (Diterima)</span>
                                                </div>
                                                <div class="small text-muted lh-base">
                                                    Peserta dinyatakan lolos verifikasi dan diterima mengikuti kegiatan magang di Diskominfo.
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-check-label d-block border rounded-4 p-4 h-100 cursor-pointer transition-all shadow-sm
                                           {{ old('status') === 'rejected' ? 'border-danger border-2 bg-danger-subtle bg-opacity-25' : 'border-secondary-subtle bg-white' }}"
                                           id="wrap-rejected"
                                           onclick="document.getElementById('status_rejected').checked = true; selectWrap('rejected');">
                                        <div class="d-flex align-items-start gap-3">
                                            <input class="form-check-input flex-shrink-0 mt-1.5" type="radio" name="status"
                                                   id="status_rejected" value="rejected"
                                                   {{ old('status') === 'rejected' ? 'checked' : '' }}
                                                   onclick="selectWrap('rejected');">
                                            <div>
                                                <div class="fw-bold text-danger fs-5 mb-1 d-flex align-items-center gap-1.5">
                                                    <i class="bi bi-x-circle-fill"></i>
                                                    <span>REJECTED (Ditolak)</span>
                                                </div>
                                                <div class="small text-muted lh-base">
                                                    Pendaftaran ditolak — wajib menyertakan alasan penolakan pada catatan admin.
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Catatan Admin --}}
                        <div class="mb-4">
                            <label for="catatan_admin" class="form-label fw-semibold text-dark">
                                <i class="bi bi-chat-square-dots me-1 text-primary"></i>
                                Catatan Admin
                                <span class="text-danger" id="label-catatan-wajib">
                                    @if (old('status') === 'rejected' || ! old('status')) * @endif
                                </span>
                            </label>
                            <textarea
                                class="form-control form-control-lg rounded-3 @error('catatan_admin') is-invalid @enderror"
                                id="catatan_admin"
                                name="catatan_admin"
                                rows="5"
                                maxlength="1000"
                                placeholder="@if (old('status') === 'rejected') Tuliskan alasan penolakan secara jelas (minimal 10, maksimal 1000 karakter) @else Opsional: catatan untuk peserta dan arsip admin ... @endif"
                            >{{ old('catatan_admin', $reg->catatan_admin) }}</textarea>
                            <div class="d-flex justify-content-between align-items-center mt-1">
                                <div id="catatan-helper" class="form-text small">
                                    @if (old('status') === 'rejected')
                                        <span class="text-danger fw-medium">Wajib (min 10).</span>
                                    @endif
                                    <span>Maksimal <b>1000</b> karakter.</span>
                                </div>
                                <div class="small">
                                    <span id="counter-catatan">{{ mb_strlen(old('catatan_admin', $reg->catatan_admin ?? '')) }}</span>
                                    <span class="text-muted">/1000</span>
                                </div>
                            </div>
                            @error('catatan_admin')
                                <div class="invalid-feedback small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Submit Bottom --}}
                        <div class="d-flex flex-wrap gap-2 justify-content-between border-top pt-3">
                            <div class="small text-muted d-flex align-items-center gap-1">
                                <i class="bi bi-shield-lock text-primary"></i>
                                Decision guard: keputusan final tidak dapat diubah setelah disubmit.
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.applications.show', $reg->id) }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2">
                                    <i class="bi bi-x-lg me-1"></i> Batal
                                </a>
                                <button type="submit" id="btn-submit-verif" class="btn bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2">
                                    <i class="bi bi-check2-circle me-1"></i>
                                    Submit Keputusan (Final)
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

function verifyConfirmation() {
    const acc = document.getElementById('status_accepted');
    const rej = document.getElementById('status_rejected');
    const cat = document.getElementById('catatan_admin').value.trim();
    let msg;
    if (acc && acc.checked) {
        msg = cat.length === 0
            ? 'Keputusan = ACCEPTED (Diterima). Tidak ada catatan admin. Lanjutkan submit final (tidak bisa dibatalkan)?'
            : 'Keputusan = ACCEPTED (Diterima) dengan Catatan Admin. Lanjutkan submit final?';
    } else if (rej && rej.checked) {
        if (cat.length < 10) {
            alert('Catatan Admin minimal 10 karakter saat Rejected.');
            document.getElementById('catatan_admin').focus();
            return false;
        }
        msg = 'Keputusan = REJECTED (Ditolak) dengan Catatan Admin. Lanjutkan submit final?';
    } else {
        alert('Pilih salah satu status: Accepted atau Rejected.');
        return false;
    }
    const btn = document.getElementById('btn-submit-verif');
    if (btn) { btn.disabled = true; btn.classList.add('opacity-75'); btn.innerHTML = '<i class="bi bi-arrow-repeat me-1 spinner-border spinner-border-sm"></i> Memproses ...'; }
    return confirm(msg);
}
</script>
@endpush
