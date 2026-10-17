@extends('layouts.participant')

@section('title', 'Lengkapi Profil Peserta — Onboarding')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="row justify-content-center mb-3 py-1">
    <div class="col-lg-10 col-xl-9">
        @include('participant.onboarding._wizard', ['activeStep' => 3])

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h1 class="h4 fw-bold mb-0 text-slate-800 dark:text-slate-100">
                    <i class="bi bi-person-lines-fill text-primary me-1.5"></i>Lengkapi Biodata Peserta
                </h1>
                <p class="text-muted text-xs mb-0">
                    Isi formulir profil secara lengkap agar Anda dapat mengajukan pendaftaran magang di Diskominfo Tuban.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-3 p-2.5 text-xs border rounded-2">
                <h5 class="alert-heading text-xs font-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Terjadi Kesalahan Pengisian:</h5>
                <ul class="mb-0 text-xs ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('participant.profile.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            @include('participant.profile._form')

            <div class="d-flex justify-content-end gap-2 border-top pt-2.5 mt-2">
                <a href="{{ route('participant.onboarding.choose-type') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-bold shadow-sm transition-all px-3 py-1.5 text-xs">
                    <i class="bi bi-arrow-left me-1"></i> Kembali Pilih Kategori
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1.5 px-4 text-xs rounded-lg shadow-sm transition-all flex items-center justify-center gap-1.5">
                    <i class="bi bi-save"></i> Simpan Profil & Lanjutkan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
