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
        \Illuminate\Pagination\Paginator::useBootstrapFive();

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
