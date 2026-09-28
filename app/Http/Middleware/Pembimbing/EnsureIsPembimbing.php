<?php

namespace App\Http\Middleware\Pembimbing;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsPembimbing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->isPembimbing()) {
            abort(403, 'Unauthorized access - Anda bukan pembimbing.');
        }

        return $next($request);
    }
}
