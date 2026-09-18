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
        <div x-data="{ showPassword: false }" class="position-relative relative" style="position: relative; display: block; width: 100%;">
            <input class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="••••••••" style="padding-right: 2.5rem !important; box-sizing: border-box;">
            <button type="button" @click="showPassword = !showPassword" class="absolute right-0 inset-y-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 bg-transparent border-0" tabindex="-1" style="position: absolute !important; right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 50; border: none; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="height: 1.25rem; width: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showPassword" x-cloak style="display: none; height: 1.25rem; width: 1.25rem;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
        <p class="text-xs text-gray-500 text-muted mt-1 mb-0">Password minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
        @error('password')
            <div class="invalid-feedback small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Kata sandi minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</div>
        @enderror
    </div>

    <!-- Konfirmasi Kata Sandi -->
    <div class="mb-2">
        <label class="form-label small mb-1" for="password_confirmation">Konfirmasi Kata Sandi</label>
        <div x-data="{ showPassword: false }" class="position-relative relative" style="position: relative; display: block; width: 100%;">
            <input class="form-control form-control-sm @error('password_confirmation') is-invalid @enderror" id="password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" style="padding-right: 2.5rem !important; box-sizing: border-box;">
            <button type="button" @click="showPassword = !showPassword" class="absolute right-0 inset-y-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 bg-transparent border-0" tabindex="-1" style="position: absolute !important; right: 10px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 50; border: none; background: transparent; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="height: 1.25rem; width: 1.25rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="showPassword" x-cloak style="display: none; height: 1.25rem; width: 1.25rem;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
            </button>
        </div>
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
