@extends('layouts.participant')

@section('title', 'Pilih Jenis Peserta — Onboarding')

@push('styles')
<style>
    .onboarding-card {
        border: 2px solid var(--app-border);
        border-radius: 12px;
        background: var(--app-surface);
        padding: 1.25rem 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .onboarding-card:hover {
        border-color: #0d6efd;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -10px rgba(13, 110, 253, 0.2);
    }

    .onboarding-icon {
        font-size: 2.25rem;
        margin-bottom: 0.75rem;
        transition: transform 0.3s ease;
    }

    .onboarding-card:hover .onboarding-icon {
        transform: scale(1.08);
    }

    .badge-onboarding {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        padding: 0.25rem 0.65rem;
        border-radius: 50rem;
    }
</style>
@endpush

@section('content')
<div class="row justify-content-center py-2 sm:py-3">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 2])

        <div class="text-center mb-3">
            <h1 class="h4 fw-bold text-body mb-1">Pilih Jenis Peserta</h1>
            <p class="text-muted text-xs mx-auto mb-0" style="max-width: 540px;">
                Silakan pilih kategori peserta magang sebelum melanjutkan ke pengisian data profil.
            </p>
        </div>

        <div class="row g-2.5 justify-content-center">
            {{-- Card 1: Mahasiswa --}}
            <div class="col-md-6">
                <div class="onboarding-card h-100 text-center d-flex flex-column justify-content-between"
                     onclick="window.location.href='{{ route('participant.profile.create', ['type' => 'university']) }}'">
                    <div>
                        <div class="badge-onboarding bg-primary bg-opacity-10 text-primary d-inline-block mb-2">
                            PERGURUAN TINGGI
                        </div>
                        <div class="onboarding-icon text-primary">🎓</div>
                        <h2 class="h5 fw-bold text-body mb-1">Mahasiswa</h2>
                        <p class="text-muted text-xs mb-3">
                            Peserta dari Perguruan Tinggi, Universitas, atau Institut.
                        </p>

                        <div class="bg-light rounded-2 p-2.5 text-start mb-3 text-xs border">
                            <div class="fw-bold text-body mb-1"><i class="bi bi-info-circle me-1 text-primary"></i> Data yang dibutuhkan:</div>
                            <ul class="mb-0 ps-3 text-muted">
                                <li>Universitas / Perguruan Tinggi</li>
                                <li>NIM (Nomor Induk Mahasiswa)</li>
                                <li>Program Studi & Semester</li>
                            </ul>
                        </div>
                    </div>

                    <a href="{{ route('participant.profile.create', ['type' => 'university']) }}"
                       class="btn btn-primary fw-bold text-xs shadow-sm w-100 rounded-2 py-1.5 px-3">
                        <i class="bi bi-check-circle-fill me-1.5"></i> Pilih Mahasiswa
                    </a>
                </div>
            </div>

            {{-- Card 2: Siswa --}}
            <div class="col-md-6">
                <div class="onboarding-card h-100 text-center d-flex flex-column justify-content-between"
                     onclick="window.location.href='{{ route('participant.profile.create', ['type' => 'student']) }}'">
                    <div>
                        <div class="badge-onboarding bg-success bg-opacity-10 text-success d-inline-block mb-2">
                            SEKOLAH MENENGAH / SMK
                        </div>
                        <div class="onboarding-icon text-success">🏫</div>
                        <h2 class="h5 fw-bold text-body mb-1">Siswa / SMK</h2>
                        <p class="text-muted text-xs mb-3">
                            Peserta dari SMA / SMK / MA.
                        </p>

                        <div class="bg-light rounded-2 p-2.5 text-start mb-3 text-xs border">
                            <div class="fw-bold text-body mb-1"><i class="bi bi-info-circle me-1 text-success"></i> Data yang dibutuhkan:</div>
                            <ul class="mb-0 ps-3 text-muted">
                                <li>Nama Sekolah (SMA / SMK / MA)</li>
                                <li>NIS / NISN</li>
                                <li>Jurusan / Keahlian</li>
                            </ul>
                        </div>
                    </div>

                    <a href="{{ route('participant.profile.create', ['type' => 'student']) }}"
                       class="btn btn-success fw-bold text-xs shadow-sm w-100 rounded-2 py-1.5 px-3">
                        <i class="bi bi-check-circle-fill me-1.5"></i> Pilih Siswa / SMK
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
