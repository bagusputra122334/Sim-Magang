<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        if ($this->shouldVerifyTurnstile()) {
            $rules['cf-turnstile-response'] = ['required', 'string', new \App\Rules\TurnstileRule];
        }

        return $rules;
    }

    /**
     * Determine if Turnstile CAPTCHA verification should be required (after 3 failed attempts).
     */
    public function shouldVerifyTurnstile(): bool
    {
        $siteKey = config('services.turnstile.site_key');
        $secretKey = config('services.turnstile.secret_key');

        if (empty($siteKey) || empty($secretKey) || app()->environment('testing')) {
            return false;
        }

        $cumulative = max(
            RateLimiter::attempts($this->cumulativeThrottleKey()),
            RateLimiter::attempts($this->ipCumulativeThrottleKey())
        );

        return RateLimiter::attempts($this->throttleKey()) >= 3
            || RateLimiter::attempts($this->ipThrottleKey()) >= 3
            || $cumulative >= 3;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required'                 => 'Alamat email wajib diisi.',
            'email.email'                    => 'Masukkan alamat email yang valid.',
            'password.required'              => 'Kata sandi wajib diisi.',
            'cf-turnstile-response.required' => 'Silakan centang verifikasi keamanan CAPTCHA.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            'email'    => Str::lower(trim((string) $this->input('email'))),
            'password' => (string) $this->input('password'),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey(), 60);
            RateLimiter::hit($this->ipThrottleKey(), 60);
            $emailCum = RateLimiter::hit($this->cumulativeThrottleKey(), 3600); // 1 jam akumulasi
            $ipCum = RateLimiter::hit($this->ipCumulativeThrottleKey(), 3600);
            $cumulativeFailures = max($emailCum, $ipCum);

            $emailAttempts = RateLimiter::attempts($this->throttleKey());
            $ipAttempts = RateLimiter::attempts($this->ipThrottleKey());
            $currentCycleAttempts = max($emailAttempts, $ipAttempts);

            // Logika Siklus Bertingkat (Escalated Lockout):
            // Siklus 3: Total akumulasi gagal >= 15x -> Lockout 15 Menit (900 detik)
            if ($cumulativeFailures >= 15 && $currentCycleAttempts >= 5) {
                $this->applyLockout(900);
            }
            // Siklus 2: Total akumulasi gagal >= 10x -> Lockout 5 Menit (300 detik) + Kirim Email Alert
            elseif ($cumulativeFailures >= 10 && $currentCycleAttempts >= 5) {
                $this->applyLockout(300);
                $this->sendSecurityAlertMail($credentials['email']);
            }
            // Siklus 1: 5x gagal -> Lockout 60 Detik Penuh (1 menit)
            elseif ($currentCycleAttempts >= 5) {
                $this->applyLockout(60);
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->ipThrottleKey());
        RateLimiter::clear($this->cumulativeThrottleKey());
        RateLimiter::clear($this->ipCumulativeThrottleKey());
        RateLimiter::clear($this->lockoutTimerKey());
        RateLimiter::clear($this->ipLockoutTimerKey());
    }

    /**
     * Apply lockout penalty period in seconds.
     */
    protected function applyLockout(int $seconds): void
    {
        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->ipThrottleKey());
        RateLimiter::clear($this->lockoutTimerKey());
        RateLimiter::clear($this->ipLockoutTimerKey());

        RateLimiter::hit($this->lockoutTimerKey(), $seconds);
        RateLimiter::hit($this->ipLockoutTimerKey(), $seconds);

        for ($i = 0; $i < 5; $i++) {
            RateLimiter::hit($this->throttleKey(), $seconds);
            RateLimiter::hit($this->ipThrottleKey(), $seconds);
        }
    }

    /**
     * Kirim email peringatan keamanan otomatis ke pemilik akun saat Siklus 2 (10x gagal).
     */
    protected function sendSecurityAlertMail(string $email): void
    {
        try {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                \Illuminate\Support\Facades\Mail::to($user->email)->send(
                    new \App\Mail\SecurityAlertMail(
                        $user,
                        (string) $this->ip(),
                        now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s'),
                        (string) $this->userAgent()
                    )
                );
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('[SECURITY] Gagal mengirim email alert lockout: ' . $e->getMessage());
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $seconds = max(
            RateLimiter::availableIn($this->lockoutTimerKey()),
            RateLimiter::availableIn($this->ipLockoutTimerKey()),
            RateLimiter::tooManyAttempts($this->throttleKey(), 5) ? RateLimiter::availableIn($this->throttleKey()) : 0,
            RateLimiter::tooManyAttempts($this->ipThrottleKey(), 5) ? RateLimiter::availableIn($this->ipThrottleKey()) : 0
        );

        if ($seconds <= 0) {
            return;
        }

        event(new Lockout($this));

        // Audit Logging & Security Event Tracking
        \App\Support\AuditLogger::lockout(
            (string) $this->input('email'),
            (string) $this->ip(),
            (string) $this->userAgent()
        );

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request (by email + IP).
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Get the fallback rate limiting throttle key for the request (by IP only).
     */
    public function ipThrottleKey(): string
    {
        return 'login_ip|'.$this->ip();
    }

    /**
     * Get the cumulative failed attempts key (by email + IP).
     */
    public function cumulativeThrottleKey(): string
    {
        return 'login_cumulative|' . Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Get the cumulative failed attempts key (by IP only).
     */
    public function ipCumulativeThrottleKey(): string
    {
        return 'login_cumulative_ip|' . $this->ip();
    }

    /**
     * Get the dedicated lockout timer key (by email + IP).
     */
    public function lockoutTimerKey(): string
    {
        return 'login_lockout|' . Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Get the dedicated lockout timer key (by IP only).
     */
    public function ipLockoutTimerKey(): string
    {
        return 'login_ip_lockout|' . $this->ip();
    }
}
