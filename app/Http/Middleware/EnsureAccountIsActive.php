<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAccountIsActive
{
    public function handle(Request $request, Closure $next)
    {
        abort_unless($request->user()?->isActive(), 403, 'Account disabled');
        return $next($request);
    }
}
