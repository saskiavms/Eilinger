<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\TwoFactorMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            SetLocale::class,
        ]);

        $middleware->alias([
            'auth'             => Authenticate::class,
            'guest'            => RedirectIfAuthenticated::class,
            'verified'         => EnsureEmailIsVerified::class,
            'admin'            => IsAdmin::class,
            'twofactor'        => TwoFactorMiddleware::class,
            'setLocale'        => SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            session()->forget('auth.2fa');
            Auth::logout();

            if ($request->is('*/verify')) {
                return redirect()->route('login', app()->getLocale())
                    ->withErrors(['email' => __('userNotification.sessionExpired')]);
            }

            return redirect()->back()
                ->withErrors(['error' => __('userNotification.pageExpired')]);
        });
    })
    ->create();
