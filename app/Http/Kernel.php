<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Trust proxies (default for Laravel)
        \App\Http\Middleware\TrustProxies::class,

        // Handles CORS
        \Illuminate\Http\Middleware\HandleCors::class,

        // Prevents responses from being cached
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Validates POST size
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Trims strings
        \App\Http\Middleware\TrimStrings::class,

        // Converts empty strings to null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,

            // Shares errors from session to views
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            // Verifies CSRF token
            \App\Http\Middleware\VerifyCsrfToken::class,

            // Route model binding, etc.
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // API rate limiting
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',

            // Route bindings
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These can be assigned using route keys.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth'        => \App\Http\Middleware\Authenticate::class,
        
        'guest'       => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'      => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'    => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'    => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

    ];
}

