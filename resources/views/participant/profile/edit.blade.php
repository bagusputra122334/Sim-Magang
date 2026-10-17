@extends('layouts.participant')

@section('title', 'Ubah Profil Peserta')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="row justify-content-center mb-3">
    <div class="col-lg-10">

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h1 class="h4 fw-bold mb-0 text-slate-800 dark:text-slate-100">
                    <i class="bi bi-pencil-square text-primary me-1.5"></i>Ubah Data Profil
                </h1>
                <p class="text-muted text-xs mb-0">
                    Perbarui data identitas, pendidikan, atau kontak tempat tinggal Anda.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-3 p-2.5 text-xs border rounded-2">
                <h5 class="alert-heading text-xs font-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Terjadi Kesalahan:</h5>
                <ul class="mb-0 text-xs ps-3">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('participant.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('participant.profile._form')

            <div class="d-flex justify-content-end gap-2 border-top pt-2.5 mt-2">
                <a href="{{ route('participant.profile.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg font-bold shadow-sm transition-all px-3 py-1.5 text-xs">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-1.5 bg-blue-600 border border-transparent rounded-lg font-bold text-xs text-white hover:bg-blue-700 active:bg-blue-800 shadow-sm transition">
                    <i class="bi bi-save me-1.5"></i> Perbarui Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
