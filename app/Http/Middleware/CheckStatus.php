<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Re-fetch fresh from DB — never trust the session-cached model
            $user = Auth::user()->fresh();

            // User was deleted from DB
            if (!$user) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been removed. Please contact your administrator.']);
            }

            // User was blocked
            if ($user->status === 'blocked') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account has been blocked. Please contact your administrator.']);
            }
        }

        return $next($request);
    }
}