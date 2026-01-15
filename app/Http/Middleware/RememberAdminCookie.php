<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class RememberAdminCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->isMethod('get') &&
            $request->is('admin/*') &&
            auth()->check()
        ) {
            // Store for 7 days
            Cookie::queue(
                'admin_last_url',
                $request->fullUrl(),
                60 * 24 * 7
            );
        }
        return $next($request);
    }
}
