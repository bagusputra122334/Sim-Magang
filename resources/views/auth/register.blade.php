@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-2 text-center text-md-start">
        <h1 class="h4 mb-0 fw-bold">Daftar Akun Baru</h1>
        <p class="text-muted small mb-0">Buat akun baru SIMAGANG Diskominfo Tuban.</p>
    </div>

    <!-- Nama Lengkap -->
    <div class="mb-2">
        <label class="form-label small mb-1" for="name">Nama Lengkap</label>
        <input class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Lengkap Anda">
        @error('name')
            <div class="invalid-feedback small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Nama lengkap wajib diisi.</div>
        @enderror
    </div>

    <!-- Alamat Email -->
    <div class="mb-2">
        <label class="form-label small mb-1" for="email">Alamat Email</label>
        <input class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Masukkan email yang valid.</div>
        @enderror
    </div>

    <!-- Kata Sandi -->
    <div class="mb-2">
        <label class="form-label small mb-1" for="password">Kata Sandi</label>
        <input class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
        @error('password')
            <div class="invalid-feedback small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Kata sandi minimal 8 karakter.</div>
        @enderror
    </div>

    <!-- Konfirmasi Kata Sandi -->
    <div class="mb-2">
        <label class="form-label small mb-1" for="password_confirmation">Konfirmasi Kata Sandi</label>
        <input class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
        @error('password_confirmation')
            <div class="invalid-feedback small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Ulangi kata sandi yang sama.</div>
        @enderror
    </div>

    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="terms" required>
        <label class="form-check-label small" for="terms">Saya menyetujui syarat dan ketentuan</label>
        <div class="invalid-feedback small">Anda harus menyetujui sebelum melanjutkan.</div>
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 w-100 py-2 px-4" type="submit">
        <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Create Account
    </button>

</form>
@endsection
