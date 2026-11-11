<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateHostHeader
{
    /**
     * Handle an incoming request and validate the HTTP Host header against trusted domains.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        if ($this->isHostAllowed($host)) {
            return $next($request);
        }

        abort(400, 'Invalid Host Header: Host header tidak dikenali atau tidak diizinkan oleh kebijakan keamanan sistem.');
    }

    /**
     * Check if the given host is in the trusted whitelist.
     */
    protected function isHostAllowed(string $host): bool
    {
        $hostLower = strtolower(trim($host));

        // Default whitelist for internal servers and local development
        $allowed = [
            'localhost',
            '127.0.0.1',
            '::1',
            '10.9.9.135', // Diskominfo SP Kab. Tuban internal staging IP
        ];

        // Add host from config('app.url')
        if ($appUrlHost = parse_url(config('app.url'), PHP_URL_HOST)) {
            $allowed[] = strtolower($appUrlHost);
        }

        // Add hosts configured in TRUSTED_HOSTS environment variable (comma-separated)
        if ($envHosts = env('TRUSTED_HOSTS')) {
            foreach (explode(',', $envHosts) as $item) {
                $trimmed = strtolower(trim($item));
                if ($trimmed !== '') {
                    $allowed[] = $trimmed;
                }
            }
        }

        // Check exact match and wildcard patterns
        foreach ($allowed as $pattern) {
            if ($pattern === $hostLower) {
                return true;
            }

            // Pattern starts with wildcard, e.g. *.tubankab.go.id
            if (str_starts_with($pattern, '*.')) {
                $domain = substr($pattern, 2);
                if ($hostLower === $domain || str_ends_with($hostLower, '.' . $domain)) {
                    return true;
                }
            }
        }

        // Always allow official Tuban Regency domain and all subdomains
        if ($hostLower === 'tubankab.go.id' || str_ends_with($hostLower, '.tubankab.go.id')) {
            return true;
        }

        return false;
    }
}

