@extends('layouts.participant')

@section('title', 'Lengkapi Profil Peserta — Onboarding')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="row justify-content-center mb-4 py-2">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 3])

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-person-lines-fill text-primary me-2"></i>Lengkapi Biodata Peserta
                </h1>
                <p class="text-muted small mb-0">
                    Isi formulir profil secara lengkap agar Anda dapat mengajukan pendaftaran magang di Diskominfo Tuban.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4 border rounded-3">
                <h5 class="alert-heading fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan Pengisian:</h5>
                <ul class="mb-0 small ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('participant.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('participant.profile._form')

            <div class="d-flex justify-content-end gap-2 border-top pt-4">
                <a href="{{ route('participant.onboarding.choose-type') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-1"></i> Kembali Pilih Kategori
                </a>
                <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                    <i class="bi bi-save me-2"></i> Simpan Profil & Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
