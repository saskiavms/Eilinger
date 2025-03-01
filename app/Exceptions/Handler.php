<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof TokenMismatchException) {
                Log::debug('Token mismatch exception caught', [
                    'url' => $request->url(),
                    'method' => $request->method(),
                    'session' => session()->all(),
                    'has_2fa' => session()->has('auth.2fa'),
                    'is_verify_page' => $request->is('*/verify'),
                    'exception' => get_class($e)
                ]);

                // Clear any remaining session data
                session()->forget('auth.2fa');
                Auth::logout();

                if ($request->is('*/verify')) {
                    return redirect()->route('login', app()->getLocale())
                        ->withErrors(['email' => __('Your session has expired. Please login again.')]);
                }

                return redirect()->back()
                    ->withErrors(['error' => __('The page expired. Please try again.')]);
            }
        });
    }
}
