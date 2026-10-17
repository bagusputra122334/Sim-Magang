@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-3 text-center text-md-start">
        <h1 class="h4 mb-0 fw-bold">Lupa Kata Sandi</h1>
        <p class="text-muted small mb-0">Masukkan email Anda untuk menerima tautan reset kata sandi.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-2.5 p-2 px-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-6 me-2"></i>
                <div class="flex-grow-1 text-xs">{{ session('status') }}</div>
                <button type="button" class="btn-close p-2 ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1" for="email">Alamat Email</label>
        <input class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback text-xs">{{ $message }}</div>
        @else
            <div class="invalid-feedback text-xs">Masukkan email yang valid.</div>
        @enderror
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-lg font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow w-100 py-1.5 px-4 text-xs" type="submit">
        <i class="bi bi-envelope-arrow-up me-1" aria-hidden="true"></i> Kirim Tautan Reset
    </button>

</form>

<p class="text-muted small mt-3 mb-0">Periksa kotak masuk dan folder spam email Anda setelah mengirimkan permintaan.</p>
@endsection
