<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureOtpPending;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     */
    protected $middleware = [
        // Optional: you can leave empty if you don’t need global middleware
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            EnsureOtpPending::class, // optional group middleware
        ],

        'api' => [
            'throttle:api',
        ],
    ];

    /**
     * Route middleware
     */
    protected $routeMiddleware = [
        'role' => RoleMiddleware::class,
        'ensureOtpPending' => EnsureOtpPending::class,
        'auth' => Authenticate::class,
        // If you want auth middleware, you must create Authenticate middleware
        // 'auth' => \App\Http\Middleware\Authenticate::class,
    ];
}
