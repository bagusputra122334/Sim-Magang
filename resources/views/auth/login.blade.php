@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
    @csrf

    <div class="mb-2 text-center text-md-start">
        <h1 class="h4 mb-0 fw-bold">Masuk Akun</h1>
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

    @php
        $turnstileSiteKey = config('services.turnstile.site_key');
        $email = old('email', '');
        $throttleKey = !empty($email) ? \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($email).'|'.request()->ip()) : '';
        $ipThrottleKey = 'login_ip|' . request()->ip();

        $cumulativeKey = !empty($email) ? 'login_cumulative|' . \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($email).'|'.request()->ip()) : '';
        $ipCumulativeKey = 'login_cumulative_ip|' . request()->ip();

        $cumulativeFailures = max(
            !empty($cumulativeKey) ? \Illuminate\Support\Facades\RateLimiter::attempts($cumulativeKey) : 0,
            \Illuminate\Support\Facades\RateLimiter::attempts($ipCumulativeKey)
        );

        $lockoutKey = !empty($email) ? 'login_lockout|' . \Illuminate\Support\Str::transliterate(\Illuminate\Support\Str::lower($email).'|'.request()->ip()) : '';
        $ipLockoutKey = 'login_ip_lockout|' . request()->ip();

        $secondsRemaining = max(
            !empty($lockoutKey) ? \Illuminate\Support\Facades\RateLimiter::availableIn($lockoutKey) : 0,
            \Illuminate\Support\Facades\RateLimiter::availableIn($ipLockoutKey),
            !empty($throttleKey) && \Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5) ? \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey) : 0,
            \Illuminate\Support\Facades\RateLimiter::tooManyAttempts($ipThrottleKey, 5) ? \Illuminate\Support\Facades\RateLimiter::availableIn($ipThrottleKey) : 0
        );

        $isLockedOut = $secondsRemaining > 0;
        $failedAttempts = max(
            !empty($throttleKey) ? \Illuminate\Support\Facades\RateLimiter::attempts($throttleKey) : 0,
            \Illuminate\Support\Facades\RateLimiter::attempts($ipThrottleKey),
            $cumulativeFailures
        );

        $showTurnstile = !empty($turnstileSiteKey) && $failedAttempts >= 3;
        $isCycleThree = $cumulativeFailures >= 15 && $isLockedOut;
    @endphp

    @if($isLockedOut)
        <div class="alert alert-warning py-2.5 px-3 small rounded-3 mb-3 border border-warning-subtle" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-lock-fill text-warning fs-5 me-2 flex-shrink-0"></i>
                <div class="flex-grow-1">
                    <strong class="text-slate-800 dark:text-slate-100">Akun Terkunci Sementara</strong>
                    <div class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                        Terlalu banyak percobaan gagal. Silakan coba lagi dalam <span id="countdownTimer" class="fw-bold text-danger">{{ $secondsRemaining }}</span> detik.
                    </div>
                </div>
            </div>
        </div>
        <script>
            (function() {
                var sec = {{ $secondsRemaining }};
                var timerEl = document.getElementById('countdownTimer');
                var submitBtn = document.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
                var interval = setInterval(function() {
                    sec--;
                    if (timerEl) timerEl.textContent = sec;
                    if (sec <= 0) {
                        clearInterval(interval);
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                        window.location.reload();
                    }
                }, 1000);
            })();
        </script>
    @endif

    <div class="mb-2">
        <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-1" for="email">Alamat Email</label>
        <input class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('email') is-invalid @enderror" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
        @error('email')
            <div class="invalid-feedback text-xs">{{ $message }}</div>
        @else
            <div class="invalid-feedback text-xs">Masukkan alamat email yang valid.</div>
        @enderror
    </div>

    @if($isCycleThree)
        <div class="p-3 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900 rounded-lg text-center mb-3">
            <div class="d-flex align-items-center justify-content-center text-danger mb-2">
                <i class="bi bi-shield-x fs-5 me-1.5"></i>
                <span class="fw-bold text-xs">Kolom Kata Sandi Dinonaktifkan</span>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="btn btn-danger btn-sm w-100 text-xs font-semibold py-1.5 shadow-sm">
                    <i class="bi bi-key-fill me-1"></i> Lupa Kata Sandi?
                </a>
            @endif
        </div>
    @else
        <div class="mb-2" x-data="{ showPassword: false }">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label block text-xs font-bold !text-slate-800 dark:!text-slate-200 mb-0" for="password">Kata Sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors" href="{{ route('password.request') }}">Lupa Kata Sandi?</a>
                @endif
            </div>
            <div class="position-relative relative">
                <input class="form-control form-control-sm py-1.5 px-2.5 text-xs rounded-2 @error('password') is-invalid @enderror pe-5 pr-10" id="password" :type="showPassword ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
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
                <div class="invalid-feedback d-block text-xs">{{ $message }}</div>
            @else
                <div class="invalid-feedback text-xs">Kata sandi wajib diisi.</div>
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
    @endif

    <div class="form-check mb-2.5">
        <input class="form-check-input" type="checkbox" name="remember" id="remember_me" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label text-xs text-slate-700 dark:text-slate-300 font-medium" for="remember_me">Ingat saya</label>
    </div>

    @if($showTurnstile && !$isLockedOut)
        <div class="mb-3 text-center">
            <div class="text-xs text-slate-600 dark:text-slate-400 mb-1.5 font-medium d-flex align-items-center justify-content-center gap-1">
                <i class="bi bi-shield-check text-indigo-600"></i>
                <span>Verifikasi Keamanan (Anti-Bot):</span>
            </div>
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            <div class="cf-turnstile d-inline-block" data-sitekey="{{ $turnstileSiteKey }}" data-theme="auto"></div>
            @error('cf-turnstile-response')
                <div class="text-danger text-xs mt-1 font-semibold text-start">{{ $message }}</div>
            @enderror
        </div>
    @endif

    <button class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white !text-white rounded-lg font-semibold shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow w-100 py-1.5 px-4 text-xs" type="submit" {{ $isLockedOut ? 'disabled' : '' }}>
        <i class="bi bi-box-arrow-right me-1" aria-hidden="true"></i> Masuk Akun
    </button>

</form>
@endsection
