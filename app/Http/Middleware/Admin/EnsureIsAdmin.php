<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login', absolute: false));
        }

        $user = Auth::user();
        if ($user === null || ! $user->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki izin Admin. Hanya Admin Dinas Komunikasi, Informatika, Statistik dan Persandian Kabupaten Tuban yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
