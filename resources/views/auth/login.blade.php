@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-4">
        <h1 class="h3 mb-1">Masuk Akun</h1>
        <p class="text-muted mb-0">Masuk ke portal SIM-MAGANG Diskominfo Tuban.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2 flex-shrink-0"></i>
                <div class="flex-grow-1">{{ session('status') }}</div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label" for="email">Alamat Email</label>
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Masukkan alamat email yang valid.</div>
        @enderror
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label mb-0" for="password">Kata Sandi</label>
            @if (Route::has('password.request'))
                <a class="small fw-semibold text-decoration-none" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
            @endif
        </div>
        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Kata sandi wajib diisi.</div>
        @enderror
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember_me">Ingat saya</label>
    </div>

    <button class="btn btn-primary w-100 py-2" type="submit">
        <i class="bi bi-box-arrow-in-right me-1" aria-hidden="true"></i> Masuk Akun
    </button>
</form>
@endsection
