<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            // Clear session and logout
            session()->forget('auth.2fa');
            Auth::logout();

            if ($request->is('*/verify')) {
                return redirect()->route('login', app()->getLocale())
                    ->withErrors(['email' => __('userNotification.sessionExpired')]);
            }

            return redirect()->back()
                ->withErrors(['error' => __('userNotification.pageExpired')]);
        }
    }
}
