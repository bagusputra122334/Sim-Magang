@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<form method="POST" action="{{ route('password.store') }}" class="needs-validation" novalidate>
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') ?? old('token', $request->token) }}">

    <div class="mb-3 text-center text-md-start">
        <h1 class="h4 mb-0 fw-bold">Reset Password</h1>
        <p class="text-muted small mb-0">Buat kata sandi baru untuk akun Anda.</p>
    </div>

    <!-- Alamat Email -->
    <div class="mb-2.5">
        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1" for="email">Alamat Email</label>
        <input class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback text-xs">{{ $message }}</div>
        @else
            <div class="invalid-feedback text-xs">Masukkan email yang valid.</div>
        @enderror
    </div>

    <!-- Kata Sandi -->
    <div class="mb-2.5">
        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1" for="password">Kata Sandi Baru</label>
        <input class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 mb-0">Password minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
        @error('password')
            <div class="invalid-feedback text-xs">{{ $message }}</div>
        @else
            <div class="invalid-feedback text-xs">Kata sandi minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</div>
        @enderror
    </div>

    <!-- Konfirmasi Kata Sandi -->
    <div class="mb-3">
        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
        <input class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('password_confirmation') is-invalid @enderror" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
        @error('password_confirmation')
            <div class="invalid-feedback text-xs">{{ $message }}</div>
        @else
            <div class="invalid-feedback text-xs">Ulangi kata sandi yang sama.</div>
        @enderror
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-lg font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow w-100 py-1.5 px-4 text-xs" type="submit">
        <i class="bi bi-shield-lock me-1" aria-hidden="true"></i> Reset Password
    </button>

</form>
@endsection
