@extends('layouts.participant')

@section('title', 'Profil Berhasil Disimpan — Onboarding')

@section('content')
<div class="row justify-content-center py-2 sm:py-3">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 4])

        {{-- Success Card --}}
        <div class="card border-0 shadow-sm rounded-2 overflow-hidden text-center">
            <div class="card-body p-3 sm:p-4">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-2.5"
                     style="width: 56px; height: 56px; font-size: 1.75rem;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>

                <h1 class="h4 fw-bold text-body mb-1.5">Profil Berhasil Disimpan</h1>
                <p class="text-xs text-muted mb-3 mx-auto" style="max-width: 580px;">
                    Profil Anda telah lengkap. Sekarang Anda dapat mengajukan pendaftaran magang di Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban.
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('participant.registrations.create') }}" class="btn btn-primary fw-bold text-xs px-3.5 py-1.5 shadow-sm rounded-2">
                        <i class="bi bi-send-fill me-1.5"></i> Mulai Daftar Magang
                    </a>
                    <a href="{{ route('participant.dashboard') }}" class="btn btn-outline-secondary fw-bold text-xs px-3 py-1.5 rounded-2">
                        <i class="bi bi-speedometer2 me-1.5"></i> Buka Dashboard Saya
                    </a>
                </div>
            </div>
            <div class="card-footer bg-light py-2 border-top text-muted text-xs">
                <i class="bi bi-shield-check me-1 text-success"></i>
                Sistem Informasi Magang — Dinas Komunikasi dan Informatika, Statistik dan Persandian Kabupaten Tuban
            </div>
        </div>
    </div>
</div>
@endsection
