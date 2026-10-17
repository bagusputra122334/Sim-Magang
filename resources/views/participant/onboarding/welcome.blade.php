@extends('layouts.participant')

@section('title', 'Selamat Datang — Onboarding Peserta')

@section('content')
<div class="row justify-content-center py-2 sm:py-3">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 1])

        {{-- Onboarding Welcome Hero Card --}}
        <div class="card border-0 shadow-sm rounded-2 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white p-3 sm:p-4 text-center position-relative">
                <div class="badge bg-white bg-opacity-20 text-white rounded-pill px-2.5 py-1 fw-bold text-xs mb-2">
                    <i class="bi bi-clock-history me-1"></i> Estimasi Pengisian: ±2 Menit
                </div>
                <h1 class="h4 fw-bold mb-1.5">Selamat Datang di Sistem Informasi Magang</h1>
                <p class="text-xs text-white-75 mb-0 mx-auto" style="max-width: 620px;">
                    Terima kasih telah membuat akun. Sebelum melanjutkan, silakan lengkapi data diri Anda agar dapat mengajukan pendaftaran magang di Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban.
                </p>
            </div>

            <div class="card-body p-3 sm:p-4">
                <div class="row g-2.5 align-items-center">
                    <div class="col-md-7">
                        <h2 class="h6 fw-bold text-body mb-2">
                            <i class="bi bi-check-circle-fill text-success me-1.5"></i>Mengapa Wajib Melengkapi Profil?
                        </h2>
                        <ul class="list-group list-group-flush text-xs border-0 mb-3">
                            <li class="list-group-item bg-transparent px-0 py-1.5 border-0">
                                <i class="bi bi-patch-check text-primary me-1.5 fs-6 align-middle"></i>
                                <span><strong>Verifikasi Identitas Resmi:</strong> Data NIK, NIS/NIM, dan Instansi Anda akan diverifikasi oleh tim Admin.</span>
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-1.5 border-0">
                                <i class="bi bi-file-earmark-pdf text-danger me-1.5 fs-6 align-middle"></i>
                                <span><strong>Surat Balasan Otomatis:</strong> Data profil digunakan untuk mencetak Surat Balasan Penerimaan Magang PDF.</span>
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-1.5 border-0">
                                <i class="bi bi-lightning-charge text-warning me-1.5 fs-6 align-middle"></i>
                                <span><strong>Pendaftaran 1-Klik:</strong> Setelah profil tersimpan, Anda bisa langsung memilih posisi magang dengan sekali klik.</span>
                            </li>
                        </ul>

                        <a href="{{ route('participant.onboarding.choose-type') }}" class="btn btn-primary fw-bold px-4 py-1.5 text-xs shadow-sm rounded-2">
                            Mulai Sekarang <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>

                    <div class="col-md-5 text-center">
                        <div class="p-3 bg-light rounded-2 border">
                            <div class="display-5 text-primary mb-2">
                                🎓
                            </div>
                            <h3 class="fw-bold text-body mb-0.5 text-xs">Dinas Komunikasi dan Informatika, Statistik dan Persandian</h3>
                            <p class="text-muted text-xs mb-0">Kabupaten Tuban, Jawa Timur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
