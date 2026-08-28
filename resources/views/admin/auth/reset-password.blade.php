@extends('layouts.auth')

@section('title', 'Reset Password Admin')

@section('content')
<form method="POST" action="{{ route('admin.password.store') }}" class="needs-validation" novalidate>
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') ?? old('token', $request->token) }}">

    <div class="mb-4">
        <div class="d-inline-block p-2 bg-primary-subtle text-primary rounded-3 mb-2">
            <i class="bi bi-key-fill fs-4"></i>
        </div>
        <h1 class="h3 mb-1 fw-bold">Reset Password Admin</h1>
        <p class="text-muted mb-0 small">Buat kata sandi baru untuk akun Administrator SIM-MAGANG.</p>
    </div>

    <!-- Alamat Email -->
    <div class="mb-3">
        <label class="form-label fw-medium" for="email">Alamat Email Administrator</label>
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="admin@tubankab.go.id">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Masukkan email yang valid.</div>
        @enderror
    </div>

    <!-- Kata Sandi -->
    <div class="mb-3">
        <label class="form-label fw-medium" for="password">Kata Sandi Baru</label>
        <input class="form-control @error('password') is-invalid @enderror" id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Kata sandi minimal 8 karakter.</div>
        @enderror
    </div>

    <!-- Konfirmasi Kata Sandi -->
    <div class="mb-4">
        <label class="form-label fw-medium" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
        <input class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
        @error('password_confirmation')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Ulangi kata sandi yang sama.</div>
        @enderror
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 w-100 py-2.5 px-4" type="submit">
        <i class="bi bi-shield-check me-1" aria-hidden="true"></i> Simpan Password Admin Baru
    </button>

</form>
@endsection
