@extends('layouts.participant')

@section('title', 'Selamat Datang — Onboarding Peserta')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 1])

        {{-- Onboarding Welcome Hero Card --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white p-4 p-md-5 text-center position-relative">
                <div class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1.5 fw-semibold small mb-3">
                    <i class="bi bi-clock-history me-1"></i> Estimasi Pengisian: ±2 Menit
                </div>
                <h1 class="display-6 fw-extrabold mb-3">Selamat Datang di Sistem Informasi Magang</h1>
                <p class="lead text-white-75 mb-0 mx-auto" style="max-width: 620px;">
                    Terima kasih telah membuat akun. Sebelum melanjutkan, silakan lengkapi data diri Anda agar dapat mengajukan pendaftaran magang di Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.
                </p>
            </div>

            <div class="card-body p-4 p-md-5">
                <div class="row g-4 align-items-center">
                    <div class="col-md-7">
                        <h2 class="h5 fw-bold text-body mb-3">
                            <i class="bi bi-check-circle-fill text-success me-2"></i>Mengapa Wajib Melengkapi Profil?
                        </h2>
                        <ul class="list-group list-group-flush small border-0 mb-4">
                            <li class="list-group-item bg-transparent px-0 py-2 border-0">
                                <i class="bi bi-patch-check text-primary me-2 fs-5 align-middle"></i>
                                <span><strong>Verifikasi Identitas Resmi:</strong> Data NIK, NIS/NIM, dan Instansi Anda akan diverifikasi oleh tim Admin.</span>
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2 border-0">
                                <i class="bi bi-file-earmark-pdf text-danger me-2 fs-5 align-middle"></i>
                                <span><strong>Surat Balasan Otomatis:</strong> Data profil digunakan untuk mencetak Surat Balasan Penerimaan Magang PDF.</span>
                            </li>
                            <li class="list-group-item bg-transparent px-0 py-2 border-0">
                                <i class="bi bi-lightning-charge text-warning me-2 fs-5 align-middle"></i>
                                <span><strong>Pendaftaran 1-Klik:</strong> Setelah profil tersimpan, Anda bisa langsung memilih posisi magang dengan sekali klik.</span>
                            </li>
                        </ul>

                        <a href="{{ route('participant.onboarding.choose-type') }}" class="btn btn-primary btn-lg fw-bold px-5 shadow-sm">
                            Mulai Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>

                    <div class="col-md-5 text-center">
                        <div class="p-4 bg-light rounded-4 border">
                            <div class="display-1 text-primary mb-3">
                                🎓
                            </div>
                            <h3 class="h6 fw-bold text-body mb-1">Dinas Komunikasi, Informatika, Statistik dan Persandian</h3>
                            <p class="text-muted small mb-0">Kabupaten Tuban, Jawa Timur</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
