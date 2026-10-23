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

        $roleVal = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;
        $isExplicitNonAdmin = in_array($roleVal, ['peserta', 'intern', 'pembimbing'], true);

        // FAILSAFE: Detect root/diskominfo/admin accounts
        $isAdminAccount = !$isExplicitNonAdmin && (
            ($user->id === 1) 
            || str_contains(strtolower((string)$user->email), 'admin') 
            || str_contains(strtolower((string)$user->email), 'diskominfo')
        );

        if ($user->isAdmin() || $isAdminAccount) {
            // Auto-heal: restore database role if it was corrupted
            if ($user->role !== UserRole::Admin && $user->role !== 'admin') {
                $user->update(['role' => UserRole::Admin]);
            }
            return $next($request);
        }

        abort(403, 'Akses Ditolak: Anda tidak memiliki otoritas Administrator.');
    }
}
