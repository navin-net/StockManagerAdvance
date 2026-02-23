<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieController extends Controller
{
    public function accept(Request $request)
    {
        // Create cookie for 1 year
        return back()->withCookie(
            cookie(
                'cookie_consent',
                'accepted',
                60 * 24 * 365
            )
        );
    }
}
