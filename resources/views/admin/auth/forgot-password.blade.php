@extends('layouts.auth')

@section('title', 'Lupa Password Admin')

@section('content')
<form method="POST" action="{{ route('admin.password.email') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-4">
        <div class="d-inline-block p-2 bg-primary-subtle text-primary rounded-3 mb-2">
            <i class="bi bi-shield-lock-fill fs-4"></i>
        </div>
        <h1 class="h3 mb-1 fw-bold">Lupa Password Admin</h1>
        <p class="text-muted mb-0 small">Masukkan email Administrator SIM-MAGANG untuk menerima tautan atur ulang kata sandi.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                <div class="flex-grow-1 small">{{ session('status') }}</div>
                <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    <div class="mb-4">
        <label class="form-label fw-medium" for="email">Alamat Email Administrator</label>
        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@tubankab.go.id">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @else
            <div class="invalid-feedback">Masukkan email admin yang valid.</div>
        @enderror
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 w-100 py-2.5 px-4" type="submit">
        <i class="bi bi-envelope-arrow-up me-1" aria-hidden="true"></i> Kirim Tautan Reset Password Admin
    </button>
</form>

<div class="mt-4 pt-3 border-top text-center">
    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors text-xs">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
    </a>
</div>

@endsection
