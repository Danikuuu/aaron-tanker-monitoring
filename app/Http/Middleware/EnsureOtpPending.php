<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureOtpPending
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('login_user_id')) {
            return redirect()->route('login')->withErrors(['email' => 'Please login first.']);
        }

        return $next($request);
    }
}

