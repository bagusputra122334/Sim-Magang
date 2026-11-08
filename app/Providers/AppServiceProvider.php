<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\SmtpTestCommand::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Validation\Rules\Password::defaults(function () {
            return \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->numbers();
        });

        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Global Mail Interceptor (Only active if MAIL_INTERCEPT_ALL_TO is explicitly set in .env)
        if ($interceptTo = env('MAIL_INTERCEPT_ALL_TO')) {
            \Illuminate\Support\Facades\Mail::alwaysTo($interceptTo);
            \Illuminate\Support\Facades\Event::listen(
                \Illuminate\Mail\Events\MessageSending::class,
                function (\Illuminate\Mail\Events\MessageSending $event) use ($interceptTo) {
                    $event->message->to($interceptTo);
                }
            );
        }



        \Illuminate\Support\Facades\RateLimiter::for('login', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(5)->by($request->ip());
        });

        \Illuminate\Support\Facades\RateLimiter::for('registration-submission', function (\Illuminate\Http\Request $request) {
            return \Illuminate\Cache\RateLimiting\Limit::perMinute(3)->by($request->user()?->id ?: $request->ip());
        });

        RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = Auth::user();

            if ($user?->isAdmin()) {
                return route('admin.dashboard');
            }

            if ($user?->isPeserta()) {
                return route('participant.dashboard');
            }

            return '/';
        });

        // Kustomisasi Notifikasi Email Verifikasi Resmi SIMAGANG Pemkab Tuban (Bahasa Indonesia)
        \Illuminate\Auth\Notifications\VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('[SIMAGANG] ✉️ Verifikasi Alamat Email Pendaftar Magang')
                ->greeting('Halo, ' . $notifiable->name . '!')
                ->line('Terima kasih telah mendaftar di SIMAGANG (Sistem Informasi Manajemen Magang Pemerintah Kabupaten Tuban).')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda agar dapat mengajukan formasi magang.')
                ->action('Verifikasi Alamat Email', $url)
                ->line('Tautan verifikasi ini berlaku selama 60 menit.')
                ->line('Jika Anda tidak merasa mendaftar akun di SIMAGANG, silakan abaikan email ini.')
                ->salutation("Salam hangat,\nTim Pengelola SIMAGANG Pemerintah Kabupaten Tuban");
        });
    }


}
