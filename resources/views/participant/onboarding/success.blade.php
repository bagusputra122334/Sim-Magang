@extends('layouts.participant')

@section('title', 'Profil Berhasil Disimpan — Onboarding')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 4])

        {{-- Success Card --}}
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden text-center">
            <div class="card-body p-4 p-md-5">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4"
                     style="width: 90px; height: 90px; font-size: 3rem;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h1 class="display-6 fw-extrabold text-body mb-2">Profil Berhasil Disimpan</h1>
                <p class="lead text-muted mb-4 mx-auto" style="max-width: 580px;">
                    Profil Anda telah lengkap. Sekarang Anda dapat mengajukan pendaftaran magang di Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban.
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary btn-lg fw-bold px-5 shadow-sm">
                        <i class="bi bi-send-fill me-2"></i> Mulai Daftar Magang
                    </a>
                    <a href="{{ route('participant.dashboard') }}" class="btn btn-outline-secondary btn-lg fw-semibold px-4">
                        <i class="bi bi-speedometer2 me-2"></i> Buka Dashboard Saya
                    </a>
                </div>
            </div>
            <div class="card-footer bg-light py-3 border-top text-muted small">
                <i class="bi bi-shield-check me-1 text-success"></i>
                Sistem Informasi Magang — Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban
            </div>
        </div>
    </div>
</div>
@endsection
