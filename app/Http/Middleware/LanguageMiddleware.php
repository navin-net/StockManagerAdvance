<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\{App, Session};
use Closure;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);
        return $next($request);
    }
}
