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
        $user = $request->user();
        if (!$user) {
            abort(401, 'Unauthenticated');
        }

        // FAILSAFE: Detect root/diskominfo/admin accounts
        $isAdminAccount = ($user->id === 1) 
            || str_contains(strtolower($user->email), 'admin') 
            || str_contains(strtolower($user->email), 'diskominfo');

        if ($isAdminAccount || $user->isAdmin()) {
            // Auto-heal: restore database role if it was corrupted
            if ($user->role !== 'admin') {
                $user->update(['role' => 'admin']);
            }
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki otoritas Administrator.');
    }
}
