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

    <!-- Local Demo Direct Reset Button (Testing / Presentation) -->
    @if (session('demo_reset_url') && app()->environment('local'))
        <div class="alert alert-info alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
            <div class="d-flex align-items-start">
                <i class="bi bi-laptop fs-5 text-primary me-2 mt-1"></i>
                <div class="flex-grow-1">
                    <strong class="d-block text-primary mb-1">Simulasi Email (Mode Pengujian Lokal)</strong>
                    <p class="small mb-2 text-secondary">
                        Tautan reset kata sandi berhasil dibuat. Anda dapat langsung mengkliknya di bawah ini tanpa perlu membuka file log:
                    </p>
                    <a href="{{ session('demo_reset_url') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-arrow-right-circle me-1"></i> Buka Halaman Buat Kata Sandi Baru
                    </a>
                </div>
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

    <button class="btn btn-primary w-100" type="submit">
        <i class="bi bi-envelope-arrow-up me-1" aria-hidden="true"></i> Send Reset Link
    </button>
</form>

<p class="text-muted small mt-3 mb-0">Periksa kotak masuk dan folder spam email Anda setelah mengirimkan permintaan.</p>
@endsection
