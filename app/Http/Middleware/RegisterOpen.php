<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RegisterOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $register = DB::table('pos_registers')
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->first();

        if (!$register) {
            return redirect()->route('pos.open-register');
        }

        // Restore session if missing
        session([
            'register_opened' => true,
            'cash_register_id' => $register->id,
        ]);

        return $next($request);
    }
}
