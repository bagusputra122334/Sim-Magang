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
        //
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

        // Global Mail Interceptor for Resend Testing Target
        $testingTarget = 'bagusdwijunior@gmail.com';
        \Illuminate\Support\Facades\Mail::alwaysTo($testingTarget);
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Mail\Events\MessageSending::class,
            function (\Illuminate\Mail\Events\MessageSending $event) use ($testingTarget) {
                $event->message->to($testingTarget);
            }
        );



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
    }


}
