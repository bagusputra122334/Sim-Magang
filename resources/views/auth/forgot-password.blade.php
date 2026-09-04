@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
<form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-4">
        <h1 class="h3 mb-1">Lupa Kata Sandi</h1>
        <p class="text-muted mb-0">Masukkan email Anda untuk menerima tautan reset kata sandi.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                <div class="flex-grow-1">{{ session('status') }}</div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        </div>
    @endif


    <div class="mb-4">
        <label class="form-label" for="email">Alamat Email</label>
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Masukkan email yang valid.</div>
        @enderror
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 w-100 py-2.5 px-4" type="submit">
        <i class="bi bi-envelope-arrow-up me-1" aria-hidden="true"></i> Send Reset Link
    </button>

</form>

<p class="text-muted small mt-3 mb-0">Periksa kotak masuk dan folder spam email Anda setelah mengirimkan permintaan.</p>
@endsection
