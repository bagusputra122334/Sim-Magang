<?php

namespace App\Http\Middleware\Participant;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsParticipant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login', absolute: false));
        }

        $user = Auth::user();
        if ($user === null || ! $user->isPeserta()) {
            abort(Response::HTTP_FORBIDDEN, 'Halaman ini hanya untuk Peserta Magang Terdaftar. Admin dapat menggunakan halaman Admin.');
        }

        return $next($request);
    }
}
