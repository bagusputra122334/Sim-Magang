@extends('layouts.participant')

@section('title', 'Ubah Profil Peserta')

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<div class="row justify-content-center mb-4">
    <div class="col-lg-10">


        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="h3 fw-bold mb-1">
                    <i class="bi bi-pencil-square text-primary me-2"></i>Ubah Data Profil
                </h1>
                <p class="text-muted small mb-0">
                    Perbarui data identitas, pendidikan, atau kontak tempat tinggal Anda.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4 border rounded-3">
                <h5 class="alert-heading fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan:</h5>
                <ul class="mb-0 small ps-3">
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

            <div class="d-flex justify-content-end gap-2 border-top pt-4">
                <a href="{{ route('participant.profile.index') }}" class="btn bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-4 py-2.5">
                    <i class="bi bi-x-lg me-1"></i> Batal
                </a>
                <button type="submit" class="btn bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md px-5 py-2.5">
                    <i class="bi bi-save me-2"></i> Perbarui Profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
