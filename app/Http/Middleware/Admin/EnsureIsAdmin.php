<?php

namespace App\Http\Middleware\Admin;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login', absolute: false));
        }

        $user = Auth::user();

        // Strict role validation: User must exist and have Administrator role
        $isAdmin = false;
        if ($user !== null) {
            if ($user->role instanceof UserRole) {
                $isAdmin = $user->role->isAdmin();
            } else {
                $isAdmin = strtolower((string) $user->role) === 'admin';
            }
        }

        if (! $isAdmin) {
            abort(Response::HTTP_FORBIDDEN, 'Akses Ditolak: Anda tidak memiliki otoritas Administrator.');
        }

        return $next($request);
    }
}
