@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-2 text-center text-md-start">
        <h1 class="h4 mb-0 fw-bold">Masuk Akun</h1>
        <p class="text-muted small mb-0">Masuk ke portal SIMAGANG Diskominfo Tuban.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show mb-2 p-2 px-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill text-success fs-6 me-2 flex-shrink-0"></i>
                <div class="flex-grow-1 small">{{ session('status') }}</div>
                <button type="button" class="btn-close p-2 ms-2" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        </div>
    @endif

    <div class="mb-2">
        <label class="form-label small mb-1" for="email">Alamat Email</label>
        <input class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Masukkan alamat email yang valid.</div>
        @enderror
    </div>

    <div class="mb-2" x-data="{ showPassword: false }">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <label class="form-label small mb-0" for="password">Kata Sandi</label>
            @if (Route::has('password.request'))
                <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
            @endif
        </div>
        <div class="position-relative relative">
            <input class="form-control form-control-sm @error('password') is-invalid @enderror pe-5 pr-10" id="password" :type="showPassword ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            <button type="button" 
                    @click="showPassword = !showPassword" 
                    id="togglePasswordBtn"
                    class="btn btn-link text-secondary text-slate-500 position-absolute top-50 end-0 translate-middle-y me-2 p-0 text-decoration-none border-0 bg-transparent focus:outline-none" 
                    style="z-index: 5;" 
                    aria-label="Tampilkan atau sembunyikan kata sandi">
                <i class="bi bi-eye-fill fs-6" :class="showPassword ? 'bi-eye-slash-fill' : 'bi-eye-fill'" id="togglePasswordIcon"></i>
            </button>
        </div>
        @error('password')
            <div class="invalid-feedback d-block small">{{ $message }}</div>
        @else
            <div class="invalid-feedback small">Kata sandi wajib diisi.</div>
        @enderror
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function() {
                    if (typeof window.Alpine === 'undefined') {
                        const isPassword = passwordInput.getAttribute('type') === 'password';
                        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                        if (icon) {
                            if (isPassword) {
                                icon.classList.remove('bi-eye-fill');
                                icon.classList.add('bi-eye-slash-fill');
                            } else {
                                icon.classList.remove('bi-eye-slash-fill');
                                icon.classList.add('bi-eye-fill');
                            }
                        }
                    }
                });
            }
        });
    </script>

    <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label small" for="remember_me">Ingat saya</label>
    </div>

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-xl font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md hover:opacity-90 w-100 py-2 px-4" type="submit">
        <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i> Masuk Akun
    </button>

</form>
@endsection
