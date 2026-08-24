@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-4">
        <h1 class="h3 mb-1">Daftar Akun Baru</h1>
        <p class="text-muted mb-0">Buat akun baru SIM-MAGANG Diskominfo Tuban.</p>
    </div>

    <!-- Nama Lengkap -->
    <div class="mb-3">
        <label class="form-label" for="name">Nama Lengkap</label>
        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Lengkap Anda">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
        @enderror
    </div>

    <!-- Alamat Email -->
    <div class="mb-3">
        <label class="form-label" for="email">Alamat Email</label>
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Masukkan email yang valid.</div>
        @enderror
    </div>

    <!-- Kata Sandi -->
    <div class="mb-3">
        <label class="form-label" for="password">Kata Sandi</label>
        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Kata sandi minimal 8 karakter.</div>
        @enderror
    </div>

    <!-- Konfirmasi Kata Sandi -->
    <div class="mb-3">
        <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
        <input class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Ulangi kata sandi yang sama.</div>
        @enderror
    </div>

    <div class="form-check mb-4">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label" for="terms">Saya menyetujui syarat dan ketentuan</label>
        <div class="invalid-feedback">Anda harus menyetujui sebelum melanjutkan.</div>
    </div>

    <button class="btn btn-primary w-100" type="submit">
        <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Create Account
    </button>
</form>
@endsection
