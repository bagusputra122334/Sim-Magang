@extends('layouts.participant')
@section('title', 'Ajukan Pendaftaran Magang Baru')

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-lg-11">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Ajukan Pendaftaran Magang Baru</h1>
                <p class="text-muted mb-0">Pilih posisi magang dan lengkapi berkas pendaftaran Anda.</p>
            </div>
            <a href="{{ route('participant.registrations.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
            </a>
        </div>

        <div class="alert alert-primary border-2 rounded-4 mb-5 py-4 px-5 d-flex align-items-start" role="alert">
            <i class="bi bi-info-circle-fill display-6 text-primary me-4 flex-shrink-0"></i>
            <div class="flex-grow-1">
                <h4 class="alert-heading fw-bold mb-2">Selamat datang di Form Pendaftaran Magang Diskominfo Tuban!</h4>
                <p class="mb-0">Pastikan Anda memilih posisi magang yang sesuai dengan kompetensi, minat & jurusan akademik Anda. Lengkapi seluruh kolom dan lampirkan dokumen yang valid sebelum menekan tombol Submit.</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-success text-white py-4 px-5">
                <h3 class="mb-0 fw-bold">
                    <i class="bi bi-send-plus-fill me-2"></i>Form Pengajuan Pendaftaran Magang Baru
                </h3>
                <p class="mb-0 text-white-50 mt-1 small">Kolom bertanda <span class="text-light fw-bold">*</span> wajib diisi.</p>
            </div>
            <form method="POST" action="{{ route('participant.registrations.store') }}"
                  enctype="multipart/form-data"
                  class="needs-validation" novalidate>
                @csrf
                <div class="card-body p-5">
                    @include('participant.registrations._form', [
                        'positions' => $positions,
                        'reg'       => null,
                    ])
                </div>
                <div class="card-footer bg-white border-top py-4 px-5 text-end">
                    <a href="{{ route('participant.registrations.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5 me-2">
                        <i class="bi bi-arrow-left-circle me-2"></i>Batal
                    </a>
                    <button type="submit" id="btnSubmitPendaftaran" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold text-sm shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-5 py-2.5 d-inline-flex align-items-center justify-content-center" style="background-color: #4f46e5 !important; color: #ffffff !important; border-color: #4f46e5 !important;">
                        <i class="bi bi-send-fill me-2" style="color: #ffffff !important;"></i>Submit Pendaftaran
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
            const valid = confirm('Anda akan MENSUBMIT pendaftaran magang?\n\nSetelah submit, pendaftaran Anda tidak dapat diubah selama masa Under Review (kecuali jika status Rejected). Pastikan seluruh data & dokumen sudah BENAR dan LENGKAP.');
            if (!valid) e.preventDefault();
        });
    }
});
</script>
@endpush
