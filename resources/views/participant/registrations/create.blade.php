@extends('layouts.participant')
@section('title', 'Ajukan Pendaftaran Magang Baru')

@section('content')
<div class="row justify-content-center mb-3">
    <div class="col-lg-11">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h4 fw-bold mb-0.5">Ajukan Pendaftaran Magang Baru</h1>
                <p class="text-muted small mb-0">Pilih posisi magang dan lengkapi berkas pendaftaran Anda.</p>
            </div>
            <a href="{{ route('participant.registrations.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-2 font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="alert alert-primary border rounded-2 mb-3 py-2 px-3 d-flex align-items-center" role="alert">
            <i class="bi bi-info-circle-fill fs-5 text-primary me-2.5 flex-shrink-0"></i>
            <div class="flex-grow-1 text-xs">
                <strong class="fw-bold d-block mb-0.5">Selamat datang di Form Pendaftaran Magang Diskominfo Tuban!</strong>
                <p class="mb-0 text-muted">Pastikan Anda memilih posisi magang yang sesuai dengan kompetensi, minat & jurusan akademik Anda. Lengkapi seluruh kolom dan lampirkan dokumen yang valid sebelum menekan tombol Submit.</p>
            </div>
        </div>

        <div class="card shadow-sm border rounded-2 overflow-hidden mb-3">
            <div class="card-header bg-gradient bg-success text-white py-2 px-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-send-plus-fill me-1.5"></i>Form Pengajuan Pendaftaran Magang Baru
                </h6>
                <p class="mb-0 text-white-50 mt-0.5 text-xs">Kolom bertanda <span class="text-light fw-bold">*</span> wajib diisi.</p>
            </div>
            <form method="POST" action="{{ route('participant.registrations.store') }}"
                  enctype="multipart/form-data"
                  class="needs-validation" novalidate>
                @csrf
                <div class="card-body p-3 sm:p-4">
                    @include('participant.registrations._form', [
                        'positions' => $positions,
                        'reg'       => null,
                    ])
                </div>
                <div class="card-footer bg-white border-top py-2 px-3 text-end">
                    <a href="{{ route('participant.registrations.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-2 font-bold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3 py-1.5 text-xs me-2">
                        <i class="bi bi-arrow-left-circle me-1.5"></i>Batal
                    </a>
                    <button type="submit" id="btnSubmitPendaftaran" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-2 font-bold text-xs shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-3.5 py-1.5 d-inline-flex align-items-center justify-content-center" style="background-color: #4f46e5 !important; color: #ffffff !important; border-color: #4f46e5 !important;">
                        <i class="bi bi-send-fill me-1.5" style="color: #ffffff !important;"></i>Submit Pendaftaran
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submitBtn = document.getElementById('btnSubmitPendaftaran');
    if (submitBtn) {
        submitBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = submitBtn.closest('form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const posChecked = form.querySelector('input[name="position_id"]:checked');
            const posLabel = posChecked ? (posChecked.closest('label')?.querySelector('.fw-bold')?.textContent?.trim() || 'Posisi Terpilih') : '—';
            const cvFile = form.querySelector('input[name="cv"]')?.files[0]?.name || 'Berkas CV Terunggah';
            const spFile = form.querySelector('input[name="surat_pengantar"]')?.files[0]?.name || 'Berkas Surat Pengantar';

            window.SimangModal.confirm({
                title: 'Konfirmasi Submit Pendaftaran Magang',
                icon: 'bi-send-check-fill',
                theme: 'blue',
                summaryHtml: '<div class="row g-1"><div class="col-4 text-slate-500 font-semibold">Posisi Magang</div><div class="col-8 fw-bold text-slate-900">' + posLabel.replace(/</g, '&lt;') + '</div><div class="col-4 text-slate-500 font-semibold">Lampiran CV</div><div class="col-8 font-monospace text-emerald-700">' + cvFile.replace(/</g, '&lt;') + '</div><div class="col-4 text-slate-500 font-semibold">Surat Pengantar</div><div class="col-8 font-monospace text-emerald-700">' + spFile.replace(/</g, '&lt;') + '</div></div>',
                alertText: 'Setelah disubmit, berkas pendaftaran Anda akan masuk tahap <strong>Under Review</strong> dan tidak dapat disunting kembali sampai proses verifikasi oleh Admin selesai.',
                confirmText: 'Ya, Submit Pendaftaran',
                confirmIcon: 'bi-send-fill',
                form: form
            });
        });
    }
});
</script>
@endpush
